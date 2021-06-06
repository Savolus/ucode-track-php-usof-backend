<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {
    public function get_user_comment_like($comment, $user) {
        $like = Like::where('user_id', $user['id'])->where('comment_id', $comment['id'])->first();

        if (empty($like)) {
            return null;
        }

        return [
            'type' => $like['type']
        ];
    }
    public function get_comment_rating($comment) {
        $rating = 0;

        $comment_likes_dislikes = $comment->likes()->get();

        foreach ($comment_likes_dislikes as $like_dislike) {
            $rating += $like_dislike['type'] === 'like' ? 1 : -1;
        }

        return $rating;
    }
    public function store_likes(Request $request, int $id) {
        $validated = $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $user = Auth::user();
        $user = User::find($user['id']);
        $comment = Comment::find($id);

        if (!isset($comment)) {
            return response([
                'message' => 'Comment not found'
            ], 404);
        }

        $is_like_exists = Like::where('user_id', $user['id'])->where('comment_id', $comment['id'])->first();

        if (empty($is_like_exists)) {
            $like = new Like();

            $like->type = $validated['type'];

            $like->user()->associate($user);
            $like->comment()->associate($comment);
            $like->save();

            if ($validated['type'] === 'like') {
                return response([
                    'message' => 'Comment liked successfully'
                ], 201);
            } else {
                return response([
                    'message' => 'Comment disliked successfully'
                ], 201);
            }
        }

        if ($is_like_exists->type === $validated['type']) {
            Like::destroy($is_like_exists->id);

            if ($validated['type'] === 'like') {
                return response([
                    'message' => 'Comment unliked successfully'
                ], 201);
            } else {
                return response([
                    'message' => 'Comment undisliked successfully'
                ], 201);
            }
        }

        $is_like_exists->type = $validated['type'];
        $is_like_exists->save();

        if ($validated['type'] === 'like') {
            return response([
                'message' => 'Comment liked successfully'
            ], 201);
        } else {
            return response([
                'message' => 'Comment disliked successfully'
            ], 201);
        }
    }
    public function show(int $id) {
        $user = Auth::user();
        $comment = Comment::find($id);
        $comment_response = [];

        if (!isset($comment)) {
            return response([
                'message' => 'Comment not found'
            ], 404);
        }

        $comment_destructered = json_decode(json_encode($comment), true);
        $comment_keys = array_keys($comment_destructered);
        $comment_values = array_values($comment_destructered);

        for ($i = 0; $i < count($comment_keys); $i++) {
            $comment_response[$comment_keys[$i]] = $comment_values[$i];
        }

        $comment_response['rating'] = $this->get_comment_rating($comment);

        if (!empty($user)) {
            $user = User::find($user['id']);

            $comment_response['self'] = $this->get_user_comment_like($comment, $user);
        }

        return $comment_response;
    }
    public function show_likes(int $id) {
        $comment = Comment::find($id);

        if (!isset($comment)) {
            return response([
                'message' => 'Comment not found'
            ], 404);
        }

        return Like::where('comment_id', $id)->get();
    }
    public function update(Request $request, int $id) {
        $validated = $request->validate([
            'content' => 'required|string|max:2047'
        ]);

        $comment = Comment::find($id);

        if (!isset($comment)) {
            return response([
                'message' => 'Comment not found'
            ], 404);
        }

        $comment->update($validated);

        return response([
            'message' => 'Comment updated successfully'
        ], 201);
    }
    public function destroy(int $id) {
        $comment = Comment::find($id);

        if (!isset($comment)) {
            return response([
                'message' => 'Comment not found'
            ], 404);
        }

        Comment::destroy($id);

        return response([
            'message' => 'Comment deleted successfully'
        ], 201);
    }
    public function destroy_likes(int $id) {
        $user = Auth::user();
        $user = User::find($user['id']);
        $comment = Comment::find($id);

        if (!isset($comment)) {
            return response([
                'message' => 'Comment not found'
            ], 404);
        }

        $like = Like::where('user_id', $user['id'])->where('comment_id', $id);

        Like::destroy($like['id']);

        return response([
            'message' => 'Comment like deleted successfully'
        ], 201);
    }
}
