<?php

namespace App\Http\Controllers;

use App\Models\TugasAkhir;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TugasAkhirController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tugasAkhirs = collect([]);

        if ($user->role === 'admin') {
            $tugasAkhirs = TugasAkhir::with(['mahasiswa.user', 'dosens.user'])->get();
        } elseif ($user->role === 'dosen') {
            $tugasAkhirs = TugasAkhir::with(['mahasiswa.user', 'dosens.user'])
                ->whereHas('dosens', function ($query) use ($user) {
                    $query->where('dosen_id', $user->dosen->id);
                })
                ->get();
        } else {
            $mahasiswa = $user->mahasiswa;
            if ($mahasiswa) {
                $tugasAkhirs = TugasAkhir::with(['mahasiswa.user', 'dosens.user'])
                    ->where('mahasiswa_id', $mahasiswa->id)
                    ->get();
            }
        }

        Log::info('Tugas Akhir index loaded', [
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'role' => $user->role,
            'mahasiswa_id' => $user->mahasiswa ? $user->mahasiswa->id : null,
            'tugas_akhirs_count' => $tugasAkhirs->count(),
        ]);

        return view('tugas-akhir.index', compact('tugasAkhirs'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'mahasiswa') {
            abort(403, 'Hanya mahasiswa yang dapat membuat tugas akhir.');
        }

        $dosens = Dosen::with('user')->get();
        return view('tugas-akhir.create', compact('dosens'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'mahasiswa') {
            abort(403, 'Hanya mahasiswa yang dapat membuat tugas akhir.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'dosen_ids' => 'required|array|min:1|max:2', // Minimal 1, maksimal 2 dosen
            'dosen_ids.*' => 'exists:dosen,id', // Validasi setiap ID dosen
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'dosen_ids.required' => 'Pilih setidaknya satu dosen pembimbing.',
            'dosen_ids.*.exists' => 'Dosen tidak valid.',
            'file.mimes' => 'File harus berupa PDF, DOC, atau DOCX.',
            'file.max' => 'File maksimal 10MB.',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            Log::error('Mahasiswa record not found for user', ['user_id' => Auth::id()]);
            return redirect()->route('tugas-akhir.create')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas-akhir', 'public');
        }

        return DB::transaction(function () use ($validated, $mahasiswa, $filePath) {
            $tugasAkhir = TugasAkhir::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'file_path' => $filePath,
                'status' => 'pending',
            ]);

            $tugasAkhir->dosens()->attach($validated['dosen_ids']);

            Log::info('Tugas Akhir created', [
                'tugas_akhir_id' => $tugasAkhir->id,
                'mahasiswa_id' => $mahasiswa->id,
                'user_id' => Auth::id(),
                'judul' => $validated['judul'],
                'dosen_ids' => $validated['dosen_ids'],
            ]);

            return redirect()->route('tugas-akhir.index')->with('success', 'Tugas akhir berhasil diajukan.');
        });
    }

    public function show(TugasAkhir $tugasAkhir)
    {
        $user = Auth::user();
        $isDosenPembimbing = $user->role === 'dosen' && $tugasAkhir->dosens->contains('id', $user->dosen->id);
        if ($user->role !== 'admin' && $user->mahasiswa?->id !== $tugasAkhir->mahasiswa_id && !$isDosenPembimbing) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('tugas-akhir.show', compact('tugasAkhir'));
    }

    public function edit(TugasAkhir $tugasAkhir)
    {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa' || $user->mahasiswa->id !== $tugasAkhir->mahasiswa_id || $tugasAkhir->status !== 'pending') {
            abort(403, 'Hanya mahasiswa pemilik tugas akhir dengan status pending yang dapat mengedit.');
        }

        $dosens = Dosen::with('user')->get();
        return view('tugas-akhir.edit', compact('tugasAkhir', 'dosens'));
    }

    public function update(Request $request, TugasAkhir $tugasAkhir)
    {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa' || $user->mahasiswa->id !== $tugasAkhir->mahasiswa_id || $tugasAkhir->status !== 'pending') {
            abort(403, 'Hanya mahasiswa pemilik tugas akhir dengan status pending yang dapat mengedit.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'dosen_ids' => 'required|array|min:1|max:2',
            'dosen_ids.*' => 'exists:dosen,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'dosen_ids.required' => 'Pilih setidaknya satu dosen pembimbing.',
            'dosen_ids.*.exists' => 'Dosen tidak valid.',
            'file.mimes' => 'File harus berupa PDF, DOC, atau DOCX.',
            'file.max' => 'File maksimal 10MB.',
        ]);

        return DB::transaction(function () use ($request, $validated, $tugasAkhir) {
            $filePath = $tugasAkhir->file_path;
            if ($request->hasFile('file')) {
                if ($filePath) {
                    Storage::disk('public')->delete($filePath);
                }
                $filePath = $request->file('file')->store('tugas-akhir', 'public');
            }

            $tugasAkhir->update([
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'file_path' => $filePath,
            ]);

            $tugasAkhir->dosens()->sync($validated['dosen_ids']);

            Log::info('Tugas Akhir updated', [
                'tugas_akhir_id' => $tugasAkhir->id,
                'mahasiswa_id' => $tugasAkhir->mahasiswa_id,
                'user_id' => Auth::id(),
                'judul' => $validated['judul'],
                'dosen_ids' => $validated['dosen_ids'],
            ]);

            return redirect()->route('tugas-akhir.index')->with('success', 'Tugas akhir berhasil diperbarui.');
        });
    }

    public function destroy(TugasAkhir $tugasAkhir)
    {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa' || $user->mahasiswa->id !== $tugasAkhir->mahasiswa_id || $tugasAkhir->status !== 'pending') {
            abort(403, 'Hanya mahasiswa pemilik tugas akhir dengan status pending yang dapat menghapus.');
        }

        return DB::transaction(function () use ($tugasAkhir) {
            if ($tugasAkhir->file_path) {
                Storage::disk('public')->delete($tugasAkhir->file_path);
            }

            $tugasAkhir->dosens()->detach(); // Hapus relasi di tabel pivot
            $tugasAkhir->delete();

            Log::info('Tugas Akhir deleted', [
                'tugas_akhir_id' => $tugasAkhir->id,
                'mahasiswa_id' => $tugasAkhir->mahasiswa_id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('tugas-akhir.index')->with('success', 'Tugas akhir berhasil dihapus.');
        });
    }

    public function approve(Request $request, TugasAkhir $tugasAkhir)
    {
        $user = Auth::user();
        if ($user->role !== 'dosen' || !$tugasAkhir->dosens->contains('id', $user->dosen->id)) {
            abort(403, 'Hanya dosen pembimbing yang dapat menyetujui tugas akhir.');
        }

        $validated = $request->validate([
            'catatan' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($tugasAkhir, $validated) {
            $tugasAkhir->update([
                'status' => 'approved',
                'catatan' => $validated['catatan'] ?? $tugasAkhir->catatan,
            ]);

            Log::info('Tugas Akhir approved', [
                'tugas_akhir_id' => $tugasAkhir->id,
                'dosen_id' => Auth::user()->dosen->id,
                'catatan' => $validated['catatan'],
            ]);

            return redirect()->route('tugas-akhir.index')->with('success', 'Tugas akhir berhasil disetujui.');
        });
    }

    public function reject(Request $request, TugasAkhir $tugasAkhir)
    {
        $user = Auth::user();
        if ($user->role !== 'dosen' || !$tugasAkhir->dosens->contains('id', $user->dosen->id)) {
            abort(403, 'Hanya dosen pembimbing yang dapat menolak tugas akhir.');
        }

        $validated = $request->validate([
            'catatan' => 'required|string',
        ]);

        return DB::transaction(function () use ($tugasAkhir, $validated) {
            $tugasAkhir->update([
                'status' => 'rejected',
                'catatan' => $validated['catatan'],
            ]);

            Log::info('Tugas Akhir rejected', [
                'tugas_akhir_id' => $tugasAkhir->id,
                'dosen_id' => Auth::user()->dosen->id,
                'catatan' => $validated['catatan'],
            ]);

            return redirect()->route('tugas-akhir.index')->with('success', 'Tugas akhir telah ditolak.');
        });
    }
}