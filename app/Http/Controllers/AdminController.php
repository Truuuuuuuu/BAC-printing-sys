<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Bid;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;

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


    public function users(Request $request){
        $users = User::with('roles')
        ->search($request->search)
        ->latest()
        ->paginate(15)
        ->withQueryString();
        
        return view('admin.all-users', compact('users'));
    }

    public function resetPassword(User $user, Request $request){
       $request->validate([
        'password' => ['required', 'string', 'min:8'],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'User password has been reset successfully.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
    
}
