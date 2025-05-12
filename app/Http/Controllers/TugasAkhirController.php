<?php

namespace App\Http\Controllers;

use App\Models\TugasAkhir;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TugasAkhirController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tugasAkhirs = collect([]);

        try {
            if ($user->role === 'admin') {
                $tugasAkhirs = TugasAkhir::with(['mahasiswa.user', 'dosen.user'])->get();
            } elseif ($user->role === 'dosen') {
                $tugasAkhirs = TugasAkhir::with(['mahasiswa.user', 'dosen.user'])
                    ->where('dosen_id', $user->dosen->id)
                    ->get();
            } else {
                $mahasiswa = $user->mahasiswa;
                if ($mahasiswa) {
                    $tugasAkhirs = TugasAkhir::with(['mahasiswa.user', 'dosen.user'])
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
                'tugas_akhirs' => $tugasAkhirs->toArray(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading Tugas Akhir index: ' . $e->getMessage(), ['exception' => $e]);
        }

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
            'dosen_id' => 'required|exists:dosen,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'dosen_id.required' => 'Pilih dosen pembimbing.',
            'dosen_id.exists' => 'Dosen tidak valid.',
            'file.mimes' => 'File harus berupa PDF, DOC, atau DOCX.',
            'file.max' => 'File maksimal 10MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas-akhir', 'public');
        }

        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            Log::error('Mahasiswa record not found for user', ['user_id' => Auth::id()]);
            return redirect()->route('tugas-akhir.create')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $tugasAkhir = TugasAkhir::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $validated['dosen_id'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        Log::info('Tugas Akhir created', [
            'tugas_akhir_id' => $tugasAkhir->id,
            'mahasiswa_id' => $mahasiswa->id,
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
        ]);

        return redirect()->route('tugas-akhir.index')->with('success', 'Tugas akhir berhasil diajukan.');
    }

    public function show(TugasAkhir $tugasAkhir)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->mahasiswa?->id !== $tugasAkhir->mahasiswa_id && $user->dosen?->id !== $tugasAkhir->dosen_id) {
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
            'dosen_id' => 'required|exists:dosen,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'dosen_id.required' => 'Pilih dosen pembimbing.',
            'dosen_id.exists' => 'Dosen tidak valid.',
            'file.mimes' => 'File harus berupa PDF, DOC, atau DOCX.',
            'file.max' => 'File maksimal 10MB.',
        ]);

        $filePath = $tugasAkhir->file_path;
        if ($request->hasFile('file')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('tugas-akhir', 'public');
        }

        $tugasAkhir->update([
            'dosen_id' => $validated['dosen_id'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'file_path' => $filePath,
        ]);

        return redirect()->route('tugas-akhir.index')->with('success', 'Tugas akhir berhasil diperbarui.');
    }

    public function destroy(TugasAkhir $tugasAkhir)
    {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa' || $user->mahasiswa->id !== $tugasAkhir->mahasiswa_id || $tugasAkhir->status !== 'pending') {
            abort(403, 'Hanya mahasiswa pemilik tugas akhir dengan status pending yang dapat menghapus.');
        }

        if ($tugasAkhir->file_path) {
            Storage::disk('public')->delete($tugasAkhir->file_path);
        }

        $tugasAkhir->delete();

        return redirect()->route('tugas-akhir.index')->with('success', 'Tugas akhir berhasil dihapus.');
    }

    public function approve(Request $request, TugasAkhir $tugasAkhir)
    {
        if (Auth::user()->role !== 'dosen') {
            abort(403, 'Hanya dosen yang dapat menyetujui tugas akhir.');
        }

        $validated = $request->validate([
            'catatan' => 'nullable|string',
        ]);

        $tugasAkhir->update([
            'status' => 'approved',
            'catatan' => $validated['catatan'] ?? $tugasAkhir->catatan,
        ]);

        return redirect()->route('tugas-akhir.index')->with('success', 'Tugas akhir berhasil disetujui.');
    }

    public function reject(Request $request, TugasAkhir $tugasAkhir)
    {
        $user = Auth::user();
        if ($user->role !== 'dosen' || $user->dosen->id !== $tugasAkhir->dosen_id) {
            abort(403, 'Hanya dosen pembimbing yang dapat menolak.');
        }

        $validated = $request->validate([
            'catatan' => 'required|string',
        ]);

        $tugasAkhir->update([
            'status' => 'rejected',
            'catatan' => $validated['catatan'],
        ]);

        return redirect()->route('tugas-akhir.index')->with('success', 'Tugas akhir telah ditolak.');
    }
}
