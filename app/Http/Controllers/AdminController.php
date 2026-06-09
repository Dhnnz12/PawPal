<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function usersIndex(Request $request)
    {
        $roleFilter = $request->query('role');
        $typeFilter = $request->query('type');

        $query = User::query();

        if ($roleFilter === 'pet_owner') {
            $query->where('role', 'pet_owner');
        } elseif ($roleFilter === 'admin') {
            $query->where('role', 'admin');
        } elseif ($roleFilter === 'service_provider') {
            $query->where('role', 'service_provider');
            if ($typeFilter) {
                $query->where('provider_type', $typeFilter);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users', 'roleFilter', 'typeFilter'));
    }

    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,pet_owner,service_provider',
            'provider_type' => 'required_if:role,service_provider|nullable|string|in:groomer,veterinarian,pet_sitter,seller',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'certification' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'is_verified' => 'required|boolean',
            'is_active' => 'required|boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $certPath = null;
        if ($request->hasFile('certification')) {
            $certPath = $request->file('certification')->store('certifications', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'provider_type' => $request->role === 'service_provider' ? $request->provider_type : null,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'avatar' => $avatarPath,
            'certification' => $certPath,
            'is_verified' => $request->is_verified,
            'is_active' => $request->is_active,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:admin,pet_owner,service_provider',
            'provider_type' => 'required_if:role,service_provider|nullable|string|in:groomer,veterinarian,pet_sitter,seller',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'certification' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'is_verified' => 'required|boolean',
            'is_active' => 'required|boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'provider_type' => $request->role === 'service_provider' ? $request->provider_type : null,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'is_verified' => $request->is_verified,
            'is_active' => $request->is_active,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('certification')) {
            if ($user->certification) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->certification);
            }
            $data['certification'] = $request->file('certification')->store('certifications', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Informasi pengguna berhasil diperbarui!');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === \Illuminate\Support\Facades\Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        if ($user->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }
        if ($user->certification) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->certification);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === \Illuminate\Support\Facades\Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri!');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$statusText}!");
    }

    public function verifyProvider(Request $request, User $provider)
    {
        if (!$provider->isServiceProvider()) {
            abort(400, 'User is not a service provider.');
        }

        $request->validate([
            'action' => 'required|string|in:approve,reject',
        ]);

        if ($request->action === 'approve') {
            $provider->update(['is_verified' => true]);
            return back()->with('success', "Penyedia layanan {$provider->name} berhasil diverifikasi!");
        } else {
            // Delete certificate path or handle reject
            $provider->update([
                'is_verified' => false,
                'certification' => null, // clear uploaded document
            ]);
            return back()->with('success', "Penyedia layanan {$provider->name} ditolak. Dokumen sertifikasi telah dibersihkan.");
        }
    }

    public function approveTransaction(\App\Models\Order $order)
    {
        $order->update(['status' => 'paid']);
        return back()->with('success', "Order #{$order->id} berhasil diverifikasi dan ditandai Sudah Dibayar!");
    }

    public function rejectTransaction(\App\Models\Order $order)
    {
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }
        $order->update(['status' => 'cancelled']);
        return back()->with('success', "Order #{$order->id} ditolak dan stok dikembalikan.");
    }
}
