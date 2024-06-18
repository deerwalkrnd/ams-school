<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->passwordRules(),
            'password_confirmation' => ['required', 'string', 'same:password'],
        ], [
            'current_password.current_password' => __('The provided password does not match your current password.'),
            'password_confirmation.same' => __('The password confirmation does not match the new password.'),
        ]);

        if ($validator->fails() && $validator->errors()->has('current_password')) {
            session()->flash('error', $validator->errors()->first('current_password'));
            return;
        }

        if (Hash::check($input['password'], $user->password)) {
            session()->flash('error', 'New password must be different from the current password.');
            return;
        }

        if ($validator->fails() && $validator->errors()->has('password_confirmation')) {
            session()->flash('error', $validator->errors()->first('password_confirmation'));
            return;
        }

        $user->forceFill([
            'password' => Hash::make($input['password']),
            'change_password_status' => '1',
            'last_password_change_datetime' => date('Y-m-d H:i:s'),
        ])->save();

        session()->flash('toast_success', 'Password Changed Successfully.');
    }
}
