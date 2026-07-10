<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIPADU - Sistem Pelatihan Digital">
    <title>Register | SIPADU</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #1da853;
            --primary-dark: #1a8f47;
            --primary-light: #e7f7ed;
        }

        body {
            background: linear-gradient(135deg, #f0f7f2 0%, #e1eee4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 25px 60px rgba(29, 168, 83, 0.15);
            overflow: hidden;
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            min-height: 600px;
        }

        @media (min-width: 992px) {
            .auth-card {
                flex-direction: row;
                min-height: 700px;
            }
        }

        /* Left Side - Brand & Visual */
        .auth-brand-side {
            background: linear-gradient(135deg, #0d1b15 0%, #1a3a2a 100%);
            padding: 2.5rem 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            flex: 0 0 40%;
        }

        .auth-brand-side::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(29, 168, 83, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .auth-brand-side .brand-top {
            position: relative;
            z-index: 1;
        }

        .auth-brand-side .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2.5rem;
        }

        .auth-brand-side .brand-logo .logo-icon {
            width: 48px;
            height: 48px;
            background: rgba(29, 168, 83, 0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #1da853;
        }

        .auth-brand-side .brand-logo .logo-text {
            color: #fff;
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: 0.5px;
        }

        .auth-brand-side .brand-logo .logo-text span {
            color: #1da853;
        }

        .auth-brand-side .brand-logo .logo-sub {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
            font-weight: 400;
            letter-spacing: 2px;
            text-transform: uppercase;
            display: block;
            margin-top: 2px;
        }

        .auth-brand-side .brand-content {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-brand-side .brand-content h1 {
            color: #fff;
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 0.5rem;
        }

        .auth-brand-side .brand-content h1 .highlight {
            color: #1da853;
        }

        .auth-brand-side .brand-content p {
            color: rgba(255,255,255,0.6);
            font-size: 0.95rem;
            max-width: 90%;
            margin-bottom: 2rem;
        }

        .auth-brand-side .brand-features {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .auth-brand-side .brand-features .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255,255,255,0.7);
            font-size: 0.85rem;
        }

        .auth-brand-side .brand-features .feature-item i {
            color: #1da853;
            font-size: 1.1rem;
        }

        .auth-brand-side .brand-footer {
            position: relative;
            z-index: 1;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            margin-top: 1.5rem;
        }

        .auth-brand-side .brand-footer .version {
            color: rgba(255,255,255,0.3);
            font-size: 0.7rem;
        }

        /* Right Side - Form */
        .auth-form-side {
            padding: 2.5rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 0 0 60%;
            max-height: 700px;
            overflow-y: auto;
        }

        .auth-form-side::-webkit-scrollbar {
            width: 4px;
        }

        .auth-form-side::-webkit-scrollbar-track {
            background: transparent;
        }

        .auth-form-side::-webkit-scrollbar-thumb {
            background: #d0d5dd;
            border-radius: 4px;
        }

        .auth-form-side .form-header {
            margin-bottom: 1.5rem;
        }

        .auth-form-side .form-header .eyebrow {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1da853;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .auth-form-side .form-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0d1b15;
            margin-bottom: 0.25rem;
        }

        .auth-form-side .form-header p {
            color: #8a93a3;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .auth-form-side .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: #0d1b15;
            margin-bottom: 0.25rem;
        }

        .auth-form-side .input-group-custom {
            position: relative;
            margin-bottom: 0.85rem;
        }

        .auth-form-side .input-group-custom .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #b0b8c8;
            z-index: 10;
            font-size: 0.95rem;
        }

        .auth-form-side .input-group-custom .form-control,
        .auth-form-side .input-group-custom .form-select,
        .auth-form-side .input-group-custom textarea {
            padding: 0.65rem 0.75rem 0.65rem 2.8rem;
            border: 2px solid #e8ecf1;
            border-radius: 0.75rem;
            font-size: 0.85rem;
            transition: all 0.2s;
            background: #fafbfc;
            width: 100%;
        }

        .auth-form-side .input-group-custom .form-control:focus,
        .auth-form-side .input-group-custom .form-select:focus,
        .auth-form-side .input-group-custom textarea:focus {
            border-color: #1da853;
            box-shadow: 0 0 0 4px rgba(29, 168, 83, 0.1);
            background: #fff;
        }

        .auth-form-side .input-group-custom .form-control.is-invalid,
        .auth-form-side .input-group-custom .form-select.is-invalid,
        .auth-form-side .input-group-custom textarea.is-invalid {
            border-color: #ea5455;
            box-shadow: 0 0 0 4px rgba(234, 84, 85, 0.1);
        }

        .auth-form-side .input-group-custom textarea {
            padding-top: 0.65rem;
            min-height: 60px;
            resize: vertical;
        }

        .auth-form-side .input-group-custom .form-select {
            padding-right: 2.5rem;
            appearance: auto;
        }

        .auth-form-side .input-group-custom .input-icon-textarea {
            top: 16px;
            transform: none;
        }

        .auth-form-side .row-custom {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }

        .auth-form-side .btn-register {
            background: linear-gradient(135deg, #1da853 0%, #1a8f47 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 0.75rem;
            transition: all 0.2s;
            color: #fff;
            width: 100%;
            margin-top: 0.5rem;
        }

        .auth-form-side .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(29, 168, 83, 0.35);
        }

        .auth-form-side .btn-register:active {
            transform: translateY(0);
        }

        .auth-form-side .auth-footer {
            margin-top: 1.25rem;
            text-align: center;
            font-size: 0.85rem;
            color: #8a93a3;
        }

        .auth-form-side .auth-footer a {
            color: #1da853;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s;
        }

        .auth-form-side .auth-footer a:hover {
            color: #1a8f47;
            text-decoration: underline;
        }

        .alert {
            border-radius: 0.75rem;
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
        }

        .alert-success {
            background: #e7f7ed;
            border-color: #1da853;
            color: #0a7344;
        }

        .alert-danger {
            background: #fde8e8;
            border-color: #ea5455;
            color: #842029;
        }

        .invalid-feedback {
            font-size: 0.75rem;
            color: #ea5455;
            margin-top: 0.25rem;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .auth-brand-side {
                flex: 0 0 100%;
                padding: 1.5rem;
                min-height: 220px;
            }

            .auth-brand-side .brand-content h1 {
                font-size: 1.8rem;
            }

            .auth-brand-side .brand-content p {
                max-width: 100%;
            }

            .auth-brand-side .brand-features {
                display: none;
            }

            .auth-form-side {
                flex: 0 0 100%;
                padding: 1.5rem;
                max-height: none;
                overflow-y: visible;
            }

            .auth-card {
                margin: 0;
                min-height: auto;
            }

            .auth-form-side .row-custom {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .auth-brand-side .brand-content h1 {
                font-size: 1.4rem;
            }

            .auth-brand-side .brand-logo .logo-text {
                font-size: 1rem;
            }

            .auth-form-side .form-header h2 {
                font-size: 1.2rem;
            }

            .auth-form-side {
                padding: 1.25rem;
            }

            body {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <!-- Left Side - Brand & Visual -->
        <div class="auth-brand-side">
            <div class="brand-top">
                <div class="brand-logo">
                    <div class="logo-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <div class="logo-text">S I P <span>A D U</span></div>
                        <span class="logo-sub">Sistem Pelatihan Digital</span>
                    </div>
                </div>
            </div>

            <div class="brand-content">
                <h1>Mulai <br>Perjalanan <span class="highlight">Belajar</span></h1>
                <p>Daftar sekarang untuk mengakses berbagai pelatihan dan sertifikasi digital.</p>
                <div class="brand-features">
                    <div class="feature-item">
                        <i class="bi bi-shield-check"></i>
                        <span>Koneksi Aman & Terenkripsi</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-mortarboard-fill"></i>
                        <span>Akses ke semua pelatihan</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-award"></i>
                        <span>Dapatkan sertifikat resmi</span>
                    </div>
                </div>
            </div>

            <div class="brand-footer">
                <span class="version">v1.0.0 &bull; SIPADU Platform</span>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="auth-form-side">
            <div class="form-header">
                <p class="eyebrow">Daftar Akun</p>
                <h2>Buat Akun Baru</h2>
                <p>Isi data diri Anda untuk mendaftar.</p>
            </div>

            <!-- Session Status -->
            @if(session('status'))
                <div class="alert alert-success mb-3">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    Terdapat kesalahan pada form di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row-custom">
                    <!-- NIK -->
                    <div class="input-group-custom">
                        <span class="input-icon"><i class="bi bi-card-text"></i></span>
                        <input type="text" 
                               class="form-control @error('nik') is-invalid @enderror" 
                               id="nik" 
                               name="nik" 
                               value="{{ old('nik') }}" 
                               placeholder="NIK" 
                               required>
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="input-group-custom">
                        <span class="input-icon"><i class="bi bi-person"></i></span>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama') }}" 
                               placeholder="Nama Lengkap" 
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="input-group-custom">
                    <span class="input-icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="Email" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- No Telepon -->
                <div class="input-group-custom">
                    <span class="input-icon"><i class="bi bi-phone"></i></span>
                    <input type="text" 
                           class="form-control @error('no_telepon') is-invalid @enderror" 
                           id="no_telepon" 
                           name="no_telepon" 
                           value="{{ old('no_telepon') }}" 
                           placeholder="No. Telepon" 
                           required>
                    @error('no_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nama Usaha & NIB -->
                <div class="row-custom">
                    <div class="input-group-custom">
                        <span class="input-icon"><i class="bi bi-building"></i></span>
                        <input type="text" 
                               class="form-control @error('nama_usaha') is-invalid @enderror" 
                               id="nama_usaha" 
                               name="nama_usaha" 
                               value="{{ old('nama_usaha') }}" 
                               placeholder="Nama Usaha" 
                               required>
                        @error('nama_usaha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group-custom">
                        <span class="input-icon"><i class="bi bi-file-earmark-text"></i></span>
                        <input type="text" 
                               class="form-control @error('nib') is-invalid @enderror" 
                               id="nib" 
                               name="nib" 
                               value="{{ old('nib') }}" 
                               placeholder="NIB" 
                               required>
                        @error('nib')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Jenis Usaha & Alamat -->
                <div class="row-custom">
                    <div class="input-group-custom">
                        <span class="input-icon"><i class="bi bi-tag"></i></span>
                        <select class="form-select @error('jenis_usaha') is-invalid @enderror" 
                                id="jenis_usaha" 
                                name="jenis_usaha" 
                                required>
                            <option value="">Jenis Usaha</option>
                            <option value="formal" @selected(old('jenis_usaha') === 'formal')>Formal</option>
                            <option value="non_formal" @selected(old('jenis_usaha') === 'non_formal')>Non Formal</option>
                        </select>
                        @error('jenis_usaha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group-custom">
                        <span class="input-icon input-icon-textarea"><i class="bi bi-geo-alt"></i></span>
                        <textarea class="form-control @error('alamat_lengkap') is-invalid @enderror" 
                                  id="alamat_lengkap" 
                                  name="alamat_lengkap" 
                                  rows="1" 
                                  placeholder="Alamat Lengkap" 
                                  required>{{ old('alamat_lengkap') }}</textarea>
                        @error('alamat_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password & Confirm -->
                <div class="row-custom">
                    <div class="input-group-custom">
                        <span class="input-icon"><i class="bi bi-lock"></i></span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Password (min 8)" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group-custom">
                        <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Konfirmasi Password" 
                               required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-register">
                    <i class="bi bi-person-plus me-2"></i>
                    Daftar Sekarang
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? 
                <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>