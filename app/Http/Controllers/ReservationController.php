<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // Public reservation form
    public function create()
    {
        return view('reservation.create');
    }

    // Store public reservation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'reservation_time' => ['required'],
            'guests' => ['required', 'integer', 'min:1', 'max:50'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['status'] = 'pending';

        Reservation::create($validated);

        return redirect()
            ->route('home')
            ->with('success', 'Your reservation has been submitted successfully!');
    }

    // Admin reservation list
    public function index()
    {
        $reservations = Reservation::latest()->paginate(10);

        $totalCount = Reservation::count();

        $pendingCount = Reservation::where('status', 'pending')->count();

        $confirmedCount = Reservation::where('status', 'confirmed')->count();

        $cancelledCount = Reservation::where('status', 'cancelled')->count();

        return view('admin.reservations.index', compact(
            'reservations',
            'totalCount',
            'pendingCount',
            'confirmedCount',
            'cancelledCount'
        ));
    }

    // Show reservation details
    public function show(Reservation $reservation)
    {
        return view('admin.reservations.show', compact('reservation'));
    }

    // Edit reservation form
    public function edit(Reservation $reservation)
    {
        return view('admin.reservations.edit', compact('reservation'));
    }

    // Update reservation
    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'reservation_date' => ['required', 'date'],
            'reservation_time' => ['required'],
            'guests' => ['required', 'integer', 'min:1', 'max:50'],
            'message' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:pending,confirmed,cancelled'],
        ]);

        $reservation->update($validated);

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Reservation updated successfully!');
    }

    // Update reservation status
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled'],
        ]);

        $reservation->update($validated);

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Reservation status updated successfully!');
    }

    // Delete reservation
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Reservation deleted successfully!');
    }
}
