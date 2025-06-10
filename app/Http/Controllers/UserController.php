<?php

namespace App\Http\Controllers;

use App\Mail\UserCredentialMail;
use App\Models\Role;
use App\models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.user.index')->with(compact('users'));
    }

    public function create()
    {
        $users = User::all();
        $roles = Role::all();

        return view('admin.user.create')->with(compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'max:255',
                Rule::unique('users', 'name'),
            ],
            'email' => 'required|email|unique:users',
            'role' => 'required',
        ]);

        $user = new User;
        $user->name = trim($request->input('name'));
        $user->email = trim($request->input('email'));
        $user->password = bcrypt('password2@23');
        $user->save();
        $roles = $request->input('role');
        $user->roles()->sync($roles);

        Mail::to($user->email)->send(new UserCredentialMail($user, [Role::select('role')->where('id', $request->role)->first()->role]));

        return redirect(route('user.index'))->with('success', 'User Successfully Created');
    }

    public function edit($id)
    {
        $users = User::find($id);
        $roles = Role::all();

        return view('admin.user.edit', compact('users', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $users = User::find($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$users->id,
            'role' => 'required',
        ]);
        $users->name = trim($request->input('name'));
        $users->email = trim($request->input('email'));
        $users->save();
        $roles = $request->input('role', []);
        $users->roles()->sync($roles);

        return redirect(route('user.index'))->with('success', 'User Successfully Updated');
    }

    public function delete($id)
    {
        $users = User::find($id);
        $users->roles()->detach();
        $users->delete();

        return redirect(route('user.index'))->with('success', 'User Successfully Deleted');
    }
}
