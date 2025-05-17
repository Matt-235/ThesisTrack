<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input
        $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'identifier.required' => 'NIM/NIP atau Email harus diisi.',
            'password.required' => 'Kata sandi harus diisi.',
        ]);

        $identifier = $request->input('identifier');
        $password = $request->input('password');

        \Log::info('Login attempt', ['identifier' => $identifier]);

        // Cek apakah input adalah email
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $identifier)->first();
            \Log::info('Email check', ['user' => $user ? $user->toArray() : null]);
            if ($user && Hash::check($password, $user->password)) {
                Auth::login($user, $request->filled('remember'));
                $request->session()->regenerate();
                \Log::info('Login success', ['user_id' => $user->id, 'role' => $user->role]);
                $role = Auth::user()->role;
                if ($role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($role === 'dosen') {
                    return redirect()->route('dosen.dashboard');
                } elseif ($role === 'mahasiswa') {
                    return redirect()->route('mahasiswa.dashboard');
                }
                return redirect()->route('home');
            }
        } else {
            // Cek apakah input adalah NIM (tabel mahasiswa)
            $mahasiswa = Mahasiswa::where('nim', $identifier)->first();
            \Log::info('NIM check', ['mahasiswa' => $mahasiswa ? $mahasiswa->toArray() : null]);
            if ($mahasiswa) {
                $user = User::find($mahasiswa->user_id);
                \Log::info('User from NIM', ['user' => $user ? $user->toArray() : null]);
                if ($user && Hash::check($password, $user->password)) {
                    Auth::login($user, $request->filled('remember'));
                    $request->session()->regenerate();
                    \Log::info('Login success', ['user_id' => $user->id, 'role' => $user->role]);
                    $role = Auth::user()->role;
                    if ($role === 'admin') {
                        return redirect()->route('admin.dashboard');
                    } elseif ($role === 'dosen') {
                        return redirect()->route('dosen.dashboard');
                    } elseif ($role === 'mahasiswa') {
                        return redirect()->route('mahasiswa.dashboard');
                    }
                    return redirect()->route('home');
                }
            }

            // Cek apakah input adalah NIP (tabel dosen)
            $dosen = Dosen::where('nip', $identifier)->first();
            \Log::info('NIP check', ['dosen' => $dosen ? $dosen->toArray() : null]);
            if ($dosen) {
                $user = User::find($dosen->user_id);
                \Log::info('User from NIP', ['user' => $user ? $user->toArray() : null]);
                if ($user && Hash::check($password, $user->password)) {
                    Auth::login($user, $request->filled('remember'));
                    $request->session()->regenerate();
                    \Log::info('Login success', ['user_id' => $user->id, 'role' => $user->role]);
                    $role = Auth::user()->role;
                    if ($role === 'admin') {
                        return redirect()->route('admin.dashboard');
                    } elseif ($role === 'dosen') {
                        return redirect()->route('dosen.dashboard');
                    } elseif ($role === 'mahasiswa') {
                        return redirect()->route('mahasiswa.dashboard');
                    }
                    return redirect()->route('home');
                }
            }
        }

        \Log::warning('Login failed', ['identifier' => $identifier]);
        return back()->withErrors([
            'identifier' => __('Kredensial yang diberikan tidak cocok dengan data kami.'),
        ])->with('error', 'Kredensial tidak valid.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}