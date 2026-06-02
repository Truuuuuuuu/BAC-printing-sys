<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Project;
class ProjectController extends Controller
{
    
    public function index()
    {
        $projects = Project::all();
        return view('project.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'project_title' => 'required',
            'amount' => ['required', 'numeric', 'min:0'],
            'bidding_date' => ['required', 'date', 'after:today'],
            'status' => ['required', 'in:awarded,failed'],
        ],
        [
            'project_title.required' => 'Please enter a project title.',
            'amount.required' => 'Please enter an amount.',
            'amount.numeric' => 'Amount must be a valid number.',
            'bidding_date.after' => 'The bidding date must be in the future.',
            'status.in' => 'Please select a valid status.',
        ]);

        Project::create([
            'project_title' => $attributes['project_title'],
            'amount' => $attributes['amount'],
            'bidding_date' => $attributes['bidding_date'],
            'status' => $attributes['status'],
        ]);

        return redirect()->route('project.index')->with('success','Project created successfully.');
    }
}
