<?php

namespace App\Http\Controllers;

use App\Models\Role;
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
        $user->name = trim($request->input('name'));
        $user->email = trim($request->input('email'));
        $user->password = bcrypt(trim($request->input('password')));
        $user->save();
        $roles = $request->input('role', []);
        $user->role()->sync($roles);
        return redirect('/user')->with('success', 'Admin Successfully Created');
    }
    public function edit($id)
    {
        $users = User::find($id);
        $roles = Role::all();
        return view('user.edit', compact('users','roles'));
    }
    public function update(Request $request,$id){
        $users=User::find($id);
        $users->name=$request->input('name');
        $users->email=$request->input('email');
        $users->update();
        $users->role()->sync($request->input('role', []));
        return redirect('/user')->with('success', "User Updated Successfully ");
    }
    public function delete($id){
        $users=User::find($id);
        $users->delete();
        return redirect('/user')->with('success', "User Deleted Successfully");
    }


}
