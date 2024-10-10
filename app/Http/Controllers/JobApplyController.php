<?php

namespace App\Http\Controllers;

use App\Models\JobApply;
use App\Models\User;
use App\Models\Job; // Import Job model if you want to link jobs to applications
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobApplyController extends Controller
{
    // List all job applications (Read)
    public function index()
    {
        $jobApplies = JobApply::with('users')->get();  // Fetch all job applications with related users
        return view('job_applies.index', compact('jobApplies'));  // Return a view with the list of applications
    }

    // Show the form to create a new job application (Create)
    public function create()
    {
        $users = User::all();  // Retrieve all users for selecting candidates
        $jobs = Job::all();    // Retrieve all jobs for the application
        return view('job_applies.create', compact('users', 'jobs'));  // Show the form for creating an application
    }

    // Store a newly created job application in the database (Create)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'applied_job' => 'required|exists:jobs,id',
            'candidate_id' => 'required|exists:users,id',
            'is_accepted' => 'nullable|boolean',
            'uploaded_cv' => 'required|file|mimes:pdf|max:2048', // Validate uploaded CV
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Store the uploaded CV
        $cvPath = $request->file('uploaded_cv')->store('cvs', 'public');

        // Create a new job application
        JobApply::create([
            'applied_job' => $request->applied_job,
            'candidate_id' => $request->candidate_id,
            'is_accepted' => $request->is_accepted,
            'uploaded_cv' => $cvPath,
        ]);

        return redirect()->route('job_applies.index')->with('success', 'Job application created successfully.');
    }

    // Show a specific job application (Read)
    public function show($id)
    {
        $jobApply = JobApply::with('users')->findOrFail($id);  // Fetch the job application with the user relationship
        return view('job_applies.show', compact('jobApply'));  // Show application details
    }

    // Show the form for editing a job application (Update)
    public function edit($id)
    {
        $jobApply = JobApply::findOrFail($id);
        $users = User::all();  // Retrieve all users for selecting candidates
        $jobs = Job::all();    // Retrieve all jobs for the application
        return view('job_applies.edit', compact('jobApply', 'users', 'jobs'));  // Show the edit form
    }

    // Update the job application in the database (Update)
    public function update(Request $request, $id)
    {
        $jobApply = JobApply::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'applied_job' => 'required|exists:jobs,id',
            'candidate_id' => 'required|exists:users,id',
            'is_accepted' => 'nullable|boolean',
            'uploaded_cv' => 'nullable|file|mimes:pdf|max:2048', // Validate uploaded CV if exists
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update the uploaded CV if a new one is provided
        $cvPath = $jobApply->uploaded_cv; // Keep the old CV path
        if ($request->hasFile('uploaded_cv')) {
            $cvPath = $request->file('uploaded_cv')->store('cvs', 'public'); // Store the new CV
        }

        // Update job application details
        $jobApply->update([
            'applied_job' => $request->applied_job,
            'candidate_id' => $request->candidate_id,
            'is_accepted' => $request->is_accepted,
            'uploaded_cv' => $cvPath,
        ]);

        return redirect()->route('job_applies.index')->with('success', 'Job application updated successfully.');
    }

    // Delete a job application (Delete)
    public function destroy($id)
    {
        $jobApply = JobApply::findOrFail($id);
        $jobApply->delete();  // Delete the job application

        return redirect()->route('job_applies.index')->with('success', 'Job application deleted successfully.');
    }
}
