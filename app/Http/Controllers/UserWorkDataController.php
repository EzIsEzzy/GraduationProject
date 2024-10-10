<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\UserWorkData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserWorkDataController extends Controller
{
    // Create a new work data entry
    public function store(Request $request)
    {
        // Check if the user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login')->withErrors(['You must be logged in to add work data.']);
    }
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $workData = UserWorkData::create([
            'user_id' => Auth::id(),
            'job_title' => $request->job_title,
            'company_name' => $request->company_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Work data added successfully.');
    }

    // Get all work data for the authenticated user (Read)
    public function index()
    {
        $workData = UserWorkData::where('user_id', Auth::id())->get();
        return response()->json($workData);
    }

    // Update a specific work data entry
    public function update(Request $request, $workDataId)
    {
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $workData = UserWorkData::findOrFail($workDataId);
        // Check if the user is the owner of the work data
        if ($workData->user_id !==  Auth::id()) {
            return redirect()->back()->withErrors(['Unauthorized action.']);
        }

        $workData->update($request->only(['job_title', 'company_name', 'start_date', 'end_date', 'description']));

        return redirect()->back()->with('success', 'Work data updated successfully.');
    }

    // Delete a specific work data entry
    public function destroy($workDataId)
    {
        $workData = UserWorkData::findOrFail($workDataId);
        // Check if the user is the owner of the work data
        if ($workData->user_id !==  Auth::id()) {
            return redirect()->back()->withErrors(['Unauthorized action.']);
        }

        $workData->delete();

        return redirect()->back()->with('success', 'Work data deleted successfully.');
    }
}
