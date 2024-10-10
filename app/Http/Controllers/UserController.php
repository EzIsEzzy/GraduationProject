<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    // List all users (Read)
    public function index()
    {
        $users = User::all();  // Fetch all users
        return view('users.index', compact('users'));  // Returning a view with the list of users
    }

    // Show the form to create a new user (Create)
    public function create()
    {
        return view('users.create');  // Show the form for creating a new user
    }

    // Store a newly created user in the database (Create)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'username' => 'required|string|max:255|unique:users',
            'gender' => 'required|in:male,female',
            'birthdate' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create a new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => $request->username,
            'picture' => $request->picture,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show a specific user (Read)
    public function show($id)
    {
        $user = User::findOrFail($id);  // Fetch a single user
        return view('users.show', compact('user'));  // Show user details
    }

    // Show the form for editing a user (Update)
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));  // Show the edit form
    }

    // Update the user in the database (Update)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'gender' => 'required|in:male,female',
            'birthdate' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update user details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'picture' => $request->picture,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'password' => $request->password ? Hash::make($request->password) : $user->password,  // Only hash password if it’s updated
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Delete a user (Delete)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();  // Delete the user

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
