<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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
            'no_telepon' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string',
            
            // Validasi file - Wajib jika belum ada
            'avatar' => [$user->foto ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'ktp_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'nib_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'npwp_file' => [$user->npwp_file ? 'nullable' : 'required', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'],
            'file_produk' => [$user->file_produk ? 'nullable' : 'required', 'file', 'mimes:pdf,jpeg,png,jpg,doc,docx', 'max:5120'],
            
            // Tambahan field untuk UMK
            'status_pernikahan' => 'required|string|in:Menikah,Belum Menikah,Cerai Hidup,Cerai Mati',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'desa' => 'nullable|string|max:100',
            'alamat_lengkap' => 'nullable|string',
            
            'nama_usaha' => 'nullable|string|max:150',
            'status_usaha' => 'required|string|in:Aktif,Tidak Aktif',
            'bentuk_usaha' => 'required|string|in:Perorangan,PT Perorangan,UD,CV,PT,Koperasi',
            'jabatan_usaha' => 'required|string|max:100',
            'merek_produk' => 'nullable|string|max:150',
            'kbli_id' => 'required|array|min:1',
            'kbli_id.*' => 'exists:kblis,id',
            'kbli_utama' => 'required|exists:kblis,id',
            'no_telepon_usaha' => 'required|string|max:20',
            'tanggal_berdiri' => 'required|date',
            'npwp_usaha' => 'required|string|max:50',
            'nib' => 'nullable|string|max:50',
            'modal_usaha' => 'nullable|string|max:50',
            'nilai_modal' => 'nullable|numeric',
            'omzet_usaha' => 'nullable|string|max:50',
            'nilai_omzet' => 'nullable|numeric',
            'karyawan_tetap_laki_laki' => 'required|integer|min:0',
            'karyawan_tetap_perempuan' => 'required|integer|min:0',
            'karyawan_tidak_tetap_laki_laki' => 'required|integer|min:0',
            'karyawan_tidak_tetap_perempuan' => 'required|integer|min:0',
            'kapasitas_produksi' => 'nullable|string|max:100',
            
            'provinsi_usaha' => 'required|string|max:100',
            'kabupaten_usaha' => 'required|string|max:100',
            'kecamatan_usaha' => 'required|string|max:100',
            'desa_usaha' => 'required|string|max:100',
            'alamat_usaha' => 'required|string',
            
            'email_usaha' => 'required|email|max:100',
            'judul_usaha_online' => 'nullable|string|max:255',
            'website_usaha' => 'nullable|url|max:255',
            'facebook_usaha' => 'nullable|url|max:255',
            'instagram_usaha' => 'nullable|url|max:255',
            'tiktok_usaha' => 'nullable|url|max:255',
            'shopee' => 'nullable|url|max:255',
            'tokopedia' => 'nullable|url|max:255',
            'lazada' => 'nullable|url|max:255',
            'blibli' => 'nullable|url|max:255',
            'marketplace_lainnya_nama' => 'nullable|array',
            'marketplace_lainnya_nama.*' => 'nullable|string|max:150',
            'marketplace_lainnya_link' => 'nullable|array',
            'marketplace_lainnya_link.*' => 'nullable|url|max:255',
            'pengadaan_barang' => 'nullable|string|max:150',
            'akses_kredit' => 'nullable|string|max:150',
            'tabungan' => 'nullable|string|max:150',
            'perizinan_usaha' => 'nullable|string|max:150',
            'sertifikasi_produk' => 'nullable|string|max:150',
            'jangkauan_pemasaran' => 'nullable|string|max:150',
            'lokasi_pemasaran' => 'nullable|string|max:150',
            'pasok_bahan_baku' => 'nullable|string|max:150',
            'kemitraan' => 'nullable|string|max:150',
            'status_ekspor' => 'nullable|string|max:150',
            'negara_ekspor' => 'nullable|string',
            'metode_ekspor' => 'nullable|string|max:150',
            'volume_ekspor' => 'nullable|string|max:100',
            'nilai_ekspor' => 'nullable|numeric',
            'permasalahan' => 'nullable|string',
            'kebutuhan_diklat' => 'nullable|string',
            'riwayat_pelatihan' => 'nullable|string',
            'jenis_pelatihan_diikuti' => 'nullable|string',
            'masukan_saran' => 'nullable|string',
            'anggota_koperasi' => 'nullable|string|max:150',
        ]);

        // Auto kalkulasi total karyawan
        $validated['total_karyawan_tetap'] = ((int) ($validated['karyawan_tetap_laki_laki'] ?? 0)) + ((int) ($validated['karyawan_tetap_perempuan'] ?? 0));
        $validated['total_karyawan_tidak_tetap'] = ((int) ($validated['karyawan_tidak_tetap_laki_laki'] ?? 0)) + ((int) ($validated['karyawan_tidak_tetap_perempuan'] ?? 0));
        $validated['total_tenaga_kerja'] = $validated['total_karyawan_tetap'] + $validated['total_karyawan_tidak_tetap'];
        
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

        // Sinkronisasi name dengan nama agar tidak ada ketimpangan data antara peserta dan admin
        if (isset($validated['name'])) {
            $validated['nama'] = $validated['name'];
        }

        $user->update($validated);

        // Handle Multiple KBLI Saving
        if ($request->has('kbli_id')) {
            $user->kblis()->delete();
            $kbliIds = $request->input('kbli_id');
            $utamaId = $request->input('kbli_utama');
            
            // make unique and validate against actual DB to prevent spoofing
            $savedIds = [];
            $validKblis = \App\Models\Kbli::whereIn('id', $kbliIds)->pluck('id')->toArray();

            foreach ($kbliIds as $index => $kId) {
                if (!empty($kId) && in_array($kId, $validKblis) && !in_array($kId, $savedIds)) {
                    $user->kblis()->create([
                        'kbli_id' => $kId,
                        'is_utama' => ($kId == $utamaId) ? true : false,
                    ]);
                    $savedIds[] = $kId;
                }
            }
        } elseif ($request->isMethod('put') || $request->isMethod('post')) {
            $user->kblis()->delete();
        }


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
