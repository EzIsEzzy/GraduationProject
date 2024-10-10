<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    // List all likes (Read)
    public function index()
    {
        $likes = Like::with('users', 'posts')->get();  // Fetch all likes with related users and posts
        return view('likes.index', compact('likes'));  // Returning a view with the list of likes
    }

    // Show the form to create a new like (Create)
    public function create()
    {
        $users = User::all();  // Retrieve all users for assigning likes
        $posts = Post::all();  // Retrieve all posts for assigning likes
        return view('likes.create', compact('users', 'posts'));  // Show the form for creating a like
    }

    // Store a newly created like in the database (Create)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'is_like' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create a new like
        Like::create([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'is_like' => $request->is_like,
        ]);

        return redirect()->route('likes.index')->with('success', 'Like created successfully.');
    }

    // Show a specific like (Read)
    public function show($id)
    {
        $like = Like::with('users', 'posts')->findOrFail($id);  // Fetch the like with relationships
        return view('likes.show', compact('like'));  // Show like details
    }

    // Show the form for editing a like (Update)
    public function edit($id)
    {
        $like = Like::findOrFail($id);
        $users = User::all();  // Retrieve all users for assigning likes
        $posts = Post::all();  // Retrieve all posts for assigning likes
        return view('likes.edit', compact('like', 'users', 'posts'));  // Show the edit form
    }

    // Update the like in the database (Update)
    public function update(Request $request, $id)
    {
        $like = Like::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'is_like' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update like details
        $like->update([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'is_like' => $request->is_like,
        ]);

        return redirect()->route('likes.index')->with('success', 'Like updated successfully.');
    }

    // Delete a like (Delete)
    public function destroy($id)
    {
        $like = Like::findOrFail($id);
        $like->delete();  // Delete the like

        return redirect()->route('likes.index')->with('success', 'Like deleted successfully.');
    }
}
