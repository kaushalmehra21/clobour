<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Colony;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_super_admin', false)
            ->with('colonies')
            ->paginate(15);
        return view('super-admin.users.index', compact('users'));
    }

    public function create()
    {
        $colonies = Colony::all();
        return view('super-admin.users.create', compact('colonies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'colony_id' => 'required|exists:colonies,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_super_admin' => false,
            'current_colony_id' => $validated['colony_id'],
        ]);

        // Assign user to colony
        $user->colonies()->attach($validated['colony_id'], [
            'is_primary' => true,
        ]);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $colonies = Colony::all();
        return view('super-admin.users.edit', compact('user', 'colonies'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'colony_id' => 'required|exists:colonies,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'current_colony_id' => $validated['colony_id'],
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Update colony assignment
        $user->colonies()->sync([$validated['colony_id'] => ['is_primary' => true]]);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is_super_admin) {
            return redirect()->back()->with('error', 'Cannot delete super admin.');
        }

        $user->delete();
        return redirect()->route('super-admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function impersonate(User $user)
    {
        $currentUser = auth()->user();

        if (! $currentUser || ! $currentUser->is_super_admin) {
            abort(403, 'Only super admins may impersonate users.');
        }

        if ($user->is_super_admin) {
            return redirect()->back()->with('error', 'Cannot impersonate another super admin.');
        }

        session(['impersonator_id' => $currentUser->id]);
        Auth::login($user);
        session()->regenerate();

        return redirect()->route('colony.dashboard')
            ->with('success', "You are now impersonating {$user->name}. Click \"Return to Super Admin\" to switch back.");
    }
}
