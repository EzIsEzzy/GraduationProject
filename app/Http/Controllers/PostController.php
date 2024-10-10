<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // List all posts (Read)
    public function index()
    {
        $posts = Post::with('users', 'likes', 'comments')->get();  // Fetch all posts with related user, likes, and comments
        return view('posts.index', compact('posts'));  // Returning a view with the list of posts
    }

    // Show the form to create a new post (Create)
    public function create()
    {
        $users = User::all();  // Retrieve all users for assigning posts
        return view('posts.create', compact('users'));  // Show the form for creating a post
    }

    // Store a newly created post in the database (Create)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'publisher_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle image upload if it exists
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Create a new post
        Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imagePath ?? null,  // Save image path if uploaded
            'publisher_id' => $request->publisher_id,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    // Show a specific post (Read)
    public function show($id)
    {
        $post = Post::with('users', 'likes', 'comments')->findOrFail($id);  // Fetch the post with relationships
        return view('posts.show', compact('post'));  // Show post details
    }

    // Show the form for editing a post (Update)
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $users = User::all();  // Retrieve all users for assigning posts
        return view('posts.edit', compact('post', 'users'));  // Show the edit form
    }

    // Update the post in the database (Update)
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'publisher_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle image upload if it exists
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Update post details
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imagePath ?? $post->image,  // Only update if a new image is uploaded
            'publisher_id' => $request->publisher_id,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    // Delete a post (Delete)
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();  // Delete the post

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
