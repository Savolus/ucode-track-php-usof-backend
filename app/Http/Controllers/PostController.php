<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller {
    public function index() {
        return Post::all();
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
        $user->posts()->save($post);

        return response([
            'message' => 'Post created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
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
