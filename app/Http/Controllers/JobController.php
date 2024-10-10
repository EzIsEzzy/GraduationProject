<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    // List all jobs (Read)
    public function index()
    {
        $jobs = Job::with('users')->get();  // Fetch all jobs with the related users
        return view('jobs.index', compact('jobs'));  // Return a view with the list of jobs
    }

    // Show the form to create a new job (Create)
    public function create()
    {
        $users = User::all();  // Retrieve all users for assigning jobs
        return view('jobs.create', compact('users'));  // Show the form for creating a job
    }

    // Store a newly created job in the database (Create)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_content' => 'required|string|max:255',
            'publisher_id' => 'required|exists:users,id',
            'is_company' => 'required|boolean',
            'company_pfp' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create a new job
        Job::create([
            'job_content' => $request->job_content,
            'publisher_id' => $request->publisher_id,
            'is_company' => $request->is_company,
            'company_pfp' => $request->company_pfp,
            'company_name' => $request->company_name,
        ]);

        return redirect()->route('jobs.index')->with('success', 'Job created successfully.');
    }

    // Show a specific job (Read)
    public function show($id)
    {
        $job = Job::with('users')->findOrFail($id);  // Fetch the job with the user relationship
        return view('jobs.show', compact('job'));  // Show job details
    }

    // Show the form for editing a job (Update)
    public function edit($id)
    {
        $job = Job::findOrFail($id);
        $users = User::all();  // Retrieve all users for assigning jobs
        return view('jobs.edit', compact('job', 'users'));  // Show the edit form
    }

    // Update the job in the database (Update)
    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'job_content' => 'required|string|max:255',
            'publisher_id' => 'required|exists:users,id',
            'is_company' => 'required|boolean',
            'company_pfp' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update job details
        $job->update([
            'job_content' => $request->job_content,
            'publisher_id' => $request->publisher_id,
            'is_company' => $request->is_company,
            'company_pfp' => $request->company_pfp,
            'company_name' => $request->company_name,
        ]);

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
    }

    // Delete a job (Delete)
    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();  // Delete the job

        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }
}
