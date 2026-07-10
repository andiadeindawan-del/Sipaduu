<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nik' => ['required', 'string', 'max:30', 'unique:users'],
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:users'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'nama_usaha' => ['required', 'string', 'max:100'],
            'nib' => ['required', 'string', 'max:30', 'unique:users'],
            'jenis_usaha' => ['required', 'in:formal,non_formal'],
            'alamat_lengkap' => ['required', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'nama_usaha' => $request->nama_usaha,
            'nib' => $request->nib,
            'jenis_usaha' => $request->jenis_usaha,
            'alamat_lengkap' => $request->alamat_lengkap,
            'password' => Hash::make($request->password),
            'role' => 'peserta',
            'status' => 'aktif',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
