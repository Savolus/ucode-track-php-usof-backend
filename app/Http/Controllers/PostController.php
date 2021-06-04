<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller {
    public function index() {
        $posts = Post::all();
        $posts_with_categories = [];

        foreach ($posts as $post) {
            $arr = json_decode(json_encode($post), true);
            $keys = array_keys($arr);
            $values = array_values($arr);

            $arr_to_push = [];

            for ($i = 0; $i < count($keys); $i++) {
                $arr_to_push[$keys[$i]] = $values[$i];
            }

            $arr_to_push['categories'] = $post->categories()->allRelatedIds();

            array_push($posts_with_categories, $arr_to_push);
        }

        return $posts_with_categories;
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:4095',
            'categories' => 'required|array'
        ]);

        $user = Auth::user();
        $user = User::find($user['id']);

        $post = new Post();

        $post->title = $validated['title'];
        $post->content = $validated['content'];

        $post->user()->associate($user);
        $post->save();
        $post->categories()->attach($validated['categories']);

        return response([
            'message' => 'Post created successfully'
        ], 201);
    }
    public function store_comments(Request $request, int $id) {
        $validated = $request->validate([
            'content' => 'required|string|max:2047'
        ]);

        $user = Auth::user();
        $user = User::find($user['id']);
        $post = Post::find($id);

        if (!isset($post)) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        $comment = new Comment();

        $comment->content = $validated['content'];

        $comment->user()->associate($user);
        $comment->post()->associate($post);
        $comment->save();

        return response([
            'message' => 'Comment created successfully'
        ], 201);
    }
    public function store_likes(Request $request, int $id) {
        $validated = $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $user = Auth::user();
        $user = User::find($user['id']);
        $post = Post::find($id);

        if (!isset($post)) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        $like = new Like();

        $like->type = $validated['type'];

        $like->user()->associate($user);
        $like->post()->associate($post);
        $like->save();

        return response([
            'message' => 'Like created successfully'
        ], 201);
    }
    public function show($id) {
        $post = Post::find($id);

        if (!isset($post)) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        $posts_with_categories = [];

        $arr = json_decode(json_encode($post), true);
        $keys = array_keys($arr);
        $values = array_values($arr);

        for ($i = 0; $i < count($keys); $i++) {
            $posts_with_categories[$keys[$i]] = $values[$i];
        }

        $posts_with_categories['categories'] = $post->categories()->allRelatedIds();

        return $posts_with_categories;
    }
    public function show_comments($id){
        $post = Post::find($id);

        if (!isset($post)) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        return $post->comments()->get();
    }
    public function show_categories($id){
        $post = Post::find($id);

        if (!isset($post)) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        return $post->categories()->get();
    }
    public function show_likes($id){
        $post = Post::find($id);

        if (!isset($post)) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        return $post->likes()->get();
    }
    public function update(Request $request, int $id) {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'content' => 'string|max:4095',
            'categories' => 'array'
        ]);

        $user = Auth::user();
        $user = User::find($user['id']);
        $post = Post::find($id);

        if (isset($validated['categories'])) {
            $post->categories()->sync($validated['categories']);

            unset($validated['categories']);
        }

        $post->update($validated);

        return response([
            'message' => 'Post updated successfully'
        ], 201);
    }
    public function destroy(int $id) {
        $post = Post::find($id);

        if (!isset($post)) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        Post::destroy($id);

        return response([
            'message' => 'Post deleted successfully'
        ], 201);
    }
    public function destroy_likes(int $id) {
        $user = Auth::user();
        $user = User::find($user['id']);
        $post = Post::find($id);

        if (!isset($post)) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        $like = Like::where('user_id', $user['id'])->where('post_id', $id);

        Like::destroy($like['id']);

        return response([
            'message' => 'Post deleted successfully'
        ], 201);
    }
}
