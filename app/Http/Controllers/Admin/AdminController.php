<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Resource, User, Orders};
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $resources = Resource::all();
        $users = User::all();
        // Orders where date is past week

        $orders = Orders::where('created_at', '>=', now()->subDays(7))->get();
        return view('admin.index', compact('resources', 'users', 'orders'));
    }

}