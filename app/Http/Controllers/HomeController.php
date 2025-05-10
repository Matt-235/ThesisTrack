<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $role = Auth::user()->role;
        Log::info('HomeController Role', ['role' => $role]);

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'dosen') {
            return redirect()->route('dosen.dashboard');
        } elseif ($role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        }

        return redirect('/');
    }
}