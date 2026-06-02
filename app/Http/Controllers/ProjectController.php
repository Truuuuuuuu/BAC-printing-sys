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
            'amount' => ['required', 'numeric', 'min:1'],
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

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'edit-project_title' => ['required', 'string'],
            'edit-amount'        => ['required', 'numeric'],
            'edit-bidding_date'  => ['required', 'date', 'after:today'],
            'edit-status'        => ['required', 'in:awarded,failed'],
        ],
        [
            'edit-project_title.required' => 'Please enter a project title.',
            'edit-amount.required' => 'Please enter an amount.',
            'edit-amount.numeric' => 'Amount must be a valid number.',
            'edit-bidding_date.after' => 'The bidding date must be in the future.',
            'edit-status.in' => 'Please select a valid status.',
        ]);

        if (
            $project->project_title === $request->input('edit-project_title') &&
            $project->amount == $request->input('edit-amount') &&
            $project->bidding_date->format('Y-m-d') === $request->input('edit-bidding_date') &&
            $project->status === $request->input('edit-status')
        ) {
            return redirect()->back();
        }

        $project->update([
            'project_title' => $request->input('edit-project_title'),
            'amount'        => $request->input('edit-amount'),
            'bidding_date'  => $request->input('edit-bidding_date'),
            'status'        => $request->input('edit-status'),
        ]);

        return redirect()->back()->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('project.index')->with('success', 'Project deleted successfully.');
    }
}
