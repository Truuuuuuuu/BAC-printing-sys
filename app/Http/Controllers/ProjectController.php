<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Bid;
use App\Models\CityMunicipality;
class ProjectController extends Controller
{
    

    public function index(Request $request)
    {
        $projects = Project::query();

        $projects->search($request->search);

        $projects = $projects->latest()
            ->paginate(10)
            ->withQueryString();

        return view('project.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'project_title' => 'required',
            'approved_budget' => ['required', 'numeric', 'min:1'],
            'bidding_date' => ['required', 'date', 'after:today'],
            'status' => ['required', 'in:awarded,failed'],
        ],
        [
            'project_title.required' => 'Please enter a project title.',
            'approved_budget.required' => 'Please enter an amount.',
            'approved_budget.min'=> 'Please enter a valid amount',
            'approved_budget.numeric' => 'Amount must be a valid number.',
            'bidding_date.after' => 'The bidding date must be in the future.',
            'status.in' => 'Please select a valid status.',
        ]);

        Project::create([
            'project_title' => $attributes['project_title'],
            'approved_budget' => $attributes['approved_budget'],
            'bidding_date' => $attributes['bidding_date'],
            'status' => $attributes['status'],
        ]);

        return redirect()->route('project.index')->with('success','Project created successfully.');
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'edit-project_title' => ['required', 'string'],
            'edit-approved_budget'        => ['required', 'numeric'],
            'edit-bidding_date'  => ['required', 'date', 'after:today'],
            'edit-status'        => ['required', 'in:awarded,failed'],
        ],
        [
            'edit-project_title.required' => 'Please enter a project title.',
            'edit-approved_budget.required' => 'Please enter an amount.',
            'edit-approved_budget.numeric' => 'Amount must be a valid number.',
            'edit-bidding_date.after' => 'The bidding date must be in the future.',
            'edit-status.in' => 'Please select a valid status.',
        ]);

        if (
            $project->project_title === $request->input('edit-project_title') &&
            $project->approved_budget == $request->input('edit-approved_budget') &&
            $project->bidding_date->format('Y-m-d') === $request->input('edit-bidding_date') &&
            $project->status === $request->input('edit-status')
        ) {
            return redirect()->back();
        }

        $project->update([
            'project_title' => $request->input('edit-project_title'),
            'approved_budget'        => $request->input('edit-approved_budget'),
            'bidding_date'  => $request->input('edit-bidding_date'),
            'status'        => $request->input('edit-status'),
        ]);

        return redirect()->back()->with('clear_storage',true)->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->back()->with('clear_storage', true)->with('success', 'Project deleted successfully.');
    }

    
    public function show(Project $project)
    {
        $bids = $project->bids()->orderBy('bid_amount', 'asc')->paginate(10);
        $cities = CityMunicipality::orderBy('name')->get();
        return view('project.show', compact('project', 'bids', 'cities'));
    }

    public function award(Bid $bid)
    {

        abort_if(is_null($bid->project), 404);

        $bid->project->update([
            'bid_id' => $bid->id,
        ]);

        return redirect()->back()->with('clear_storage', true)->with('success', 'Awarded successfully.');
    }
}
