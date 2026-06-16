<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\AuditLogger;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        try {
            $request->authenticate();
        } catch (\Exception $e) {
            AuditLogger::log('auth.login_failed');
            throw $e;
        }

        $request->authenticate();

        $request->session()->regenerate();

        AuditLogger::log('auth.login');

        $user = Auth::user()->name;

        if(Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.index')->with('success','Welcome, '. 'Admin');
        }
        
        
        return redirect()->intended(route('project.index', absolute: false))->with('success','Welcome, '. $user);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
