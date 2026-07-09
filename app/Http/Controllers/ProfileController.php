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
            'alamat' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        // Check if email changed
        if ($user->email !== $validated['email']) {
            $validated['email_verified_at'] = null;
        }

        $user->update($validated);

        // Redirect based on user role
        if ($user->role === 'admin') {
            return redirect()->route('admin.profile.edit')
                            ->with('success', '✅ Profil berhasil diperbarui.');
        }

        return redirect()->route('peserta.profile.index')
                        ->with('success', '✅ Profil berhasil diperbarui.');
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
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

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

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        // Redirect based on user role
        if ($user->role === 'admin') {
            return redirect()->route('admin.profile.edit')
                            ->with('success', '✅ Foto profil berhasil dihapus.');
        }

        return redirect()->route('peserta.profile.index')
                        ->with('success', '✅ Foto profil berhasil dihapus.');
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
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
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