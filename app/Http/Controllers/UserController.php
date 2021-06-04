<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index() {
        return User::all();
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'login' => 'required|unique:users,login|min:4|max:16',
            'password' => 'required|confirmed|min:8|max:20',
            'full_name' => 'required|min:3|max:32',
            'email' => 'required|email|unique:users,email|max:255',
            'role' => 'in:admin,user'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return response([
            'message' => 'Account created successfully'
        ], 201);
    }
    public function avatar_get(int $id) {
        $user = User::find($id);

        $path = public_path();

        if (empty($user['profile_picture'])) {
            $path .= '/images/fovkegtBuLYCko4hwCwosUMjJqi.png';
        } else {
            $path .= strstr($user['profile_picture'], '/');
        }

        return response()->download($path);
    }
    public function avatar_create(Request $request) {
        $validated = $request->validate([
            'profile_picture' => 'required|image|mimes:png|max:4096'
        ]);

        $user = Auth::user();

        $file = file_get_contents($validated['profile_picture']);
        $path = '/images/' . $user['login'] . '.png';
        file_put_contents(public_path() . $path, $file);

        $user = User::find($user['id']);
        $user->update([
            'profile_picture' => ".$path"
        ]);

        return response([
            'message' => 'Account updated successfully'
        ], 201);
    }
    public function show(int $id) {
        $user = User::find($id);

        if (!isset($user)) {
            return response([
                'message' => 'User not found'
            ], 404);
        }

        return $user;
    }
    public function update(Request $request) {
        $validated = $request->validate([
            'login' => 'unique:users,login|min:4|max:16',
            'password' => 'string|confirmed|min:8|max:20',
            'full_name' => 'string|min:3|max:32',
            'email' => 'email|unique:users,email|max:255'
        ]);

        $user = Auth::user();
        $user = User::find($user['id']);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        if (isset($validated['login']) && isset($user['profile_picture'])) {
            $path = './images/' . $validated['login'] . '.png';
            $oldPath = public_path() . strstr($user['profile_picture'], '/');
            $newPath = public_path() . strstr($path, '/');

            $validated['profile_picture'] = $path;

            $file = file_get_contents($oldPath);

            unlink($oldPath);

            file_put_contents($newPath, $file);
        }

        $user->update($validated);

        return response([
            'message' => 'Account updated successfully'
        ], 201);
    }
    public function destroy(int $id) {
        $user = User::find($id);

        if (!isset($user)) {
            return response([
                'message' => 'User not found'
            ], 404);
        }

        $path = public_path() . strstr($user['profile_picture'], '/');

        unlink($path);

        // DO NOT DELETE
        User::destroy($id);

        return response([
            'message' => 'Account deleted successfully'
        ], 201);
    }
}
