<?php
 
namespace App\Http\Responses;

use App\Models\User;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
 
class LoginResponse implements LoginResponseContract
{
    /**
     * @param  $request
     * @return mixed
     */
    public function toResponse($request)
    {
        $user = User::where('email', $request->email)->first();

        $home = $user->roles->first()->role == "admin" ? '/home' : '/dashboard';
 
        return redirect()->intended($home);
    }
}