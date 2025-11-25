<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Colony;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColonyController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->is_super_admin) {
                abort(403, 'Access denied. Super admin only.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $colonies = Colony::with('plan')->paginate(15);
        return view('super-admin.colonies.index', compact('colonies'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('super-admin.colonies.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:colonies,code',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'pincode' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'plan_id' => 'nullable|exists:subscription_plans,id',
            'status' => 'required|in:active,suspended,expired,trial',
            'expires_at' => 'nullable|date',
        ]);

        $colony = Colony::create($validated);

        return redirect()->route('super-admin.colonies.index')
            ->with('success', 'Colony created successfully.');
    }

    public function show(Colony $colony)
    {
        $colony->load(['plan', 'users']);
        return view('super-admin.colonies.show', compact('colony'));
    }

    public function edit(Colony $colony)
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('super-admin.colonies.edit', compact('colony', 'plans'));
    }

    public function update(Request $request, Colony $colony)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:colonies,code,' . $colony->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'pincode' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'plan_id' => 'nullable|exists:subscription_plans,id',
            'status' => 'required|in:active,suspended,expired,trial',
            'expires_at' => 'nullable|date',
        ]);

        $colony->update($validated);

        return redirect()->route('super-admin.colonies.index')
            ->with('success', 'Colony updated successfully.');
    }

    public function destroy(Colony $colony)
    {
        $colony->delete();
        return redirect()->route('super-admin.colonies.index')
            ->with('success', 'Colony deleted successfully.');
    }

    public function suspend(Colony $colony)
    {
        $colony->update(['status' => 'suspended']);
        return redirect()->back()->with('success', 'Colony suspended.');
    }

    public function activate(Colony $colony)
    {
        $colony->update(['status' => 'active']);
        return redirect()->back()->with('success', 'Colony activated.');
    }

    public function impersonate(Colony $colony)
    {
        $user = auth()->user();
        $user->switchColony($colony->id);
        
        return redirect()->route('colony.dashboard')
            ->with('success', "Impersonating colony: {$colony->name}");
    }
}
