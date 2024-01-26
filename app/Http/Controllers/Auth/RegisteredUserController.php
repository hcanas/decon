<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(RegisterUserRequest $request) : RedirectResponse
    {
        $temp_password = bin2hex(random_bytes(5));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'access_level' => $request->access_level,
            'password' => Hash::make($temp_password),
            'status' => 'active',
        ]);

        $user->temp_password = $temp_password;

        event(new Registered($user));

        return redirect()->back();
    }
}
