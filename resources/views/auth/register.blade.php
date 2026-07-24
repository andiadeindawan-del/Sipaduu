<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIPADU - Sistem Pengembangan SDM Usaha KOPERINDAG">
    <title>Daftar | SIPADU</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --teal-900: #0d2e2f;
            --teal-800: #10403f;
            --teal-700: #17504e;
            --gold-500: #c9962b;
            --gold-400: #dcb356;
            --sage-400: #8fae9c;
            --paper: #f7f2e7;
            --paper-2: #efe8d8;
            --ink: #1a2420;
            --ink-soft: #55625b;
            --line: #e2dac6;
            --danger: #a5372f;
            --danger-bg: #f7e6e3;
            --success: #33613f;
            --success-bg: #e7efe4;
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
            max-width: 1180px;
            display: grid;
            grid-template-columns: 1fr;
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 40px 90px -30px rgba(13, 46, 47, 0.35);
            border: 1px solid var(--line);
        }

        @media (min-width: 960px) {
            .auth-shell { grid-template-columns: 0.85fr 1.15fr; min-height: 640px; }
        }

        /* ============================== LEFT: BRAND PANEL ============================== */
        .brand-panel {
            position: relative;
            background: var(--teal-900);
            color: #fff;
            padding: 3rem 2.6rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        /* woven kawung-inspired geometric pattern, low opacity */
        .brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.14;
            background-image:
                radial-gradient(circle at 20px 20px, transparent 9px, rgba(255,255,255,0.55) 9.6px, rgba(255,255,255,0.55) 10px, transparent 10.6px),
                radial-gradient(circle at 60px 60px, transparent 9px, rgba(255,255,255,0.55) 9.6px, rgba(255,255,255,0.55) 10px, transparent 10.6px);
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
            background: radial-gradient(circle, rgba(201,150,43,0.18) 0%, transparent 72%);
            pointer-events: none;
        }

        .brand-mark { position: relative; z-index: 2; display: flex; align-items: center; gap: 0.75rem; }

        .brand-mark .logo-img {
            width: 58px;
            height: 58px;
            object-fit: contain;
            background: #fff;
            border-radius: 10px;
            padding: 4px;
            flex-shrink: 0;
        }

        .brand-mark .mark-word { display: flex; flex-direction: column; line-height: 1.1; }

        .brand-mark .mark-word .name {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.8rem;
            letter-spacing: 0.02em;
        }

        .brand-mark .mark-word .agency {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.76rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--gold-400);
            margin-top: 0.15rem;
        }

        .brand-copy { position: relative; z-index: 2; padding: 2rem 0; }

        .brand-copy .eyebrow {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.68rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--sage-400);
            margin-bottom: 1rem;
        }

        .brand-copy h1 {
            font-family: 'Fraunces', serif;
            font-weight: 500;
            font-size: 2.25rem;
            line-height: 1.14;
            max-width: 420px;
        }

        .brand-copy h1 em {
            font-style: italic;
            color: var(--gold-400);
            font-weight: 400;
        }

        .brand-copy p {
            margin-top: 1rem;
            color: rgba(255,255,255,0.62);
            font-size: 0.92rem;
            max-width: 360px;
            line-height: 1.55;
        }

        .brand-features {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            padding-top: 1.6rem;
            border-top: 1px solid rgba(255,255,255,0.14);
        }

        .brand-features .feature-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.72);
        }

        .brand-features .feature-item svg {
            width: 17px;
            height: 17px;
            stroke: var(--gold-400);
            flex-shrink: 0;
        }

        /* ============================== RIGHT: FORM PANEL ============================== */
        .form-panel {
            padding: 2.6rem 3.1rem;
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
            color: var(--gold-500);
            margin-bottom: 0.4rem;
        }

        .form-panel h2 {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--teal-900);
        }

        .form-panel .sub {
            color: var(--ink-soft);
            font-size: 0.86rem;
            margin-top: 0.3rem;
            margin-bottom: 1.5rem;
        }

        .field-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0 1rem;
        }

        @media (min-width: 640px) {
            .field-grid { grid-template-columns: 1fr 1fr; }
        }

        .field { margin-bottom: 1rem; }

        .field label {
            display: block;
            font-weight: 600;
            font-size: 0.76rem;
            color: var(--teal-900);
            margin-bottom: 0.32rem;
        }

        .field .control { position: relative; }

        .field .control svg {
            position: absolute;
            left: 14px;
            top: 22px;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            stroke: #a3ab9f;
            pointer-events: none;
        }

        .field .control svg.icon-top { top: 20px; }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            height: 46px;
            padding: 0 0.9rem 0 2.6rem;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            font-size: 0.87rem;
            font-family: inherit;
            background: var(--paper);
            color: var(--ink);
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }

        .field select {
            appearance: auto;
            cursor: pointer;
        }

        .field textarea {
            height: auto;
            min-height: 46px;
            padding-top: 0.65rem;
            resize: vertical;
        }

        .field input::placeholder,
        .field textarea::placeholder { color: #a6ac9f; }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            outline: none;
            border-color: var(--teal-700);
            box-shadow: 0 0 0 3px rgba(23, 80, 78, 0.12);
            background: #fff;
        }

        .field input.is-invalid,
        .field select.is-invalid,
        .field textarea.is-invalid {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(165, 55, 47, 0.1);
        }

        .field .err {
            color: var(--danger);
            font-size: 0.74rem;
            margin-top: 0.3rem;
        }

        .btn-submit {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 10px;
            background: var(--teal-900);
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
            margin-top: 0.4rem;
        }

        .btn-submit:hover {
            background: var(--teal-700);
            transform: translateY(-1px);
            box-shadow: 0 10px 24px -8px rgba(13,46,47,0.5);
        }

        .btn-submit:active { transform: translateY(0); }

        .form-footer {
            margin-top: 1.4rem;
            text-align: center;
            font-size: 0.82rem;
            color: var(--ink-soft);
        }

        .form-footer a {
            color: var(--teal-800);
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
            .brand-copy h1 { font-size: 1.85rem; }
            .brand-features { display: none; }
            .form-panel { padding: 2.2rem; }
        }

        @media (max-width: 480px) {
            .auth-shell { border-radius: 16px; }
            .brand-panel { padding: 1.6rem; }
            .brand-copy h1 { font-size: 1.55rem; }
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
                <!-- Lambang resmi Provinsi Sulawesi Barat. Letakkan file resmi (PNG/SVG) di public/images/logo-sulbar.png -->
                <img src="{{ asset('assets/images/logo-sulbar.jpg') }}" alt="Lambang Provinsi Sulawesi Barat" class="logo-img">
                <div class="mark-word">
                    <span class="name">SIPADU</span>
                    <span class="agency">Dinas Koperindag Prov. Sulawesi Barat</span>
                </div>
            </div>

            <div class="brand-copy">
                <p class="eyebrow">Portal Pelatihan &amp; SDM</p>
                <h1>Mulai perjalanan <em>belajar</em> Anda.</h1>
                <p>Daftarkan usaha Anda untuk mengakses pelatihan, sertifikasi, dan program pengembangan SDM koperasi, industri, dan perdagangan.</p>
            </div>

            <div class="brand-features">
                <div class="feature-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Koneksi aman &amp; terenkripsi
                </div>
                <div class="feature-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 1.5 3 3 6 3s6-1.5 6-3v-5"/></svg>
                    Akses ke semua pelatihan
                </div>
                <div class="feature-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M9.5 13.5L7 22l5-3 5 3-2.5-8.5"/></svg>
                    Dapatkan sertifikat resmi
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- RIGHT: REGISTER FORM -->
        <!-- ============================================================ -->
        <div class="form-panel">
            <p class="eyebrow">Daftar Akun</p>
            <h2>Buat akun baru</h2>
            <p class="sub">Isi data diri dan usaha Anda untuk mendaftar.</p>

            @if(session('status'))
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Terdapat kesalahan pada form di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field-grid">
                    <!-- NIK -->
                    <div class="field">
                        <label for="nik">NIK</label>
                        <div class="control">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M6 15h4M6 11h.01"/><circle cx="16" cy="11" r="1.5"/></svg>
                            <input type="text"
                                   class="@error('nik') is-invalid @enderror"
                                   id="nik"
                                   name="nik"
                                   value="{{ old('nik') }}"
                                   placeholder="Nomor Induk Kependudukan"
                                   required>
                        </div>
                        @error('nik')
                            <div class="err">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="field">
                        <label for="nama">Nama Lengkap</label>
                        <div class="control">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
                            <input type="text"
                                   class="@error('nama') is-invalid @enderror"
                                   id="nama"
                                   name="nama"
                                   value="{{ old('nama') }}"
                                   placeholder="Nama lengkap"
                                   required>
                        </div>
                        @error('nama')
                            <div class="err">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="field-grid">
                    <!-- Email -->
                    <div class="field">
                        <label for="email">Email</label>
                        <div class="control">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                            <input type="email"
                                   class="@error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="nama@instansi.go.id"
                                   required>
                        </div>
                        @error('email')
                            <div class="err">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- No Telepon -->
                    <div class="field">
                        <label for="no_telepon">No. Telepon</label>
                        <div class="control">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6 19.8 19.8 0 01-3.1-8.7A2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .3 2 .6 3a2 2 0 01-.5 2L8 10a16 16 0 006 6l1.3-1.2a2 2 0 012-.5c1 .3 2 .5 3 .6a2 2 0 011.7 2z"/></svg>
                            <input type="text"
                                   class="@error('no_telepon') is-invalid @enderror"
                                   id="no_telepon"
                                   name="no_telepon"
                                   value="{{ old('no_telepon') }}"
                                   placeholder="No. Telepon"
                                   required>
                        </div>
                        @error('no_telepon')
                            <div class="err">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="field-grid">
                    <!-- Nama Usaha -->
                    <div class="field">
                        <label for="nama_usaha">Nama Usaha</label>
                        <div class="control">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1m4 0h1m-6 4h1m4 0h1m-6 4h1m4 0h1"/></svg>
                            <input type="text"
                                   class="@error('nama_usaha') is-invalid @enderror"
                                   id="nama_usaha"
                                   name="nama_usaha"
                                   value="{{ old('nama_usaha') }}"
                                   placeholder="Nama usaha"
                                   required>
                        </div>
                        @error('nama_usaha')
                            <div class="err">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NIB -->
                    <div class="field">
                        <label for="nib">NIB</label>
                        <div class="control">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></svg>
                            <input type="text"
                                   class="@error('nib') is-invalid @enderror"
                                   id="nib"
                                   name="nib"
                                   value="{{ old('nib') }}"
                                   placeholder="Nomor Induk Berusaha"
                                   required>
                        </div>
                        @error('nib')
                            <div class="err">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="field-grid">
                    <!-- Jenis Usaha -->
                    <div class="field">
                        <label for="jenis_usaha">Jenis Usaha</label>
                        <div class="control">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 12.7L12.7 20.6a2 2 0 01-2.8 0L2 12.7V4a2 2 0 012-2h8.7a2 2 0 011.4.6l6.5 6.5a2 2 0 010 2.8z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
                            <select class="@error('jenis_usaha') is-invalid @enderror"
                                    id="jenis_usaha"
                                    name="jenis_usaha"
                                    required>
                                <option value="">Pilih jenis usaha</option>
                                <option value="formal" @selected(old('jenis_usaha') === 'formal')>Formal</option>
                                <option value="non_formal" @selected(old('jenis_usaha') === 'non_formal')>Non Formal</option>
                            </select>
                        </div>
                        @error('jenis_usaha')
                            <div class="err">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="field">
                        <label for="alamat_lengkap">Alamat Lengkap</label>
                        <div class="control">
                            <svg class="icon-top" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <textarea class="@error('alamat_lengkap') is-invalid @enderror"
                                      id="alamat_lengkap"
                                      name="alamat_lengkap"
                                      rows="1"
                                      placeholder="Alamat lengkap"
                                      required>{{ old('alamat_lengkap') }}</textarea>
                        </div>
                        @error('alamat_lengkap')
                            <div class="err">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="field-grid">
                    <!-- Password -->
                    <div class="field">
                        <label for="password">Kata Sandi</label>
                        <div class="control">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
                            <input type="password"
                                   class="@error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Min. 8 karakter"
                                   required>
                        </div>
                        @error('password')
                            <div class="err">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <div class="control">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/><path d="M12 15v2"/></svg>
                            <input type="password"
                                   class="@error('password_confirmation') is-invalid @enderror"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="Ulangi kata sandi"
                                   required>
                        </div>
                        @error('password_confirmation')
                            <div class="err">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Daftar Sekarang
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
            </form>

            <div class="form-footer">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>

</body>
</html>