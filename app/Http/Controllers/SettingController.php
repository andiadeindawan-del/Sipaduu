<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function updateGeneral(Request $request)
    {
        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function updateSecurity(Request $request)
    {
        return back()->with('success', 'Pengaturan keamanan berhasil diperbarui.');
    }

    public function updateNotifications(Request $request)
    {
        return back()->with('success', 'Pengaturan notifikasi berhasil diperbarui.');
    }
}
