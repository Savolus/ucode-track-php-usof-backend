<?php

namespace App\Http\Controllers;

use App\Models\Category;
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

        $categories = [];

        foreach ($validated['categories'] as $category_id) {
            array_push($categories, Category::find($category_id));
        }

        $post = new Post();

        $post->title = $validated['title'];
        $post->content = $validated['content'];

        $post->user()->associate($user);

        $post->save();

        $post->categories()->saveMany($categories);
        // $user->posts()->save($post);

        return response([
            'message' => 'Post created successfully'
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
            $categories = [];

            foreach ($validated['categories'] as $category_id) {
                array_push($categories, Category::find($category_id));
            }

            $categories = $post->categories()->get();

            $post->categories()->saveMany($categories);

            unset($validated['categories']);
        }

        $post->update($validated);

        return response([
            'message' => 'Post updated successfully'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
