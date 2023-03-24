<?php

namespace App\Http\Controllers;

use App\Models\{Resource, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index(Request $request)
    {


        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        /*
        $order = $stripe->checkout->sessions->create([
            'success_url' => url(route('settings.index')),
            'cancel_url' => url(route('settings.index')),
            'customer_email' => auth()->user()->email,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => 2000,
                        'product_data' => [
                            'name' => 'T-shirt',
                            'images' => ["https://i.imgur.com/EHyR2nP.png"],
                        ],
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'payment_intent_data' => [
                'application_fee_amount' => (20 * 0.1) * 100,
            ],
        ], [
            'stripe_account' => Auth::user()->stripe_id,
        ]);

        return redirect($order->url);*/
        $user = Auth::user();
        if ($user->stripe_id) {
            $account = $stripe->accounts->retrieve($user->stripe_id);
            $status = $account->charges_enabled;

            return view('settings', compact('user', 'account', 'status'));
        }
        return view('settings', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bio' => 'required',
            'sbio' => 'required'
        ]);
        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $avatarName = Auth::user()->id . '.' . request()->avatar->getClientOriginalExtension();
            //Store the image in the public folder
            $request->avatar->storeAs('avatars', $avatarName, 'public');
            //Save the image name in the database
            $user = User::find(auth()->user()->id);
            $user->avatar = $avatarName;
            $user->save();
        }

        $user = User::find(auth()->user()->id);
        $user->bio = $request->get('bio');

        $user->sbio = $request->get('sbio');
        $user->save();

        return redirect()->back()->with('success', 'Settings updated!');
    }

    public function connect()
    {
        $user = User::find(auth()->user()->id);
        if ($user->stripe_id) {
            return redirect()->back()->with('error', 'You are already connected with Stripe.');
        }
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $account = $stripe->accounts->create([
            'type' => 'standard',
            'email' => $user->email,
            'business_type' => 'individual',
            'metadata' => [
                'user_id' => $user->id
            ]
        ]);
        $link = $stripe->accountLinks->create(
            [
                'account' => $account->id,
                'refresh_url' => url('/settings?stripe=visit'),
                'return_url' => url('/settings'),
                'type' => 'account_onboarding',
            ]
        );
        $user->stripe_id = $account->id;
        $user->save();
        return redirect($link->url);
    }
}
