<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Grade;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $users = User::all();
        $grades = Grade::all();
        return view('admin.dashboard.index')->with(compact('users','grades'));
    }
}
