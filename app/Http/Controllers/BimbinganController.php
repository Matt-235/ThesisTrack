<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BimbinganController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bimbingans = collect([]);

        if ($user->role === 'admin') {
            $bimbingans = Bimbingan::with(['tugasAkhir.mahasiswa.user', 'dosen.user', 'mahasiswa.user'])
                ->get();
        } elseif ($user->role === 'dosen') {
            $bimbingans = Bimbingan::with(['tugasAkhir.mahasiswa.user', 'dosen.user', 'mahasiswa.user'])
                ->where('dosen_id', $user->dosen->id)
                ->get();
        } elseif ($user->role === 'mahasiswa') {
            $bimbingans = Bimbingan::with(['tugasAkhir.mahasiswa.user', 'dosen.user', 'mahasiswa.user'])
                ->where('mahasiswa_id', $user->mahasiswa->id)
                ->get();
        }

        Log::info('Bimbingan index loaded', [
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'role' => $user->role,
            'bimbingans_count' => $bimbingans->count(),
        ]);

        return view('bimbingan.index', compact('bimbingans'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'dosen') {
            abort(403, 'Hanya dosen yang dapat membuat bimbingan.');
        }

        $tugasAkhirs = TugasAkhir::with(['mahasiswa.user', 'dosens.user'])
            ->whereHas('dosens', function ($query) {
                $query->where('dosen_id', Auth::user()->dosen->id);
            })
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
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'catatan' => 'required|string',
            'tanggal' => 'required|date|after_or_equal:today',
        ], [
            'tugas_akhir_id.required' => 'Pilih tugas akhir.',
            'tugas_akhir_id.exists' => 'Tugas akhir tidak valid.',
            'mahasiswa_id.required' => 'Pilih mahasiswa.',
            'mahasiswa_id.exists' => 'Mahasiswa tidak valid.',
            'catatan.required' => 'Catatan wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal harus hari ini atau di masa depan.',
        ]);

        $tugasAkhir = TugasAkhir::findOrFail($validated['tugas_akhir_id']);
        if (!$tugasAkhir->dosens->contains('id', Auth::user()->dosen->id) ||
            $tugasAkhir->mahasiswa_id !== $validated['mahasiswa_id'] ||
            $tugasAkhir->status !== 'approved') {
            return redirect()->route('bimbingan.create')->with('error', 'Tugas akhir atau mahasiswa tidak valid.');
        }

        return DB::transaction(function () use ($validated) {
            $bimbingan = Bimbingan::create([
                'tugas_akhir_id' => $validated['tugas_akhir_id'],
                'mahasiswa_id' => $validated['mahasiswa_id'],
                'dosen_id' => Auth::user()->dosen->id,
                'catatan' => $validated['catatan'],
                'tanggal' => $validated['tanggal'],
            ]);

            Log::info('Bimbingan created', [
                'bimbingan_id' => $bimbingan->id,
                'tugas_akhir_id' => $validated['tugas_akhir_id'],
                'mahasiswa_id' => $validated['mahasiswa_id'],
                'dosen_id' => Auth::user()->dosen->id,
            ]);

            return redirect()->route('bimbingan.index')->with('success', 'Bimbingan berhasil ditambahkan.');
        });
    }

    public function show(Bimbingan $bimbingan)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' &&
            $user->mahasiswa?->id !== $bimbingan->mahasiswa_id &&
            $user->dosen?->id !== $bimbingan->dosen_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('bimbingan.show', compact('bimbingan'));
    }

    public function edit(Bimbingan $bimbingan)
    {
        if (Auth::user()->role !== 'dosen' || Auth::user()->dosen->id !== $bimbingan->dosen_id) {
            abort(403, 'Hanya dosen pembimbing yang dapat mengedit bimbingan.');
        }

        $tugasAkhirs = TugasAkhir::with(['mahasiswa.user', 'dosens.user'])
            ->whereHas('dosens', function ($query) {
                $query->where('dosen_id', Auth::user()->dosen->id);
            })
            ->where('status', 'approved')
            ->get();

        return view('bimbingan.edit', compact('bimbingan', 'tugasAkhirs'));
    }

    public function update(Request $request, Bimbingan $bimbingan)
    {
        if (Auth::user()->role !== 'dosen' || Auth::user()->dosen->id !== $bimbingan->dosen_id) {
            abort(403, 'Hanya dosen pembimbing yang dapat mengedit bimbingan.');
        }

        $validated = $request->validate([
            'tugas_akhir_id' => 'required|exists:tugas_akhirs,id',
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'catatan' => 'required|string',
            'tanggal' => 'required|date|after_or_equal:today',
        ], [
            'tugas_akhir_id.required' => 'Pilih tugas akhir.',
            'tugas_akhir_id.exists' => 'Tugas akhir tidak valid.',
            'mahasiswa_id.required' => 'Pilih mahasiswa.',
            'mahasiswa_id.exists' => 'Mahasiswa tidak valid.',
            'catatan.required' => 'Catatan wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal harus hari ini atau di masa depan.',
        ]);

        $tugasAkhir = TugasAkhir::findOrFail($validated['tugas_akhir_id']);
        if (!$tugasAkhir->dosens->contains('id', Auth::user()->dosen->id) ||
            $tugasAkhir->mahasiswa_id !== $validated['mahasiswa_id'] ||
            $tugasAkhir->status !== 'approved') {
            return redirect()->route('bimbingan.edit', $bimbingan)->with('error', 'Tugas akhir atau mahasiswa tidak valid.');
        }

        return DB::transaction(function () use ($bimbingan, $validated) {
            $bimbingan->update([
                'tugas_akhir_id' => $validated['tugas_akhir_id'],
                'mahasiswa_id' => $validated['mahasiswa_id'],
                'catatan' => $validated['catatan'],
                'tanggal' => $validated['tanggal'],
            ]);

            Log::info('Bimbingan updated', [
                'bimbingan_id' => $bimbingan->id,
                'tugas_akhir_id' => $validated['tugas_akhir_id'],
                'mahasiswa_id' => $validated['mahasiswa_id'],
                'dosen_id' => Auth::user()->dosen->id,
            ]);

            return redirect()->route('bimbingan.index')->with('success', 'Bimbingan berhasil diperbarui.');
        });
    }

    public function destroy(Bimbingan $bimbingan)
    {
        if (Auth::user()->role !== 'dosen' || Auth::user()->dosen->id !== $bimbingan->dosen_id) {
            abort(403, 'Hanya dosen pembimbing yang dapat menghapus bimbingan.');
        }

        return DB::transaction(function () use ($bimbingan) {
            $bimbingan->delete();

            Log::info('Bimbingan deleted', [
                'bimbingan_id' => $bimbingan->id,
                'mahasiswa_id' => $bimbingan->mahasiswa_id,
                'dosen_id' => Auth::user()->dosen->id,
            ]);

            return redirect()->route('bimbingan.index')->with('success', 'Bimbingan berhasil dihapus.');
        });
    }
}