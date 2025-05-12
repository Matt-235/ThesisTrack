<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        Log::info('Accessing register page');
        return view('auth.register');
    }

    public function store(Request $request)
    {
        Log::info('Register attempt with data:', $request->all());

        // Validasi
        $rules = [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,dosen,mahasiswa'],
        ];

        // Validasi tambahan berdasarkan role
        if ($request->role === 'mahasiswa') {
            $rules['nim'] = ['required', 'string', 'max:20', 'unique:mahasiswa,nim'];
            $rules['angkatan'] = ['required', 'string', 'max:4']; // Misalnya, "2020"
        } elseif ($request->role === 'dosen') {
            $rules['nip'] = ['required', 'string', 'max:20', 'unique:dosen,nip'];
            $rules['bidang_keahlian'] = ['required', 'string', 'max:255'];
        }

        try {
            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()), ['request_data' => $request->all()]);
            throw $e; // Biarkan Laravel menangani redirect kembali ke form dengan error
        }

        try {
            // Buat pengguna
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            Log::info('User created: ' . $user->email . ', role: ' . $user->role . ', nama: ' . $user->nama);

            // Jika role mahasiswa, buat record di tabel mahasiswa
            if ($user->role === 'mahasiswa') {
                $mahasiswa = Mahasiswa::create([
                    'user_id' => $user->id,
                    'nim' => $request->nim,
                    'angkatan' => $request->angkatan,
                ]);
                Log::info('Mahasiswa record created: ' . $user->email . ', mahasiswa_id: ' . $mahasiswa->id . ', nim: ' . $mahasiswa->nim . ', angkatan: ' . $mahasiswa->angkatan);
            } elseif ($user->role === 'dosen') {
                $dosen = Dosen::create([
                    'user_id' => $user->id,
                    'nip' => $request->nip,
                    'bidang_keahlian' => $request->bidang_keahlian,
                ]);
                Log::info('Dosen record created: ' . $user->email . ', dosen_id: ' . $dosen->id . ', nip: ' . $dosen->nip . ', bidang_keahlian: ' . $dosen->bidang_keahlian);
            }

            event(new Registered($user));

            // Redirect ke login dengan pesan sukses
            Log::info('Registration successful for user: ' . $user->email . ', redirecting to login');
            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage(), ['exception' => $e, 'request_data' => $request->all()]);
            return redirect()->route('register')->with('error', 'Gagal mendaftar: ' . $e->getMessage());
        }
    }
}
