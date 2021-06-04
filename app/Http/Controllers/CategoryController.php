<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    public function index() {
        return Category::all();
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|unique:categories,title|max:255',
            'description' => 'required|string|max:2047'
        ]);

        Category::create($validated);

        return response([
            'message' => 'Category created successfully'
        ], 201);
    }
    public function show(int $id) {
        $category = Category::find($id);

        if (!isset($category)) {
            return response([
                'message' => 'Category not found'
            ], 404);
        }

        return $category;
    }
    public function posts(int $id) {
        $category = Category::find($id);

        if (!isset($category)) {
            return response([
                'message' => 'Category not found'
            ], 404);
        }

        $posts_id = $category->posts()->allRelatedIds();
        $posts_with_categories = [];

        foreach ($posts_id as $post_id) {
            $post = Post::find($post_id);

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
    public function update(Request $request, int $id) {
        $validated = $request->validate([
            'title' => 'string|unique:categories,title|max:255',
            'description' => 'string|max:2047'
        ]);

        $category = Category::find($id);

        if (!isset($category)) {
            return response([
                'message' => 'Category not found'
            ], 404);
        }

        $category->update($validated);

        return response([
            'message' => 'Category updated successfully'
        ], 201);
    }
    public function destroy(int $id) {
        $category = Category::find($id);

        if (!isset($category)) {
            return response([
                'message' => 'Category not found'
            ], 404);
        }

        // DO NOT DELETE
        Category::destroy($id);

        return response([
            'message' => 'Category deleted successfully'
        ], 201);
    }
}
