<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:pet_owner,service_provider',
            'provider_type' => 'required_if:role,service_provider|nullable|string|in:groomer,veterinarian,pet_sitter,seller',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'certification' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
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

        // Veterinarians and Sellers require admin verification, so is_verified = false.
        // Groomers and Pet Sitters don't necessarily require it, or we can make them all verified for simplicity, 
        // but let's make Veterinarians and Sellers start as false (is_verified = false) and require Admin approval.
        $needsVerification = in_array($request->provider_type, ['veterinarian', 'seller']);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'provider_type' => $request->role === 'service_provider' ? $request->provider_type : null,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'avatar' => $avatarPath,
            'certification' => $certPath,
            'is_verified' => !$needsVerification,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil!');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda dinonaktifkan oleh administrator.',
                ])->onlyInput('email');
            }
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
