<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->is_super_admin) {
                return redirect()->route('super-admin.dashboard');
            } else {
                return redirect()->route('colony.dashboard');
            }
        }

        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Set colony context if not super admin
            if (!$user->is_super_admin && !$user->current_colony_id) {
                $colony = $user->colonies()->wherePivot('is_primary', true)->first()
                    ?? $user->colonies()->first();
                if ($colony) {
                    $user->current_colony_id = $colony->id;
                    $user->save();
                }
            }
            
            if ($user->is_super_admin) {
                return redirect()->intended(route('super-admin.dashboard'));
            } else {
                return redirect()->intended(route('colony.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->forget('impersonator_id');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function stopImpersonation(Request $request)
    {
        $impersonatorId = $request->session()->pull('impersonator_id');

        if (! $impersonatorId) {
            return redirect()->route('login');
        }

        $originalUser = User::find($impersonatorId);

        if ($originalUser) {
            Auth::login($originalUser);
            $request->session()->regenerate();
            return redirect()->route('super-admin.dashboard')
                ->with('success', 'You have returned to your super admin session.');
        }

        return redirect()->route('login')->with('error', 'Original session could not be restored.');
    }
}
