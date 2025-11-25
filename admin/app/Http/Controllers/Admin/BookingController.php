<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Amenity;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? Booking::with(['amenity', 'resident', 'unit', 'colony'])
            : Booking::with(['amenity', 'resident', 'unit'])->where('colony_id', $user->current_colony_id);

        if ($request->filled('amenity_id')) {
            $query->where('amenity_id', $request->amenity_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('booking_date')) {
            $query->whereDate('booking_date', $request->booking_date);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->paginate(15);
        
        $amenityQuery = Amenity::where('is_active', true);
        if (!$user->is_super_admin) {
            $amenityQuery->where('colony_id', $user->current_colony_id);
        }
        $amenities = $amenityQuery->get();

        return view('admin.bookings.index', compact('bookings', 'amenities'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['amenity', 'resident', 'unit', 'slot', 'approvedBy']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        $booking->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Booking approved successfully.');
    }

    public function reject(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->back()->with('success', 'Booking rejected.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string',
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Booking cancelled.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
}
