<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller {
    public function get_user_post_like($post, $user) {
        $like = Like::where('user_id', $user['id'])->where('post_id', $post['id'])->first();

        if (empty($like)) {
            return null;
        }

        return [
            'type' => $like['type']
        ];
    }
    public function get_user_comment_like($comment, $user) {
        $like = Like::where('user_id', $user['id'])->where('comment_id', $comment['id'])->first();

        if (empty($like)) {
            return null;
        }

        return [
            'type' => $like['type']
        ];
    }
    public function get_post_rating($post) {
        $rating = 0;

        $post_likes_dislikes = $post->likes()->get();

        foreach ($post_likes_dislikes as $like_dislike) {
            $rating += $like_dislike['type'] === 'like' ? 1 : -1;
        }

        return $rating;
    }
    public function get_comment_rating($comment) {
        $rating = 0;

        $comment_likes_dislikes = $comment->likes()->get();

        foreach ($comment_likes_dislikes as $like_dislike) {
            $rating += $like_dislike['type'] === 'like' ? 1 : -1;
        }

        return $rating;
    }
    public function index() {
        $user = Auth::user();
        $posts = Post::all();
        $posts_response = [];

        foreach ($posts as $post) {
            $post_destructered = json_decode(json_encode($post), true);
            $post_keys = array_keys($post_destructered);
            $post_values = array_values($post_destructered);

            $joined_post = [];

            for ($i = 0; $i < count($post_keys); $i++) {
                $joined_post[$post_keys[$i]] = $post_values[$i];
            }

            $joined_post['categories'] = $post->categories()->allRelatedIds();
            $joined_post['rating'] = $this->get_post_rating($post);

            if (!empty($user)) {
                $user = User::find($user['id']);

                $joined_post['self'] = $this->get_user_post_like($post, $user);
            }

            array_push($posts_response, $joined_post);
        }

        return $posts_response;
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

        $is_like_exists = Like::where('user_id', $user['id'])->where('post_id', $post['id'])->first();

        if (empty($is_like_exists)) {
            $like = new Like();

            $like->type = $validated['type'];

            $like->user()->associate($user);
            $like->post()->associate($post);
            $like->save();

            if ($validated['type'] === 'like') {
                return response([
                    'message' => 'Post liked successfully'
                ], 201);
            } else {
                return response([
                    'message' => 'Post disliked successfully'
                ], 201);
            }
        }

        if ($is_like_exists->type === $validated['type']) {
            Like::destroy($is_like_exists->id);

            if ($validated['type'] === 'like') {
                return response([
                    'message' => 'Post unliked successfully'
                ], 201);
            } else {
                return response([
                    'message' => 'Post undisliked successfully'
                ], 201);
            }
        }

        $is_like_exists->type = $validated['type'];
        $is_like_exists->save();

        if ($validated['type'] === 'like') {
            return response([
                'message' => 'Post liked successfully'
            ], 201);
        } else {
            return response([
                'message' => 'Post disliked successfully'
            ], 201);
        }
    }
    public function show($id) {
        $user = Auth::user();
        $post = Post::find($id);
        $post_response = [];

        if (!isset($post)) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        $post_destructered = json_decode(json_encode($post), true);
        $post_keys = array_keys($post_destructered);
        $post_values = array_values($post_destructered);

        for ($i = 0; $i < count($post_keys); $i++) {
            $post_response[$post_keys[$i]] = $post_values[$i];
        }

        $post_response['categories'] = $post->categories()->allRelatedIds();
        $post_response['rating'] = $this->get_post_rating($post);

        if (!empty($user)) {
            $user = User::find($user['id']);

            $post_response['self'] = $this->get_user_post_like($post, $user);
        }

        return $post_response;
    }
    public function show_comments($id){
        $user = Auth::user();
        $post = Post::find($id);

        if (!isset($post)) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        $comments = $post->comments()->get();
        $comments_response = [];

        foreach ($comments as $comment) {
            $comment_destructered = json_decode(json_encode($comment), true);
            $comment_keys = array_keys($comment_destructered);
            $comment_values = array_values($comment_destructered);

            $joined_comment = [];

            for ($i = 0; $i < count($comment_keys); $i++) {
                $joined_comment[$comment_keys[$i]] = $comment_values[$i];
            }

            $joined_comment['rating'] = $this->get_comment_rating($post);

            if (!empty($user)) {
                $user = User::find($user['id']);

                $joined_comment['self'] = $this->get_user_comment_like($post, $user);
            }

            array_push($comments_response, $joined_comment);
        }

        return $comments_response;
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
            'message' => 'Post like deleted successfully'
        ], 201);
    }
}
