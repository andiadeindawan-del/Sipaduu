<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form for ADMIN.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Statistics
        $totalQuizAttempts = $user->quizAttempts()->count() ?? 0;
        $totalCertificates = $user->certificates()->count() ?? 0;
        $totalTrainings = $user->trainings()->count() ?? 0;
        $averageQuizScore = $user->quizAttempts()->where('status', 'completed')->avg('score') ?? 0;
        
        return view('admin.profile.index', compact(
            'user',
            'totalQuizAttempts',
            'totalCertificates',
            'totalTrainings',
            'averageQuizScore'
        ));
    }

    /**
     * Display the user's profile form for PESERTA.
     */
    public function pesertaEdit(Request $request): View
    {
        $user = $request->user();
        
        // Statistics for peserta
        $totalQuizAttempts = $user->quizAttempts()->count() ?? 0;
        $totalCertificates = $user->certificates()->count() ?? 0;
        $totalTrainings = $user->trainings()->count() ?? 0;
        $averageQuizScore = $user->quizAttempts()->where('status', 'completed')->avg('score') ?? 0;
        
        return view('peserta.profile.index', compact(
            'user',
            'totalQuizAttempts',
            'totalCertificates',
            'totalTrainings',
            'averageQuizScore'
        ));
    }

    /**
     * Update the user's profile information (Admin & Peserta).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nik' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'no_telepon' => 'nullable|string|max:20', // Menyokong form yang mengirim no_telepon
            'alamat_lengkap' => 'nullable|string',
            
            // Validasi file
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ktp_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'nib_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'npwp_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            
            // Tambahan field untuk UMK
            'status_pernikahan' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'desa' => 'nullable|string|max:100',
            'kode_pos_domisili' => 'nullable|string|max:20',
            'disabilitas' => 'nullable|string|max:100',
            
            'nama_usaha' => 'nullable|string|max:150',
            'jabatan_usaha' => 'nullable|string|max:100',
            'merek_produk' => 'nullable|string|max:150',
            'kode_pos_usaha' => 'nullable|string|max:20',
            'sektor_usaha' => 'nullable|string|max:100',
            'no_telepon_usaha' => 'nullable|string|max:20',
            'bidang_usaha' => 'nullable|string|max:100',
            'tanggal_berdiri' => 'nullable|date',
            'npwp_usaha' => 'nullable|string|max:50',
            'status_nib' => 'nullable|string|max:50',
            'nib' => 'nullable|string|max:50',
            'lama_nib' => 'nullable|string|max:50',
            'modal_usaha' => 'nullable|string|max:50',
            'nilai_modal' => 'nullable|numeric',
            'omzet_usaha' => 'nullable|string|max:50',
            'nilai_omzet' => 'nullable|numeric',
            'jumlah_karyawan' => 'nullable|integer',
            'kapasitas_produksi' => 'nullable|string|max:100',
            'anggota_koperasi' => 'nullable|string|max:100',
            
            'email_usaha' => 'nullable|email|max:100',
            'website_usaha' => 'nullable|string|max:100',
            'medsos_usaha' => 'nullable|string',
            'marketplace' => 'nullable|string',
            'pengadaan_barang' => 'nullable|string|max:100',
            'akses_kredit' => 'nullable|string|max:100',
            'tabungan' => 'nullable|string|max:100',
            'perizinan_usaha' => 'nullable|string|max:100',
            'sertifikasi_produk' => 'nullable|string|max:100',
            'jangkauan_pemasaran' => 'nullable|string|max:100',
            'lokasi_pemasaran' => 'nullable|string|max:100',
            'status_ekspor' => 'nullable|string|max:100',
            'negara_ekspor' => 'nullable|string|max:100',
            'metode_ekspor' => 'nullable|string|max:100',
            'volume_ekspor' => 'nullable|string|max:100',
            'nilai_ekspor' => 'nullable|numeric',
            'pasok_bahan_baku' => 'nullable|string|max:100',
            'kemitraan' => 'nullable|string|max:100',
            
            'permasalahan' => 'nullable|string',
            'kebutuhan_diklat' => 'nullable|string',
            'riwayat_pelatihan' => 'nullable|string|max:100',
            'jenis_pelatihan_diikuti' => 'nullable|string',
            'file_produk' => 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx|max:5120',
            'masukan_saran' => 'nullable|string',
        ]);

        // Support untuk form yang lama mengirim phone bukan no_telepon
        if (isset($validated['phone']) && !isset($validated['no_telepon'])) {
            $validated['no_telepon'] = $validated['phone'];
            unset($validated['phone']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $validated['foto'] = $request->file('avatar')->store('avatars', 'public');
            unset($validated['avatar']);
        }

        // Handle KTP upload
        if ($request->hasFile('ktp_file')) {
            if ($user->ktp_file && Storage::disk('public')->exists($user->ktp_file)) {
                Storage::disk('public')->delete($user->ktp_file);
            }
            $validated['ktp_file'] = $request->file('ktp_file')->store('ktp_files', 'public');
        }

        // Handle NIB upload
        if ($request->hasFile('nib_file')) {
            if ($user->nib_file && Storage::disk('public')->exists($user->nib_file)) {
                Storage::disk('public')->delete($user->nib_file);
            }
            $validated['nib_file'] = $request->file('nib_file')->store('nib_files', 'public');
        }

        // Handle NPWP upload
        if ($request->hasFile('npwp_file')) {
            if ($user->npwp_file && Storage::disk('public')->exists($user->npwp_file)) {
                Storage::disk('public')->delete($user->npwp_file);
            }
            $validated['npwp_file'] = $request->file('npwp_file')->store('npwp_files', 'public');
        }

        // Handle File Produk upload
        if ($request->hasFile('file_produk')) {
            if ($user->file_produk && Storage::disk('public')->exists($user->file_produk)) {
                Storage::disk('public')->delete($user->file_produk);
            }
            $validated['file_produk'] = $request->file('file_produk')->store('produk_files', 'public');
        }

        // Check if email changed
        if ($user->email !== $validated['email']) {
            $validated['email_verified_at'] = null;
        }

        $user->update($validated);

        if ($user->role === 'admin') {
            return redirect()->route('admin.profile.edit')->with('success', '✅ Profil berhasil diperbarui.');
        }

        return redirect()->route('peserta.profile.index')->with('success', '✅ Profil berhasil diperbarui.');
    }

    /**
     * Update the user's password (Admin & Peserta).
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect based on user role
        if ($user->role === 'admin') {
            return redirect()->route('admin.profile.edit')
                            ->with('success', '✅ Password berhasil diubah.');
        }

        return redirect()->route('peserta.profile.index')
                        ->with('success', '✅ Password berhasil diubah.');
    }

    /**
     * Upload avatar only (Admin & Peserta).
     */
    public function uploadAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old avatar
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['foto' => $path]);

        // Redirect based on user role
        if ($user->role === 'admin') {
            return redirect()->route('admin.profile.edit')
                            ->with('success', '✅ Foto profil berhasil diperbarui.');
        }

        return redirect()->route('peserta.profile.index')
                        ->with('success', '✅ Foto profil berhasil diperbarui.');
    }

    /**
     * Remove avatar (Admin & Peserta).
     */
    public function removeAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->update(['foto' => null]);

        // Redirect based on user role
        if ($user->role === 'admin') {
            return redirect()->route('admin.profile.edit')
                            ->with('success', '✅ Foto profil berhasil dihapus.');
        }

        return back()->with('success', '✅ Foto profil berhasil dihapus.');
    }

    /**
     * View user's profile document securely
     */
    public function viewDocument(Request $request, $type, $userId = null)
    {
        $viewer = Auth::user();
        
        // Target user is either explicitly provided (for admin) or self
        $targetUserId = $userId ?: $viewer->id;
        $targetUser = \App\Models\User::findOrFail($targetUserId);

        // Security check: only admin can view others' documents, users can only view their own
        if ($viewer->id !== $targetUser->id && $viewer->role !== 'admin') {
            abort(403, 'Unauthorized access to document.');
        }

        // Map type to database column
        $column = match($type) {
            'ktp' => 'ktp_file',
            'nib' => 'nib_file',
            'npwp' => 'npwp_file',
            'produk' => 'file_produk',
            default => null
        };

        if (!$column || !$targetUser->$column) {
            abort(404, 'Document not found.');
        }

        $path = $targetUser->$column;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found on storage.');
        }

        return response()->file(storage_path('app/public/' . $path));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete avatar if exists
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Get user statistics for AJAX (Admin & Peserta).
     */
    public function getStatistics(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'total_quiz_attempts' => $user->quizAttempts()->count(),
            'total_certificates' => $user->certificates()->count(),
            'total_trainings' => $user->trainings()->count(),
            'average_quiz_score' => round($user->quizAttempts()->where('status', 'completed')->avg('score') ?? 0, 1),
        ]);
    }
}