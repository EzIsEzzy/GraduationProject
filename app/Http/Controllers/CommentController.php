<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Configuration\Middleware;

class CommentController extends Controller
{
    // Create a new comment
    public function store(Request $request, $postId)
    {
        $validator = Validator::make($request->all(), [
            'comment_line' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $comment = Comment::create([
            'post_id' => $postId,
            'commenter_id' =>  Auth::id(),
            'comment_line' => $request->comment_line,
            'is_comment' => true, // Assuming this is a flag to indicate it's a valid comment
        ]);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    // Get all comments for a specific post (Read)
    public function index($postId)
    {
        $comments = Comment::where('post_id', $postId)->with('users')->get();
        return response()->json($comments);
    }

    // Update a specific comment
    public function update(Request $request, $commentId)
    {
        $validator = Validator::make($request->all(), [
            'comment_line' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $comment = Comment::findOrFail($commentId);
        // Check if the user is the owner of the comment
        if ($comment->commenter_id !==  Auth::id()) {
            return redirect()->back()->withErrors(['Unauthorized action.']);
        }

        $comment->update(['comment_line' => $request->comment_line]);

        return redirect()->back()->with('success', 'Comment updated successfully.');
    }

    // Delete a specific comment
    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        // Check if the user is the owner of the comment
        if ($comment->commenter_id !==  Auth::id()) {
            return redirect()->back()->withErrors(['Unauthorized action.']);
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
