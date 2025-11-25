<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Colony;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_colonies' => Colony::count(),
            'active_colonies' => Colony::where('status', 'active')->count(),
            'suspended_colonies' => Colony::where('status', 'suspended')->count(),
            'total_users' => User::where('is_super_admin', false)->count(),
        ];

        $recentColonies = Colony::latest()->take(5)->get();
        
        return view('super-admin.dashboard', compact('stats', 'recentColonies'));
    }

    public function analytics()
    {
        // Analytics data
        return view('super-admin.analytics');
    }

    public function reports()
    {
        // Reports
        return view('super-admin.reports');
    }
}
