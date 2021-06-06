<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller {
    public function register(Request $request) {
        $validated = $request->validate([
            'login' => 'required|unique:users,login|min:4|max:16',
            'password' => 'required|confirmed|min:8|max:20',
            'full_name' => 'required|min:3|max:32',
            'email' => 'required|email|unique:users,email|max:255'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return response([
            'message' => 'Account created successfully'
        ], 201);
    }
    public function login(Request $request) {
        $validated = $request->validate([
            'login' => 'required|exists:users,login|min:4|max:16',
            'password' => 'required|min:8|max:20'
        ]);

        $user = User::where('login', $validated['login'])->first();

        if (empty($user) || !Hash::check($validated['password'], $user['password'])) {
            return response([
                'message' => 'Not authorithed'
            ], 401);
        }

        $token = Auth::attempt([
            'id' => $user['id'],
            'password' => $validated['password']
        ]);

        return response([
            'access_token' => $token
        ], 201);
    }
    public function logout(Request $request) {
        Auth::logout();

        return response([
            'message' => 'Successfully logout'
        ], 201);
    }
    public function password_reset(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $status = Password::sendResetLink($validated);

        $message = [
            'message' => $status === Password::RESET_LINK_SENT
            ? 'Emial with password reset is sent'
            : 'Emial with password reset isn\'t sent'
        ];

        return response($message, 201);
    }
    public function password_reset_confirm(Request $request, string $token) {
        $email = $request->query('email');

        $user = User::where('email', $email)->first();
        $status = Password::tokenExists($user, $token);

        Password::deleteToken($user);

        return response([
            'can_reset' => $status
        ]);
    }
}
