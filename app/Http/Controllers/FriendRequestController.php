<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FriendRequestController extends Controller
{
    // List all friend requests sent by the authenticated user (Read)
    public function index()
    {
        $friendRequests = FriendRequest::with('receiver')
            ->where('sender_id', Auth::id())
            ->get();

        return view('friend_requests.index', compact('friendRequests'));
    }

    // Send a friend request (Create)
    public function sendRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if the sender is trying to send a request to themselves
        if ($request->receiver_id == Auth::id()) {
            return redirect()->back()->with('error', 'You cannot send a friend request to yourself.');
        }

        // Check if a request already exists
        if (FriendRequest::where('sender_id', Auth::id())->where('receiver_id', $request->receiver_id)->exists()) {
            return redirect()->back()->with('error', 'Friend request already sent.');
        }

        // Create a new friend request
        FriendRequest::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'is_accepted' => false,
        ]);

        return redirect()->back()->with('success', 'Friend request sent successfully.');
    }

    // Accept a friend request (Update)
    public function acceptRequest($id)
    {
        $friendRequest = FriendRequest::findOrFail($id);

        if ($friendRequest->receiver_id != Auth::id()) {
            return redirect()->back()->with('error', 'You cannot accept this friend request.');
        }

        // Update the request status
        $friendRequest->update([
            'is_accepted' => true,
            'accepted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Friend request accepted.');
    }

    // Decline a friend request (Delete)
    public function declineRequest($id)
    {
        $friendRequest = FriendRequest::findOrFail($id);

        if ($friendRequest->receiver_id != Auth::id()) {
            return redirect()->back()->with('error', 'You cannot decline this friend request.');
        }

        $friendRequest->delete();  // Delete the friend request

        return redirect()->back()->with('success', 'Friend request declined.');
    }
}
