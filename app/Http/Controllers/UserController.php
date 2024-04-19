<?php

namespace App\Http\Controllers;

use App\Mail\UserCredentialMail;
use App\Models\Role;
use Illuminate\Http\Request;
use App\models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

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
            'email' => 'required|email|unique:users,email,' . $users->id,
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
    try {
        $user = User::findOrFail($id);
        $user->roles()->detach();
        $user->delete();
        return redirect(route('user.index'))->with('success', 'User Successfully Deleted');
    } catch (QueryException $e) {
        if ($e->errorInfo[1] === 1451) {
            return redirect(route('user.index'))->with('error', 'Cannot delete user. There are related records in the database.');
        } else {
            return redirect(route('user.index'))->with('error', 'An error occurred while deleting the user.');
        }
    }
}
}
