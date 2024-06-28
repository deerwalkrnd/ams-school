<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Grade;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class DashboardController extends Controller
{
    public function index(){
        try{$users = User::select('id','name','email', 'last_login')->get();
        return view('admin.dashboard.index')->with(compact('users'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('Oops! Error Occured. Please Try Again Later.');   
    }
    }
}
