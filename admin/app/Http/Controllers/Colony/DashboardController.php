<?php

namespace App\Http\Controllers\Colony;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $colony = $user->currentColony;

        if (!$colony) {
            abort(403, 'No colony context set.');
        }

        $stats = [
            'total_units' => $colony->units()->count(),
            'total_residents' => $colony->residents()->count(),
            'pending_bills' => $colony->bills()->where('status', 'pending')->count(),
            'open_complaints' => $colony->complaints()->where('status', 'open')->count(),
        ];

        return view('colony.dashboard', compact('stats', 'colony'));
    }
}
