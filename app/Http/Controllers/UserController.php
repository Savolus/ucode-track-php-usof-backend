<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function get_user_rating($user) {
        $rating = 0;

        $posts = $user->posts()->get();
        $comments = $user->comments()->get();

        foreach ($posts as $post) {
            $post_likes_dislikes = $post->likes()->get();
            $post_rating = 0;

            foreach ($post_likes_dislikes as $like_dislike) {
                $post_rating += $like_dislike['type'] === 'like' ? 1 : -1;
            }

            $rating += $post_rating;
        }

        foreach ($comments as $comment) {
            $comment_likes_dislikes = $comment->likes()->get();
            $comment_rating = 0;

            foreach ($comment_likes_dislikes as $like_dislike) {
                $comment_rating += $like_dislike['type'] === 'like' ? 1 : -1;
            }

            $rating += $comment_rating;
        }

        return $rating;
    }

    public function index() {
        $users = User::all();
        $users_response = [];

        foreach ($users as $user) {
            $user_destructered = json_decode(json_encode($user), true);
            $user_keys = array_keys($user_destructered);
            $user_values = array_values($user_destructered);

            $joined_user = [];

            for ($i = 0; $i < count($user_keys); $i++) {
                $joined_user[$user_keys[$i]] = $user_values[$i];
            }

            $joined_user['rating'] = $this->get_user_rating($user);

            array_push($users_response, $joined_user);
        }

        return $users_response;
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
        $user_response = [];

        if (!isset($user)) {
            return response([
                'message' => 'User not found'
            ], 404);
        }

        $user_destructered = json_decode(json_encode($user), true);
        $user_keys = array_keys($user_destructered);
        $user_values = array_values($user_destructered);

        for ($i = 0; $i < count($user_keys); $i++) {
            $user_response[$user_keys[$i]] = $user_values[$i];
        }

        $user_response['rating'] = $this->get_user_rating($user);

        return $user_response;
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
