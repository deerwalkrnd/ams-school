<?php

namespace App\Http\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'last_login')->get();

        return view('admin.dashboard.index')->with(compact('users'));
    }
}
