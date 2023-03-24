<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = auth()->user();
        return view('users.index', compact('users'));
    }

    public function show(String $user)
    {   
        $user = User::where('username', $user)->first();
        if (!$user)
            return redirect()->route('home')->with('error', 'User not found');
        return view('users.show', compact('user'));
    }

    public function resources(String $user)
    {
        $user = User::where('username', $user)->first();
        if(!$user)
            return redirect()->route('home')->with('error', 'User not found');
        $resources = $user->resources;
        return view('users.resources', compact('resources'));
    }
}