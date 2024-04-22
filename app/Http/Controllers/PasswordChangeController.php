<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PasswordChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $user = auth()->user();

    if ($user && $user->roles()->exists()) {
        $userRoles = $user->roles()->pluck('role')->toArray();
        $userRole = $userRoles[0];
        if($userRole=='admin'){
            return view('auth.password.index',compact('userRole'))->with('success', 'Student Edited Successfully');
        }
        else{
            return view('auth.password.teacherindex', compact('userRole'))->with('success', 'Student Edited Successfully');
        }
    } else {
        return redirect()->route('login');
    }
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
