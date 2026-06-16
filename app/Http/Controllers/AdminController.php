<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Bid;
use App\Models\User;
use App\Models\AuditLog;

class AdminController extends Controller
{
    public function index(Request $request){
        $totalProjects = Project::count();
        $totalBids = Bid::count();
        $totalUsers = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();

        $projects = Project::query();
        $projects->search($request->search);

        $projects = $projects->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.index', compact('projects','totalProjects','totalBids', 'totalUsers'));
    }

    public function auditLogs(Request $request){
       $auditLogs = AuditLog::with('user')
        ->search($request->search)
        ->latest()
        ->paginate(15)
        ->withQueryString();
        
        return view('admin.audit-logs', compact('auditLogs'));
    }

    
}
