<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function dashboard(){
        $section =  Auth::user()->section;
        return view('teacher.dashboard.index', compact('section'));
    }
}
