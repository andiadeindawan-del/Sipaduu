<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIPADU - Atur Ulang Kata Sandi">
    <title>Atur Ulang Kata Sandi | SIPADU</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/Lambang Pendidikan Sirkular Biru Hijau.png') }}?v={{ @filemtime(public_path('assets/images/Lambang Pendidikan Sirkular Biru Hijau.png')) }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/Lambang Pendidikan Sirkular Biru Hijau.png') }}?v={{ @filemtime(public_path('assets/images/Lambang Pendidikan Sirkular Biru Hijau.png')) }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4e9af1;
            --primary-dark: #3a7bc8;
            --primary-light: #e8f4f8;
            --secondary: #1a2236;
            --accent: #28c76f;
            --paper: #f8fafc;
            --ink: #1a2236;
            --ink-soft: #5a6a7a;
            --line: #d4e8f0;
            --danger: #ea5455;
            --danger-bg: #fce8e8;
            --success: #28c76f;
            --success-bg: #dff6e8;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--paper);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ink);
            padding: 2rem 1.5rem;
        }

        .auth-shell {
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr;
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 40px 90px -30px rgba(26, 34, 54, 0.3);
            border: 1px solid var(--line);
        }

        @media (min-width: 960px) {
            .auth-shell { grid-template-columns: 1fr 1.05fr; min-height: 600px; }
        }

        /* LEFT: BRAND PANEL */
        .brand-panel {
            position: relative;
            background: linear-gradient(135deg, #1a3a4a 0%, #2c7a9a 50%, #4a9aba 100%);
            color: #fff;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.08;
            background-image:
                radial-gradient(circle at 20px 20px, transparent 9px, rgba(255,255,255,0.4) 9.6px, rgba(255,255,255,0.4) 10px, transparent 10.6px);
            background-size: 40px 40px;
            mix-blend-mode: overlay;
            pointer-events: none;
        }

        .brand-mark { position: relative; z-index: 2; display: flex; align-items: center; gap: 0.75rem; }
        .brand-mark .logo-img {
            width: 58px;
            height: 58px;
            object-fit: contain;
            background: #fff;
            border-radius: 12px;
            padding: 6px;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .brand-mark .mark-word .name {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.8rem;
        }

        .brand-copy { position: relative; z-index: 2; padding: 2rem 0; }
        .brand-copy .eyebrow {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.68rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
            margin-bottom: 0.8rem;
        }

        .brand-copy h1 {
            font-family: 'Fraunces', serif;
            font-weight: 500;
            font-size: 2.2rem;
            line-height: 1.2;
        }

        .brand-copy h1 em {
            font-style: italic;
            color: #6ab0f5;
        }

        .brand-copy p {
            margin-top: 1rem;
            color: rgba(255,255,255,0.75);
            font-size: 0.9rem;
            line-height: 1.55;
        }

        .brand-footer-note {
            position: relative;
            z-index: 2;
            font-size: 0.76rem;
            color: rgba(255,255,255,0.6);
            border-top: 1px solid rgba(255,255,255,0.12);
            padding-top: 1rem;
        }

        /* RIGHT: FORM PANEL */
        .form-panel {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #fff;
        }

        .form-panel .eyebrow {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.66rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--primary);
            margin-bottom: 0.4rem;
        }

        .form-panel h2 {
            font-family: 'Fraunces', serif;
            font-size: 1.7rem;
            font-weight: 600;
            color: var(--secondary);
        }

        .form-panel .sub {
            color: var(--ink-soft);
            font-size: 0.88rem;
            margin-top: 0.4rem;
            margin-bottom: 1.6rem;
            line-height: 1.5;
        }

        .field { margin-bottom: 1.15rem; }

        .field label {
            display: block;
            font-weight: 600;
            font-size: 0.78rem;
            color: var(--secondary);
            margin-bottom: 0.35rem;
        }

        .field .control { position: relative; }

        .field .control svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 17px;
            height: 17px;
            stroke: #8a9aa8;
        }

        .field input {
            width: 100%;
            height: 48px;
            padding: 0 0.9rem 0 2.7rem;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: inherit;
            background: var(--paper);
            color: var(--ink);
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }

        .field input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.12);
            background: #fff;
        }

        .field input.is-invalid {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(234, 84, 85, 0.1);
        }

        .field .err {
            color: var(--danger);
            font-size: 0.76rem;
            margin-top: 0.3rem;
        }

        .btn-submit {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #4e9af1, #3a7bc8);
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #3a7bc8, #2c6aad);
            transform: translateY(-1px);
            box-shadow: 0 10px 24px -8px rgba(78, 154, 241, 0.5);
        }

        .form-footer {
            margin-top: 1.6rem;
            text-align: center;
            font-size: 0.84rem;
            color: var(--ink-soft);
        }

        .form-footer a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .form-footer a:hover { text-decoration: underline; }

        .alert {
            border-radius: 10px;
            font-size: 0.85rem;
            padding: 0.85rem 1rem;
            margin-bottom: 1.3rem;
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            line-height: 1.45;
        }

        .alert-danger { background: var(--danger-bg); color: var(--danger); border: 1px solid #fad2d2; }
        .alert svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 2px; }

        @media (max-width: 959px) {
            .brand-panel { padding: 2.2rem; }
            .form-panel { padding: 2.2rem; }
        }
    </style>
