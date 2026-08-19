<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        // Statistik
        $totalUsers    = User::count();
        $activeUsers   = User::where('status', 'aktif')->count();
        $inactiveUsers = User::where('status', 'nonaktif')->count();
        $trainerCount  = User::where('role', 'trainer')->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'trainerCount'
        ));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'         => 'required|string|max:30|unique:users',
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|max:100|unique:users',
            'password'    => 'required|string|min:8|confirmed',
            'role'        => 'required|in:admin,trainer,peserta',
            'status'      => 'nullable|in:aktif,nonaktif',
            'departemen'  => 'nullable|string|max:100',
            'jabatan'     => 'nullable|string|max:100',
            'no_telepon'  => 'nullable|string|max:20',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $validated;
        $data['password'] = Hash::make($validated['password']);
        $data['status']   = $validated['status'] ?? 'aktif';

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('users', 'public');
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['trainingDiajar', 'trainingDiikuti']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nik'         => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'nama'        => 'required|string|max:100',
            'email'       => ['required', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'password'    => 'nullable|string|min:8|confirmed',
            'role'        => 'required|in:admin,trainer,peserta',
            'status'      => 'nullable|in:aktif,nonaktif',
            'departemen'  => 'nullable|string|max:100',
            'jabatan'     => 'nullable|string|max:100',
            'no_telepon'  => 'nullable|string|max:20',
            
            // Validasi file
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ktp_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'nib_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'npwp_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'file_produk' => 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx|max:5120',
            
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
            'alamat_lengkap' => 'nullable|string',
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
            'masukan_saran' => 'nullable|string',
        ]);

        $data = $validated;

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('avatars', 'public');
        }

        if ($request->hasFile('ktp_file')) {
            if ($user->ktp_file && Storage::disk('public')->exists($user->ktp_file)) {
                Storage::disk('public')->delete($user->ktp_file);
            }
            $data['ktp_file'] = $request->file('ktp_file')->store('ktp_files', 'public');
        }

        if ($request->hasFile('nib_file')) {
            if ($user->nib_file && Storage::disk('public')->exists($user->nib_file)) {
                Storage::disk('public')->delete($user->nib_file);
            }
            $data['nib_file'] = $request->file('nib_file')->store('nib_files', 'public');
        }

        if ($request->hasFile('npwp_file')) {
            if ($user->npwp_file && Storage::disk('public')->exists($user->npwp_file)) {
                Storage::disk('public')->delete($user->npwp_file);
            }
            $data['npwp_file'] = $request->file('npwp_file')->store('npwp_files', 'public');
        }

        if ($request->hasFile('file_produk')) {
            if ($user->file_produk && Storage::disk('public')->exists($user->file_produk)) {
                Storage::disk('public')->delete($user->file_produk);
            }
            $data['file_produk'] = $request->file('file_produk')->store('produk_files', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Show change password form.
     */
    public function showChangePasswordForm(User $user)
    {
        return view('admin.users.change-password', compact('user'));
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Password berhasil diubah.');
    }

    /**
     * Activate user.
     */
    public function activate(User $user)
    {
        $user->update(['status' => 'aktif']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diaktifkan.');
    }

    /**
     * Deactivate user.
     */
    public function deactivate(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update(['status' => 'nonaktif']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dinonaktifkan.');
    }

    /**
     * Export users to CSV.
     */
    public function export()
    {
        $users = User::all();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users.csv"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'NIK', 'Nama', 'Email', 'Role', 'Departemen', 'Status', 'Dibuat']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->nik,
                    $user->nama,
                    $user->email,
                    $user->role,
                    $user->departemen,
                    $user->status,
                    $user->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}