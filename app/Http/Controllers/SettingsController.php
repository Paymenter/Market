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

    public function paypal(Request $request)
    {
        $request->validate([
            'paypal' => 'required|email'
        ]);
        $user = User::find(auth()->user()->id);
        $user->paypal = $request->get('paypal');
        $user->save();
        return redirect()->back()->with('success', 'PayPal email updated!');
    }
}
