<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use App\Models\CommentLike;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentLikeController extends Controller
{
    // Like a comment (Create)
    public function likeComment(Request $request, $commentId)
    {
        $validator = Validator::make($request->all(), [
            'is_liked' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if the like already exists
        $existingLike = CommentLike::where('comment_id', $commentId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingLike) {
            // If the user is unliking the comment
            if (!$request->is_liked) {
                $existingLike->delete();
                return redirect()->back()->with('success', 'Comment unliked successfully.');
            }

            // If the user is liking the comment again
            $existingLike->update(['is_liked' => true]);
            return redirect()->back()->with('success', 'Comment liked successfully.');
        }

        // Create a new like if it doesn't exist
        CommentLike::create([
            'comment_id' => $commentId,
            'user_id' => Auth::id(),
            'is_liked' => true,
        ]);

        return redirect()->back()->with('success', 'Comment liked successfully.');
    }

    // Get likes for a specific comment (Read)
    public function getCommentLikes($commentId)
    {
        $likes = CommentLike::where('comment_id', $commentId)->get();
        return response()->json($likes);
    }
}
