<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // Mahasiswa
    public function mahasiswaIndex()
    {
        $mahasiswas = Mahasiswa::with('user')->paginate(10);
        $dosens = Dosen::with('user')->paginate(10);
        return view('admin.dashboard', compact('mahasiswas', 'dosens'));
    }

    public function mahasiswaStore(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'nim' => 'required|string|unique:mahasiswa,nim',
                'angkatan' => 'required|string|size:4',
            ]);

            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $validated['nim'],
                'angkatan' => $validated['angkatan'],
            ]);

            return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
        });
    }

    public function mahasiswaEdit(Mahasiswa $mahasiswa)
    {
        return response()->json([
            'id' => $mahasiswa->id,
            'nama' => $mahasiswa->user->nama,
            'email' => $mahasiswa->user->email,
            'nim' => $mahasiswa->nim,
            'angkatan' => $mahasiswa->angkatan,
        ]);
    }

    public function mahasiswaUpdate(Request $request, Mahasiswa $mahasiswa)
    {
        return DB::transaction(function () use ($request, $mahasiswa) {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $mahasiswa->user->id,
                'password' => 'nullable|string|min:8',
                'nim' => 'required|string|unique:mahasiswa,nim,' . $mahasiswa->id,
                'angkatan' => 'required|string|size:4',
            ]);

            $mahasiswa->user->update([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => $validated['password'] ? Hash::make($validated['password']) : $mahasiswa->user->password,
            ]);

            $mahasiswa->update([
                'nim' => $validated['nim'],
                'angkatan' => $validated['angkatan'],
            ]);

            return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil diperbarui.');
        });
    }

    public function mahasiswaDestroy(Mahasiswa $mahasiswa)
    {
        return DB::transaction(function () use ($mahasiswa) {
            if ($mahasiswa->tugasAkhirs()->count() > 0) {
                return redirect()->route('admin.mahasiswa.index')->with('error', 'Mahasiswa tidak dapat dihapus karena memiliki tugas akhir.');
            }

            $mahasiswa->user->delete();
            $mahasiswa->delete();

            return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
        });
    }

    // Dosen
    public function dosenIndex()
    {
        return redirect()->route('admin.mahasiswa.index'); // Dashboard sama untuk mahasiswa dan dosen
    }

    public function dosenStore(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'nip' => 'required|string|unique:dosen,nip',
                'bidang_keahlian' => 'required|string|max:255',
            ]);

            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'dosen',
            ]);

            Dosen::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'bidang_keahlian' => $validated['bidang_keahlian'],
            ]);

            return redirect()->route('admin.mahasiswa.index')->with('success', 'Dosen berhasil ditambahkan.');
        });
    }

    public function dosenEdit(Dosen $dosen)
    {
        return response()->json([
            'id' => $dosen->id,
            'nama' => $dosen->user->nama,
            'email' => $dosen->user->email,
            'nip' => $dosen->nip,
            'bidang_keahlian' => $dosen->bidang_keahlian,
        ]);
    }

    public function dosenUpdate(Request $request, Dosen $dosen)
    {
        return DB::transaction(function () use ($request, $dosen) {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $dosen->user->id,
                'password' => 'nullable|string|min:8',
                'nip' => 'required|string|unique:dosen,nip,' . $dosen->id,
                'bidang_keahlian' => 'required|string|max:255',
            ]);

            $dosen->user->update([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => $validated['password'] ? Hash::make($validated['password']) : $dosen->user->password,
            ]);

            $dosen->update([
                'nip' => $validated['nip'],
                'bidang_keahlian' => $validated['bidang_keahlian'],
            ]);

            return redirect()->route('admin.mahasiswa.index')->with('success', 'Dosen berhasil diperbarui.');
        });
    }

    public function dosenDestroy(Dosen $dosen)
    {
        return DB::transaction(function () use ($dosen) {
            if ($dosen->tugasAkhirs()->count() > 0) {
                return redirect()->route('admin.mahasiswa.index')->with('error', 'Dosen tidak dapat dihapus karena memiliki tugas akhir.');
            }

            $dosen->user->delete();
            $dosen->delete();

            return redirect()->route('admin.mahasiswa.index')->with('success', 'Dosen berhasil dihapus.');
        });
    }
}