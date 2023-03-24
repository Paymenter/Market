<?php

namespace App\Http\Controllers;

use App\Models\{Resource, Orders};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->query('search')) {
            $resources = Resource::where('title', 'like', '%' . request()->query('search') . '%')->get()->where('status', 'published')->get();
        } else if (request()->query('category')) {
            if (request()->query('category') == 'Any')
                $resources = Resource::where('status', 'published')->get();
            else
                $resources = Resource::where('status', 'published')->get()->where('type', request()->query('category'));
        } else {
            $resources = Resource::where('status', 'published')->get();
        }
        return view('resources', compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->stripe_id)
            return redirect()->route('settings.index')->with('error', 'You need to connect your stripe account in order to upload a resource');
        return view('resources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->stripe_id)
            return redirect()->route('settings.index')->with('error', 'You need to connect your stripe account in order to upload a resource');
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:2000',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file' => 'mimes:zip|max:20480',
            'slogan' => 'required|string|max:255',
        ]);

        $resourceName = \Str::random(20) . '.' . request()->image->getClientOriginalExtension();
        if ($request->hasFile('image')) {
            $request->image->storeAs('resource', $resourceName, 'public');
        }
        // Generate a random characters for file name
        $filename = \Str::random(20);

        if ($request->hasFile('file')) {

            $request->file->storeAs('resource', $filename . '.' . request()->file->getClientOriginalExtension(), 'extensions');
        }
        // Add uploaded image to request
        $resource = new Resource(request()->all());
        $resource->image = $resourceName;
        $resource->user_id = auth()->user()->id;
        $resource->file = $filename . '.' . request()->file->getClientOriginalExtension();
        $resource->save();
        // Post created webhook to discord
        $webhookUrl = env('DISCORD_WEBHOOK_URL');
        $timestamp = date("c", strtotime("now"));

        $json_data = [
            'content' => 'New resource created by ' . auth()->user()->username,
            'username' => auth()->user()->username,
            'tts' => false,
            'avatar_url' => url('/storage/users/' . auth()->user()->avatar),
            'embeds' => [
                [
                    'title' => $resource->name,
                    'type' => 'rich',
                    'description' => $resource->description,
                    'url' => url('/resource/' . $resource->id),
                    'timestamp' => $timestamp,
                    'color' => hexdec("0000"),
                    'footer' => [
                        'text' => 'Resources',
                        'icon_url' => url('/storage/users/' . auth()->user()->avatar)
                    ],
                    'thumbnail' => [
                        'url' => url('/storage/resource/' . $resource->image)
                    ],
                    'author' => [
                        'name' => auth()->user()->name,
                        'url' => url('/users/' . auth()->user()->id),
                        'icon_url' => url('/storage/avatars/' . auth()->user()->avatar)
                    ],
                ],
            ],
        ];
        // Set headers
        $headers = [
            'Content-Type' => 'application/json',
        ];
        Http::post($webhookUrl, $json_data, $headers);
        return redirect('/resources')->with('success', 'Resource saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        if ($resource->status == 'pending' && auth()->user()->id != $resource->user_id && !auth()->user()->is_admin)
            return redirect('/resources')->with('error', 'Resource is pending approval');
        // If its user first time to view the resource, increment the views
        if (!session()->has('resource_' . $resource->id)) {
            $resource->increment('views');
            session()->put('resource_' . $resource->id, 1);
        }
        return view('resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        return view('resources.edit', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:2000',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file' => 'mimes:zip|max:20480',
            'slogan' => 'required|string|max:255',
        ]);
        $resourceName;
        if ($request->hasFile('image')) {
            $resourceName =  \Str::random(20) . '.' . request()->image->getClientOriginalExtension();
            $request->image->storeAs('resource', $resourceName, 'public');
        }
        // Generate a random characters for file name
        $filename;

        if ($request->hasFile('file')) {
            $filename = \Str::random(20);
            $request->file->storeAs('resource', $filename . '.' . request()->file->getClientOriginalExtension(), 'extensions');
        }

        // Add uploaded image to request
        $resource->name = request()->name;
        $resource->description = request()->description;
        if ($request->hasFile('image')) {
            $resource->image = $resourceName;
        }
        if ($request->hasFile('file')) {
            $resource->file = $filename . '.' . request()->file->getClientOriginalExtension();
        }
        $resource->slogan = request()->slogan;
        $resource->save();
        return back()->with('success', 'Resource updated!');
    }

    public function download(Resource $resource)
    {
        if (!$resource->price == 0 && !auth()->user()) {
            return back()->with('error', 'You need to buy this resource first!');
        }
        if (auth()->user() && !auth()->user()->orders()->where('resource_id', $resource->id)->exists() && !$resource->price == 0) {
            return back()->with('error', 'You need to buy this resource first!');
        }
        if (!auth()->user()->orders()->where('resource_id', $resource->id)->get()->first() && !$resource->price == 0) {
            return back()->with('error', 'You need to buy this resource first!');
        }
        if (!$resource->price == 0) {
            if (!auth()->user()->orders()->where('resource_id', $resource->id)->get()->first()) {
                return back()->with('error', 'You need to buy this resource first!');
            }
            if (!auth()->user()->orders()->where('resource_id', $resource->id)->get()->first()->status == 'paid') {
                return back()->with('error', 'You need to buy this resource first!');
            }
        }
        if (!Storage::disk('extensions')->exists('resource/' . $resource->file)) {
            return back()->with('error', 'File not found!');
        }
        if(!session()->has('resource_download_' . $resource->id)) {
            session()->put('resource_download_' . $resource->id, 1);
            $resource->increment('downloads');
        }
        $path = Storage::disk('extensions')->path('resource/' . $resource->file);
        try {
            return Response::download($path, $resource->name . '.' . pathinfo($path, PATHINFO_EXTENSION));
        } catch (\Exception $e) {
            return back()->with('error', 'File not found!');
        }
    }

    public function buyStripe(Resource $resource)
    {
        //if(!auth()->user()->resources->contains($resource->id) && $resource->price != 0)
        if (!$resource->user()->get()->first()->stripe_id) {
            return back()->with('error', 'User does not have a stripe account!');
        }
        if ($resource->user()->get()->first()->stripe_id == auth()->user()->stripe_id) {
            return back()->with('error', 'You cannot buy your own resource!');
        }
        if (auth()->user()->orders()->where('resource_id', $resource->id)->exists()) {
            return back()->with('error', 'You already bought this resource!');
        }
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $account = $stripe->accounts->retrieve($resource->user()->get()->first()->stripe_id);
        if (!$account->charges_enabled) {
            return back()->with('error', 'User does not have a stripe account!');
        }
        $resource->increment('sales');

        Orders::create([
            'user_id' => auth()->user()->id,
            'resource_id' => $resource->id,
            'stripe_id' => $order->id,
            'amount' => $resource->price,
            'status' => 'pending'
        ]);

        return redirect($order->url);
    }

    public function buyPaypal(Resource $resource)
    {
        if (!$resource->user()->get()->first()->paypal) {
            return back()->with('error', 'User does not have a Paypal account!');
        }
        // if ($resource->user()->get()->first()->id == auth()->user()->id) {
        //     return back()->with('error', 'You cannot buy your own resource!');
        // }
        if (auth()->user()->orders()->where('resource_id', $resource->id)->exists()) {
            if(auth()->user()->orders()->where('resource_id', $resource->id)->get()->first()->status == 'paid') {
                return back()->with('error', 'You already bought this resource!');
            }
        }
        $order = Orders::create([
            'user_id' => auth()->user()->id,
            'resource_id' => $resource->id,
            'amount' => $resource->price,
            'status' => 'pending'
        ]);
        $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        $paypal_email = $resource->user()->get()->first()->paypal;
        $return_url = route('resource.show', $resource->id);
        $cancel_url = route('resource.show', $resource->id);
        $notify_url = route('paypal.webhook');
        $currency = 'EUR';
        $querystring = '?cmd=_xclick&';
        $querystring .= 'business=' . urlencode($paypal_email) . '&';
        $querystring .= 'return=' . urlencode(stripslashes($return_url)) . '&';
        $querystring .= 'cancel_return=' . urlencode(stripslashes($cancel_url)) . '&';
        $querystring .= 'notify_url=' . urlencode($notify_url) . '&';
        $querystring .= 'item_name=' . urlencode($resource->name) . '&';
        $querystring .= 'item_number=' . urlencode($resource->id) . '&';
        $querystring .= 'amount=' . urlencode($resource->price) . '&';
        $querystring .= 'custom=' . urlencode($order->id) . '&';
        $querystring .= 'currency_code=' . urlencode($currency);
        
        dd($paypal_url . $querystring);
        return redirect($paypal_url . $querystring);
    }
}
