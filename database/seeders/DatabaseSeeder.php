<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder {
    // Users table
    public function run_users() {
        DB::table('users')->insert([
            'login' => 'admin',
            'password' => Hash::make('password'),
            'full_name' => 'Admin Admin',
            'email' => 'admmin@gmail.com',
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'login' => 'Savolus',
            'password' => Hash::make('password'),
            'full_name' => 'Mykola Dorohyi',
            'email' => 'savolus@gmail.com',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'login' => 'Marimarko',
            'password' => Hash::make('password'),
            'full_name' => 'Mari Mara',
            'email' => 'marimarko@gmail.com',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'login' => 'vchkhr',
            'password' => Hash::make('password'),
            'full_name' => 'Viacheslav Kharchenko',
            'email' => 'vchkhr@gmail.com',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('users')->insert([
            'login' => 'Lizbethzepesch',
            'password' => Hash::make('password'),
            'full_name' => 'Yelyzaveta Kliuieva',
            'email' => 'lizbethzepesch@gmail.com',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Posts table
    public function run_posts() {
        DB::table('posts')->insert([
            'title' => 'Why whe should stop using HTML v4',
            'content' => 'This specification defines the HyperText Markup Language (HTML), the publishing language of the World Wide Web. This specification defines HTML 4.01, which is a subversion of HTML 4. In addition to the text, multimedia, and hyperlink features of the previous versions of HTML (HTML 3.2 [HTML32] and HTML 2.0 [RFC1866]), HTML 4 supports more multimedia options, scripting languages, style sheets, better printing facilities, and documents that are more accessible to users with disabilities. HTML 4 also takes great strides towards the internationalization of documents, with the goal of making the Web truly World Wide.',
            'user_id' => 4,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('posts')->insert([
            'title' => 'CSS is awesome',
            'content' => 'I so much like CSS. I\'m totally recommend you use it',
            'user_id' => 5,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('posts')->insert([
            'title' => 'Node.js vs PHP',
            'content' => 'In the online advancement world, Node.js and PHP are the most well-known programming languages being used. Although both of these languages are able to manage the applications of any sort of complexity, they are being built around the different concepts & architectures. If you are an app owner or looking to develop a website, you might be wanting to choose between these two environments, therefore, you must know about the major differences, advantages, and limitations of the two languages.',
            'user_id' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('posts')->insert([
            'title' => 'JavaScript everywhere',
            'content' => 'JavaScript is the little scripting language that could. Once used chiefly to add interactivity to web browser windows, JavaScript is now a primary building block of powerful and robust applications. In this practical book, new and experienced JavaScript developers will learn how to use this language to create APIs as well as web, mobile, and desktop applications.',
            'user_id' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('posts')->insert([
            'title' => 'HTML project folder structure',
            'content' => 'I have come across a variety of projects, right from small projects with a maximum of 3 pages to big projects having over 20–25 pages filled with heavy animations and interactions.',
            'user_id' => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Categories table
    public function run_categories() {
        DB::table('categories')->insert([
            'title' => 'html',
            'description' => 'The HyperText Markup Language, or HTML is the standard markup language for documents designed to be displayed in a web browser.'
        ]);
        DB::table('categories')->insert([
            'title' => 'css',
            'description' => 'Cascading Style Sheets (CSS) is a style sheet language used for describing the presentation of a document written in a markup language such as HTML.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('categories')->insert([
            'title' => 'javascript',
            'description' => 'JavaScript often abbreviated as JS, is a programming language that conforms to the ECMAScript specification.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('categories')->insert([
            'title' => 'node.js',
            'description' => 'Node.js is an open-source, cross-platform, back-end JavaScript runtime environment that runs on the V8 engine and executes JavaScript code outside a web browser.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('categories')->insert([
            'title' => 'php',
            'description' => 'PHP is a general-purpose scripting language especially suited to web development.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Categories and Posts table
    public function run_categories_posts() {
        DB::table('category_post')->insert([
            'post_id' => 1,
            'category_id' => 1
        ]);
        DB::table('category_post')->insert([
            'post_id' => 2,
            'category_id' => 2
        ]);
        DB::table('category_post')->insert([
            'post_id' => 3,
            'category_id' => 4
        ]);
        DB::table('category_post')->insert([
            'post_id' => 3,
            'category_id' => 5
        ]);
        DB::table('category_post')->insert([
            'post_id' => 4,
            'category_id' => 3
        ]);
        DB::table('category_post')->insert([
            'post_id' => 4,
            'category_id' => 4
        ]);
        DB::table('category_post')->insert([
            'post_id' => 5,
            'category_id' => 1
        ]);
    }

    // Comments table
    public function run_comments() {
        DB::table('comments')->insert([
            'content' => 'Absolutely agree with you!',
            'user_id' => 5,
            'post_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('comments')->insert([
            'content' => 'PHP is dead!',
            'user_id' => 3,
            'post_id' => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('comments')->insert([
            'content' => 'Don\'t use PHP for API',
            'user_id' => 4,
            'post_id' => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('comments')->insert([
            'content' => 'Dont\'t forget to create folders for CSS',
            'user_id' => 5,
            'post_id' => 5,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('comments')->insert([
            'content' => 'What about Python?!',
            'user_id' => 5,
            'post_id' => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Likes table
    public function run_likes() {
        DB::table('likes')->insert([
            'user_id' => 2,
            'post_id' => 3
        ]);
        DB::table('likes')->insert([
            'user_id' => 2,
            'comment_id' => 2
        ]);
        DB::table('likes')->insert([
            'user_id' => 2,
            'comment_id' => 3
        ]);
        DB::table('likes')->insert([
            'user_id' => 3,
            'comment_id' => 5
        ]);
        DB::table('likes')->insert([
            'user_id' => 5,
            'post_id' => 1
        ]);
    }

    public function run() {
        $this->run_users();
        $this->run_posts();
        $this->run_categories();
        $this->run_categories_posts();
        $this->run_comments();
        $this->run_likes();
    }
}
