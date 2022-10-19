<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Hash;

class PasswordController
{
    public function update()
    {
        $user = request()->user();
        request()->validateWithBag('changePassword', [
            'current_password' => [
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Invalid current password.');
                    }
                }
            ],
            'password' => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);
        $user->password = request()->password;
        $user->save();
        return back()->with('success', 'Password Changed Successfully');
    }
}
