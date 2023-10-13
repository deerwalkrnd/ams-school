<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\User;

class UserController extends Controller
{
    public function index(){
        $users=User::all();
        return view('user.index')->with(compact('users'));
    }


    public function create(){
        $users=User::all();
        return view('user.create')->with(compact('users'));

    }

    public function store(Request $request)

    {
        $user = new User;
        $user->name=trim($request->name);
        $user->email = trim($request->email);
        $user->password=trim($request->password);
        $user->save();
        return redirect('/user')->with('success', "Admin Successfully Created");

    }
    public function edit($id)
    {
        $users = User::find($id);
        return view('user.edit', compact('users'));
    }
    public function update(Request $request,$id){
        $users=User::find($id);
        $users->name=$request->input('name');
        $users->email=$request->input('email');
        $users->update();
        return redirect('/user')->with('success', "User Updated Successfully ");
    }
    public function delete($id){
        $users=User::find($id);
        $users->delete();
        return redirect('/user')->with('success', "User Deleted Successfully");
    }


}
