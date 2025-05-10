<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BimbinganController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $bimbingans = Bimbingan::with(['tugasAkhir.mahasiswa.user', 'tugasAkhir.dosen.user'])->get();
        } elseif ($user->role === 'dosen') {
            $bimbingans = Bimbingan::with(['tugasAkhir.mahasiswa.user', 'tugasAkhir.dosen.user'])
                ->whereHas('tugasAkhir', function ($query) use ($user) {
                    $query->where('dosen_id', $user->dosen->id);
                })->get();
        } else {
            $bimbingans = Bimbingan::with(['tugasAkhir.mahasiswa.user', 'tugasAkhir.dosen.user'])
                ->whereHas('tugasAkhir', function ($query) use ($user) {
                    $query->where('mahasiswa_id', $user->mahasiswa->id);
                })->get();
        }

        return view('bimbingan.index', compact('bimbingans'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'dosen') {
            abort(403, 'Hanya dosen yang dapat membuat bimbingan.');
        }

        $tugasAkhirs = TugasAkhir::with(['mahasiswa.user'])
            ->where('dosen_id', Auth::user()->dosen->id)
            ->where('status', 'approved')
            ->get();

        return view('bimbingan.create', compact('tugasAkhirs'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'dosen') {
            abort(403, 'Hanya dosen yang dapat membuat bimbingan.');
        }

        $validated = $request->validate([
            'tugas_akhir_id' => 'required|exists:tugas_akhirs,id',
            'catatan' => 'required|string',
            'tanggal' => 'required|date',
        ], [
            'tugas_akhir_id.required' => 'Pilih tugas akhir.',
            'tugas_akhir_id.exists' => 'Tugas akhir tidak valid.',
            'catatan.required' => 'Catatan wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
        ]);

        // Ensure the tugas akhir belongs to the dosen
        $tugasAkhir = TugasAkhir::findOrFail($validated['tugas_akhir_id']);
        if ($tugasAkhir->dosen_id !== Auth::user()->dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke tugas akhir ini.');
        }

        Bimbingan::create([
            'tugas_akhir_id' => $validated['tugas_akhir_id'],
            'catatan' => $validated['catatan'],
            'tanggal' => $validated['tanggal'],
        ]);

        return redirect()->route('bimbingan.index')->with('success', 'Bimbingan berhasil ditambahkan.');
    }

    public function show(Bimbingan $bimbingan)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' &&
            $user->mahasiswa?->id !== $bimbingan->tugasAkhir->mahasiswa_id &&
            $user->dosen?->id !== $bimbingan->tugasAkhir->dosen_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('bimbingan.show', compact('bimbingan'));
    }

    public function edit(Bimbingan $bimbingan)
    {
        if (Auth::user()->role !== 'dosen' || Auth::user()->dosen->id !== $bimbingan->tugasAkhir->dosen_id) {
            abort(403, 'Hanya dosen pembimbing yang dapat mengedit bimbingan.');
        }

        $tugasAkhirs = TugasAkhir::with(['mahasiswa.user'])
            ->where('dosen_id', Auth::user()->dosen->id)
            ->where('status', 'approved')
            ->get();

        return view('bimbingan.edit', compact('bimbingan', 'tugasAkhirs'));
    }

    public function update(Request $request, Bimbingan $bimbingan)
    {
        if (Auth::user()->role !== 'dosen' || Auth::user()->dosen->id !== $bimbingan->tugasAkhir->dosen_id) {
            abort(403, 'Hanya dosen pembimbing yang dapat mengedit bimbingan.');
        }

        $validated = $request->validate([
            'tugas_akhir_id' => 'required|exists:tugas_akhirs,id',
            'catatan' => 'required|string',
            'tanggal' => 'required|date',
        ], [
            'tugas_akhir_id.required' => 'Pilih tugas akhir.',
            'tugas_akhir_id.exists' => 'Tugas akhir tidak valid.',
            'catatan.required' => 'Catatan wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
        ]);

        // Ensure the tugas akhir belongs to the dosen
        $tugasAkhir = TugasAkhir::findOrFail($validated['tugas_akhir_id']);
        if ($tugasAkhir->dosen_id !== Auth::user()->dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke tugas akhir ini.');
        }

        $bimbingan->update([
            'tugas_akhir_id' => $validated['tugas_akhir_id'],
            'catatan' => $validated['catatan'],
            'tanggal' => $validated['tanggal'],
        ]);

        return redirect()->route('bimbingan.index')->with('success', 'Bimbingan berhasil diperbarui.');
    }

    public function destroy(Bimbingan $bimbingan)
    {
        if (Auth::user()->role !== 'dosen' || Auth::user()->dosen->id !== $bimbingan->tugasAkhir->dosen_id) {
            abort(403, 'Hanya dosen pembimbing yang dapat menghapus bimbingan.');
        }

        $bimbingan->delete();

        return redirect()->route('bimbingan.index')->with('success', 'Bimbingan berhasil dihapus.');
    }
}