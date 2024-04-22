<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     */

     public function update(User $user, array $input): void
     {
         Validator::make($input, [
             'current_password' => ['required', 'string'],
             'password' => $this->passwordRules(),
         ])->validate();
     
         // Check if the provided current password matches the user's current password
         if (!Hash::check($input['current_password'], $user->password)) {
             throw ValidationException::withMessages(['current_password' => __('The provided password does not match your current password.')]);
         }
     
         // Check if the new password matches the current password
         if (Hash::check($input['password'], $user->password)) {
             throw ValidationException::withMessages(['password' => __('The new password must not match the current password.')]);
         }
     
         // Update the user's password
         $user->forceFill([
             'password' => Hash::make($input['password']),
             'change_password_status' => '1',
             'last_password_change_datetime' => now(),
         ])->save();
     
         session()->flash('toast_success', 'Password Changed Successfully.');
     }
     
}
