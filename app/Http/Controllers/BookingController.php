<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Booking;
use App\Models\Pet;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function search(Request $request)
    {
        $type = $request->query('type', 'groomer'); // groomer, veterinarian, pet_sitter
        
        $providers = User::with(['services', 'reviewsAsProvider'])
            ->where('role', 'service_provider')
            ->where('provider_type', $type)
            ->where('is_verified', true)
            ->get();

        return view('owner.search_providers', compact('providers', 'type'));
    }

    public function showCreate(User $provider)
    {
        if (!$provider->isServiceProvider()) {
            abort(404);
        }

        $user = Auth::user();
        $pets = $user->pets;
        $addresses = $user->addresses;
        $services = $provider->services;

        // Get schedules to show operating times
        $schedules = $provider->schedules()->where('is_available', true)->get();

        return view('owner.create_booking', compact('provider', 'pets', 'addresses', 'services', 'schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'address_id' => 'required|exists:addresses,id',
            'notes' => 'nullable|string',
        ]);

        $provider = User::find($request->provider_id);
        $service = Service::find($request->service_id);

        // Check if provider works on this day of week
        $dayOfWeek = date('w', strtotime($request->booking_date));
        $schedule = $provider->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$schedule) {
            return back()->withErrors(['booking_date' => 'Penyedia layanan tidak aktif di hari tersebut.'])->withInput();
        }

        // Check if time is within schedule hours
        $timeStr = $request->start_time;
        if ($timeStr < date('H:i', strtotime($schedule->start_time)) || $timeStr > date('H:i', strtotime($schedule->end_time))) {
            return back()->withErrors(['start_time' => 'Jam pilihan di luar jam kerja penyedia layanan.'])->withInput();
        }

        // Check for double booking
        $conflict = Booking::where('provider_id', $provider->id)
            ->where('booking_date', $request->booking_date)
            ->where('start_time', $request->start_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['start_time' => 'Jam ini sudah di-booking oleh pelanggan lain.'])->withInput();
        }

        Booking::create([
            'pet_owner_id' => Auth::id(),
            'provider_id' => $provider->id,
            'pet_id' => $request->pet_id,
            'service_id' => $service->id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'total_price' => $service->price,
            'address_id' => $request->address_id,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Pemesanan berhasil diajukan! Menunggu konfirmasi penyedia.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|string|in:confirmed,completed,cancelled',
        ]);

        $user = Auth::user();

        // Security check
        if ($user->isServiceProvider()) {
            if ($booking->provider_id !== $user->id) {
                abort(403);
            }
        } elseif ($user->isPetOwner()) {
            if ($booking->pet_owner_id !== $user->id) {
                abort(403);
            }
            // Pet owner can only cancel
            if ($request->status !== 'cancelled') {
                abort(403);
            }
        } else {
            // Admin can do anything
            if (!$user->isAdmin()) {
                abort(403);
            }
        }

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diupdate menjadi: ' . __($request->status));
    }
}