</head>
<body>

    <div class="auth-shell">
        <!-- BRAND PANEL -->
        <div class="brand-panel">
            <div class="brand-mark">
                <img src="{{ asset('assets/images/Lambang Pendidikan Sirkular Biru Hijau.png') }}?v={{ @filemtime(public_path('assets/images/Lambang Pendidikan Sirkular Biru Hijau.png')) }}" alt="Logo SIPADU" class="logo-img">
                <div class="mark-word">
                    <span class="name">SIPADU</span>
                </div>
            </div>

            <div class="brand-copy">
                <p class="eyebrow">Keamanan Akun</p>
                <h1>Buat kata sandi baru yang <em>kuat</em>.</h1>
                <p>Silakan buat kata sandi baru untuk akun Anda. Pastikan kata sandi minimal 8 karakter dan mudah Anda ingat.</p>
            </div>

            <div class="brand-footer-note">
                Sistem Pengembangan SDM Usaha KOPERINDAG &copy; {{ date('Y') }}
            </div>
        </div>

        <!-- FORM PANEL -->
        <div class="form-panel">
            <p class="eyebrow">Atur Ulang Kredensial</p>
            <h2>Kata sandi baru</h2>
            <p class="sub">Masukkan email dan tentukan kata sandi baru untuk akun Anda.</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="field">
                    <label for="email">Alamat Email</label>
                    <div class="control">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4V4z" opacity="0"/><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                        <input type="email"
                               class="@error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $request->email) }}"
                               required
                               readonly
                               style="background: #edf3f8; cursor: not-allowed;">
                    </div>
                    @error('email')
                        <div class="err">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="field">
                    <label for="password">Kata Sandi Baru</label>
                    <div class="control">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
                        <input type="password"
                               class="@error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Minimal 8 karakter"
                               required
                               autofocus
                               autocomplete="new-password">
                    </div>
                    @error('password')
                        <div class="err">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="field">
                    <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                    <div class="control">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
                        <input type="password"
                               class="@error('password_confirmation') is-invalid @enderror"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="Ulangi kata sandi baru"
                               required
                               autocomplete="new-password">
                    </div>
                    @error('password_confirmation')
                        <div class="err">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Simpan Kata Sandi Baru
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
            </form>

            <div class="form-footer">
                Batal atau ingat kata sandi?
                <a href="{{ route('login') }}">Kembali ke halaman masuk</a>
            </div>
        </div>
    </div>

</body>
</html>
