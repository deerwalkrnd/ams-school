<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $pageTitle = 'User List';
        return view('user.index')->with(compact('users', 'pageTitle'));
    }


    public function create()
    {
        $users = User::all();
        $roles = Role::all();
        $pageTitle = 'Add New User';
        return view('user.create')->with(compact('users', 'roles', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|regex:/^[A-Za-z\s]+$/|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required',
        ]);

        $user = new User;
        $user->name = trim($request->input('name'));
        $user->email = trim($request->input('email'));
        $user->password = bcrypt(trim($request->input('password')));
        $user->save();

        $roles = $request->input('role', []);
        $user->roles()->sync($roles);
        return redirect('/user')->with('success', 'Admin Successfully Created');
    }
    public function edit($id)
    {
        $users = User::find($id);
        $roles = Role::all();
        $pageTitle = 'Edit User';
        return view('user.edit', compact('users', 'roles', 'pageTitle'));
    }
    public function update(Request $request, $id)
    {
        $users = User::find($id);
        $request->validate([
            'name' => 'required|regex:/^[A-Za-z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email,' . $users->id,
            'role' => 'required',
        ]);
        $users->name = trim($request->input('name'));
        $users->email = trim($request->input('email'));
        $users->save();
        $roles = $request->input('role', []);
        $users->roles()->sync($roles);
        return redirect('/user')->with('success', 'Admin Successfully Updated');
    }
    public function delete($id)
    {
        $users = User::find($id);
        $users->roles()->detach();
        $users->delete();
        return redirect('/user')->with('success','Admin Successfully Deleted');
    }
}
