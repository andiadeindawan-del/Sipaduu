<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIPADU - Sistem Pengembangan SDM Usaha KOPERINDAG">
    <title>Masuk | SIPADU</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4e9af1;
            --primary-dark: #3a7bc8;
            --primary-light: #e8f4f8;
            --secondary: #1a2236;
            --accent: #28c76f;
            --gold-500: #c9962b;
            --gold-400: #dcb356;
            --paper: #f8fafc;
            --paper-2: #f0f7fa;
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
            max-width: 1080px;
            display: grid;
            grid-template-columns: 1fr;
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 40px 90px -30px rgba(26, 34, 54, 0.3);
            border: 1px solid var(--line);
        }

        @media (min-width: 960px) {
            .auth-shell { grid-template-columns: 1.05fr 1fr; min-height: 640px; }
        }

        /* ============================== LEFT: BRAND PANEL ============================== */
        .brand-panel {
            position: relative;
            background: linear-gradient(135deg, #1a3a4a 0%, #2c7a9a 50%, #4a9aba 100%);
            color: #fff;
            padding: 3rem 3rem 2.5rem;
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
                radial-gradient(circle at 20px 20px, transparent 9px, rgba(255,255,255,0.4) 9.6px, rgba(255,255,255,0.4) 10px, transparent 10.6px),
                radial-gradient(circle at 60px 60px, transparent 9px, rgba(255,255,255,0.4) 9.6px, rgba(255,255,255,0.4) 10px, transparent 10.6px);
            background-size: 40px 40px;
            mix-blend-mode: overlay;
            pointer-events: none;
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            right: -30%;
            bottom: -30%;
            width: 65%;
            height: 65%;
            background: radial-gradient(circle, rgba(78, 154, 241, 0.2) 0%, transparent 72%);
            pointer-events: none;
        }

        .brand-mark { position: relative; z-index: 2; display: flex; align-items: center; gap: 0.75rem; }

        .brand-mark .logo-img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            background: #fff;
            border-radius: 12px;
            padding: 6px;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .brand-mark .mark-word { display: flex; flex-direction: column; line-height: 1.1; }

        .brand-mark .mark-word .name {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 2.0rem;
            letter-spacing: 0.02em;
        }

        .brand-mark .mark-word .agency {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.82rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.8);
            margin-top: 0.15rem;
        }

        .brand-copy { position: relative; z-index: 2; padding: 2.2rem 0; }

        .brand-copy .eyebrow {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.68rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
            margin-bottom: 1rem;
        }

        .brand-copy h1 {
            font-family: 'Fraunces', serif;
            font-weight: 500;
            font-size: 2.5rem;
            line-height: 1.12;
            max-width: 460px;
        }

        .brand-copy h1 em {
            font-style: italic;
            color: #6ab0f5;
            font-weight: 400;
        }

        .brand-copy p {
            margin-top: 1rem;
            color: rgba(255,255,255,0.7);
            font-size: 0.92rem;
            max-width: 380px;
            line-height: 1.55;
        }

        .brand-stats {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 2.2rem;
            padding-top: 1.6rem;
            border-top: 1px solid rgba(255,255,255,0.12);
        }

        .brand-stats .stat .num {
            font-family: 'Fraunces', serif;
            font-size: 1.4rem;
            color: #6ab0f5;
            font-weight: 600;
        }

        .brand-stats .stat .label {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.55);
            margin-top: 0.15rem;
        }

        /* ============================== RIGHT: FORM PANEL ============================== */
        .form-panel {
            padding: 3rem 3.2rem;
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
            margin-top: 0.3rem;
            margin-bottom: 1.8rem;
        }

        .field { margin-bottom: 1.15rem; }

        .field label {
            display: block;
            font-weight: 600;
            font-size: 0.78rem;
            color: var(--secondary);
            margin-bottom: 0.35rem;
        }

        .field .control {
            position: relative;
        }

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

        .field input::placeholder { color: #8a9aa8; }

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

        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.4rem;
        }

        .check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            color: var(--ink-soft);
        }

        .check input {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot {
            font-size: 0.8rem;
            color: var(--ink-soft);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot:hover { color: var(--primary); text-decoration: underline; }

        .btn-submit {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #4e9af1, #3a7bc8);
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.02em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #3a7bc8, #2c6aad);
            transform: translateY(-1px);
            box-shadow: 0 10px 24px -8px rgba(78, 154, 241, 0.5);
        }

        .btn-submit:active { transform: translateY(0); }

        .form-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.82rem;
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
            font-size: 0.82rem;
            padding: 0.7rem 1rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .alert-success { background: var(--success-bg); color: var(--success); }
        .alert-danger { background: var(--danger-bg); color: var(--danger); }
        .alert svg { width: 17px; height: 17px; flex-shrink: 0; }

        /* ============================== RESPONSIVE ============================== */
        @media (max-width: 959px) {
            .brand-panel { padding: 2.2rem; }
            .brand-copy { padding: 1.4rem 0; }
            .brand-copy h1 { font-size: 1.9rem; }
            .brand-stats { display: none; }
            .form-panel { padding: 2.2rem; }
        }

        @media (max-width: 480px) {
            .auth-shell { border-radius: 16px; }
            .brand-panel { padding: 1.6rem; }
            .brand-copy h1 { font-size: 1.6rem; }
            .brand-copy p { font-size: 0.85rem; }
            .form-panel { padding: 1.6rem; }
        }
    </style>
</head>
<body>

    <div class="auth-shell">
        <!-- ============================================================ -->
        <!-- LEFT: BRAND PANEL -->
        <!-- ============================================================ -->
        <div class="brand-panel">
            <div class="brand-mark">
                <img src="{{ asset('assets/images/logo-sulbar.jpg') }}" alt="Lambang Provinsi Sulawesi Barat" class="logo-img">
                <div class="mark-word">
                    <span class="name">SIPADU</span>
                    <span class="agency">Dinas Koperindag Prov. Sulawesi Barat</span>
                </div>
            </div>

            <div class="brand-copy">
                <p class="eyebrow">Portal Pelatihan &amp; SDM</p>
                <h1>Tumbuh bersama, <em>berdaya</em> bersama.</h1>
                <p>Sistem Pengembangan SDM Usaha KOPERINDAG &mdash; ruang belajar dan pelatihan bagi pelaku koperasi, industri, dan perdagangan.</p>
            </div>

            <div class="brand-stats">
                <div class="stat">
                    <div class="num">120+</div>
                    <div class="label">Modul pelatihan</div>
                </div>
                <div class="stat">
                    <div class="num">4.500</div>
                    <div class="label">Peserta terdaftar</div>
                </div>
                <div class="stat">
                    <div class="num">98%</div>
                    <div class="label">Tingkat kelulusan</div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- RIGHT: LOGIN FORM -->
        <!-- ============================================================ -->
        <div class="form-panel">
            <p class="eyebrow">Akses Anggota</p>
            <h2>Masuk ke akun Anda</h2>
            <p class="sub">Masukkan email dan kata sandi terdaftar Anda.</p>

            @if(session('status'))
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <div class="control">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4V4z" opacity="0"/><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                        <input type="email"
                               class="@error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@instansi.go.id"
                               required
                               autofocus>
                    </div>
                    @error('email')
                        <div class="err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Kata Sandi</label>
                    <div class="control">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
                        <input type="password"
                               class="@error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Kata sandi"
                               required>
                    </div>
                    @error('password')
                        <div class="err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row-between">
                    <label class="check">
                        <input type="checkbox" name="remember" id="remember">
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a class="forgot" href="{{ route('password.request') }}">Lupa kata sandi?</a>
                    @endif
                </div>

                <button type="submit" class="btn-submit">
                    Masuk
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
            </form>

            <div class="form-footer">
                Belum punya akun?
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Daftar sekarang</a>
                @endif
            </div>
        </div>
    </div>

</body>
</html>