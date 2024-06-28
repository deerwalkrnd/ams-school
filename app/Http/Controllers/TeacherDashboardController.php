<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  Illuminate\Support\Facades\Log;
use Exception;
class TeacherDashboardController extends Controller
{
    public function dashboard(){
        
        try{$section =  Auth::user()->section;
        return view('teacher.dashboard.index', compact('section'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('Oops! Error Occured. Please Try Again Later.');   
    }
    }
}
