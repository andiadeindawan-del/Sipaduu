
@extends('layouts.landing')

@section('title', 'Tentang Kami')

@section('content')

<!-- ============================================================
     ABOUT SECTION - KADIS
============================================================ -->
<section class="section-pad" style="padding: 4rem 0;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div class="position-relative">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        @php
                            $kadisPath = 'assets/images/kadis.png';
                            $kadisExists = file_exists(public_path($kadisPath));
                        @endphp
                        @if($kadisExists)
                            <img src="{{ asset($kadisPath) }}" 
                                 alt="Kepala Dinas Koperindag Sulawesi Barat" 
                                 class="img-fluid w-100" style="height: 450px; object-fit: cover; object-position: top;">
                        @else
                            <div class="bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 450px;">
                                <i class="bi bi-person fs-1 text-primary"></i>
                            </div>
                        @endif
                        <div class="position-absolute bottom-0 start-0 end-0 p-4" 
                             style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <h5 class="text-white mb-0 fw-bold">H. MASRIADI NADI ATJO, S.E., M.Si.</h5>
                            <p class="text-white-50 mb-0 small">Kepala Dinas Koperindag Provinsi Sulawesi Barat</p>
                        </div>
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="position-absolute bottom-0 start-0 translate-middle">
                        <div class="bg-success bg-opacity-10 rounded-circle" style="width: 60px; height: 60px;"></div>
                    </div>
                   
                </div>
            </div>
            <div class="col-lg-7">
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">PROFIL PIMPINAN</h2>
                    
                    <div class="text-muted" style="line-height: 1.8;">
                        <p><strong>H. Masriadi Nadi Atjo, S.E., M.Si.</strong> adalah Kepala Dinas Koperasi, Perindustrian, dan Perdagangan (Koperindag) Provinsi Sulawesi Barat. Beliau memiliki pengalaman panjang di bidang pemerintahan dan pengembangan sumber daya manusia.</p>
                        <p>Di bawah kepemimpinannya, Dinas Koperindag Provinsi Sulawesi Barat terus berkomitmen untuk meningkatkan kompetensi dan daya saing pelaku usaha di bidang koperasi, industri, dan perdagangan melalui berbagai program pelatihan dan pengembangan SDM.</p>
                        <p>Beliau merupakan sosok visioner yang selalu mendorong inovasi dan pemanfaatan teknologi dalam pengembangan sumber daya manusia, termasuk melalui platform SIPADU (Sistem Pengembangan SDM Usaha) yang menjadi andalan dalam pelatihan dan sertifikasi.</p>
                    </div>

                    <!-- Informasi Detail Pimpinan -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                <i class="bi bi-calendar text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Tempat, Tanggal Lahir</small>
                                    <span class="fw-semibold">Makassar, 15 Maret 1968</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                <i class="bi bi-mortarboard text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Pendidikan Terakhir</small>
                                    <span class="fw-semibold">S2 Magister Manajemen (M.Si)</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                <i class="bi bi-briefcase text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Jabatan</small>
                                    <span class="fw-semibold">Kepala Dinas Koperindag Sulbar</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3">
                                <i class="bi bi-clock-history text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Masa Jabatan</small>
                                    <span class="fw-semibold">2021 - Sekarang</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     VISI & MISI SECTION
============================================================ -->
<section class="section-pad" style="padding: 4rem 0; background: #f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-2">Visi & Misi Kami</h2>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                Landasan kami dalam memberikan pelayanan pengembangan SDM terbaik.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box" style="width: 56px; height: 56px; border-radius: 14px; background: #eaf1fd; color: #4e9af1; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="bi bi-eye"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Visi</h4>
                    </div>
                    <p class="text-muted fs-5 fst-italic mb-0">
                        "Menjadi pusat pengembangan sumber daya manusia usaha yang unggul dan berdaya saing 
                        di tingkat nasional melalui pelatihan berbasis teknologi."
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box" style="width: 56px; height: 56px; border-radius: 14px; background: #dff6e8; color: #28c76f; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Misi</h4>
                    </div>
                    <ul class="text-muted list-unstyled mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Menyediakan pelatihan berkualitas dan relevan</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Mengembangkan metode pembelajaran interaktif</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Meningkatkan akses pelatihan bagi semua kalangan</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Membangun kemitraan strategis dengan berbagai pihak</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     STATISTICS SECTION
============================================================ -->
<section class="section-pad" style="padding: 4rem 0;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-2">Pencapaian Kami</h2>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                Angka-angka yang menunjukkan komitmen kami dalam pengembangan SDM.
            </p>
        </div>

        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="bg-white p-4 rounded-4 shadow-sm border border-light">
                    <h3 class="text-primary fw-bold mb-0" style="font-size: 2.5rem;">
                        <span data-count="{{ $totalTrainings ?? 0 }}">0</span>+
                    </h3>
                    <p class="text-muted small mb-0">Pelatihan Tersedia</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-white p-4 rounded-4 shadow-sm border border-light">
                    <h3 class="text-success fw-bold mb-0" style="font-size: 2.5rem;">
                        <span data-count="{{ $totalParticipants ?? 0 }}">0</span>+
                    </h3>
                    <p class="text-muted small mb-0">Peserta Terdaftar</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-white p-4 rounded-4 shadow-sm border border-light">
                    <h3 class="text-warning fw-bold mb-0" style="font-size: 2.5rem;">
                        <span data-count="{{ $totalCertificates ?? 0 }}">0</span>+
                    </h3>
                    <p class="text-muted small mb-0">Sertifikat Diterbitkan</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-white p-4 rounded-4 shadow-sm border border-light">
                    <h3 class="text-info fw-bold mb-0" style="font-size: 2.5rem;">
                        <span data-count="{{ $totalInstructors ?? 0 }}">0</span>+
                    </h3>
                    <p class="text-muted small mb-0">Instruktur Profesional</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     VALUES SECTION
============================================================ -->
<section class="section-pad" style="padding: 4rem 0; background: #f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-2">Nilai-Nilai Kami</h2>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                Prinsip-prinsip yang menjadi landasan kami dalam memberikan pelayanan terbaik.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card-feature text-center p-4 bg-white rounded-4 shadow-sm border border-light h-100">
                    <div class="icon-box mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: #eaf1fd; color: #4e9af1; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="bi bi-star"></i>
                    </div>
                    <h5 class="fw-bold">Kualitas</h5>
                    <p class="small text-muted">Kami selalu mengutamakan kualitas materi dan metode pembelajaran.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-feature text-center p-4 bg-white rounded-4 shadow-sm border border-light h-100">
                    <div class="icon-box mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: #dff6e8; color: #28c76f; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5 class="fw-bold">Inklusivitas</h5>
                    <p class="small text-muted">Pelatihan dapat diakses oleh semua kalangan tanpa batasan.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-feature text-center p-4 bg-white rounded-4 shadow-sm border border-light h-100">
                    <div class="icon-box mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: #fef3e2; color: #ff9f43; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <h5 class="fw-bold">Inovasi</h5>
                    <p class="small text-muted">Terus berinovasi dalam metode dan materi pelatihan.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-feature text-center p-4 bg-white rounded-4 shadow-sm border border-light h-100">
                    <div class="icon-box mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: #fce4e4; color: #ea5455; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </div>
                    <h5 class="fw-bold">Integritas</h5>
                    <p class="small text-muted">Menjaga kepercayaan dengan memberikan pelayanan terbaik.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     TEAM SECTION - TIM PENGEMBANG
============================================================ -->
<section class="section-pad" style="padding: 4rem 0; background: #f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-2">Tim Pengembang</h2>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                Orang-orang di balik pengembangan platform SIPADU.
            </p>
        </div>

        <!-- Struktur Tim Pengembang -->
       <div class="col-12 mb-3">
                <div class="row g-3 justify-content-center">
                    <!-- Dosen Pembimbing 1 -->
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center team-card">
                            <div class="team-image-wrapper" style="height: 240px;">
                                @php
                                    $dospem1Path = 'assets/images/ade1.jpeg';
                                    $dospem1Exists = file_exists(public_path($dospem1Path));
                                @endphp
                                @if($dospem1Exists)
                                    <img src="{{ asset($dospem1Path) }}" 
                                         alt="Dosen Pembimbing 1" 
                                         class="team-image"
                                         style="object-fit: cover; object-position: center top;">
                                @else
                                    <div class="team-placeholder bg-success">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                @endif
                                <div class="team-overlay">
                                    <span class="badge bg-success" style="font-size: 0.6rem;">Mahasiswa</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">A.ADE INDAWAN</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">Mahasiswa</p>
                            </div>
                        </div>
                    </div>

            <!-- Baris 2: Dosen Pembimbing 1 & Dosen Pembimbing 2 -->
            <div class="col-12 mb-3">
                <div class="row g-3 justify-content-center">
                    <!-- Dosen Pembimbing 1 -->
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center team-card">
                            <div class="team-image-wrapper" style="height: 240px;">
                                @php
                                    $dospem1Path = 'assets/images/dospem1.png';
                                    $dospem1Exists = file_exists(public_path($dospem1Path));
                                @endphp
                                @if($dospem1Exists)
                                    <img src="{{ asset($dospem1Path) }}" 
                                         alt="Dosen Pembimbing 1" 
                                         class="team-image"
                                         style="object-fit: cover; object-position: center top;">
                                @else
                                    <div class="team-placeholder bg-success">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                @endif
                                <div class="team-overlay">
                                    <span class="badge bg-success" style="font-size: 0.6rem;">Dosen Pembimbing 1</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">Dr. Hj. A.ADE INDAWAN, S.Kom., MT</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">Dosen Pembimbing 1</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dosen Pembimbing 2 -->
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center team-card">
                            <div class="team-image-wrapper" style="height: 240px;">
                                @php
                                    $dospem2Path = 'assets/images/dospem2.png';
                                    $dospem2Exists = file_exists(public_path($dospem2Path));
                                @endphp
                                @if($dospem2Exists)
                                    <img src="{{ asset($dospem2Path) }}" 
                                         alt="Dosen Pembimbing 2" 
                                         class="team-image"
                                         style="object-fit: cover; object-position: center top;">
                                @else
                                    <div class="team-placeholder bg-warning">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                @endif
                                <div class="team-overlay">
                                    <span class="badge bg-warning" style="font-size: 0.6rem;">Dosen Pembimbing 2</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">Dr. Hj. A.ADE INDAWAN, S.Kom., MT</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">Dosen Pembimbing 2</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baris 3: Pembimbing Lapangan -->
            <div class="col-12 text-center">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center h-100 team-card">
                            <div class="team-image-wrapper" style="height: 240px;">
                                @php
                                    $pembimbingLapPath = 'assets/images/pembimbing-lapangan.png';
                                    $pembimbingLapExists = file_exists(public_path($pembimbingLapPath));
                                @endphp
                                @if($pembimbingLapExists)
                                    <img src="{{ asset($pembimbingLapPath) }}" 
                                         alt="Pembimbing Lapangan" 
                                         class="team-image"
                                         style="object-fit: cover; object-position: center top;">
                                @else
                                    <div class="team-placeholder bg-info">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                @endif
                                <div class="team-overlay">
                                    <span class="badge bg-info" style="font-size: 0.6rem;">Pembimbing Lapangan</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">Dr. Hj. A.ADE INDAWAN, S.Kom., MT</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">Pembimbing Lapangan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     CTA SECTION
============================================================ -->
<section class="section-pad" style="padding: 0 0 4rem 0;">
    <div class="container">
        <div class="cta-section p-5 text-center" style="background: linear-gradient(135deg, #1a2236, #2a3654); color: #fff; border-radius: 1.5rem;">
            <h2 class="display-5 fw-bold mb-3">Siap Bergabung dengan Kami?</h2>
            <p class="mx-auto mb-4" style="max-width: 600px; opacity: 0.8;">
                Mulai perjalanan pengembangan kompetensi Anda bersama platform pelatihan terbaik.
            </p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('landing.pelatihan.index') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-journal-bookmark me-2"></i> Lihat Pelatihan
                </a>
                <a href="{{ route('landing.kontak.index') }}" class="btn btn-outline-light btn-lg px-5">
                    <i class="bi bi-envelope me-2"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    /* ============================================================
       HERO SECTION
    ============================================================ */
    .hero {
        background: linear-gradient(135deg, #0f1724 0%, #1a2236 50%, #0f1724 100%);
        color: #fff;
        position: relative;
    }
    
    .hero .badge {
        font-weight: 600;
        letter-spacing: 0.02em;
        background-color: rgba(78, 154, 241, 0.15) !important;
        color: #4e9af1 !important;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        border: 1px solid rgba(78, 154, 241, 0.2);
    }
    
    .hero .display-4 {
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    /* ============================================================
       ICON BOX
    ============================================================ */
    .icon-box {
        transition: transform 0.3s ease;
    }
    
    .icon-box:hover {
        transform: scale(1.05);
    }

    /* ============================================================
       CARD FEATURE
    ============================================================ */
    .card-feature {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .card-feature:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    }

    /* ============================================================
       TEAM CARD
    ============================================================ */
    .team-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12) !important;
    }

    .leader-card {
        border: 2px solid #4e9af1 !important;
        position: relative;
    }
    .leader-card::before {
        content: '👑';
        position: absolute;
        top: -12px;
        right: -12px;
        font-size: 1.5rem;
        z-index: 2;
    }

    .team-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 200px;
        background: #f8fafc;
    }

    .team-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top;
        transition: transform 0.5s ease;
    }

    .team-card:hover .team-image {
        transform: scale(1.05);
    }

    .team-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: rgba(255,255,255,0.7);
    }

    .team-overlay {
        position: absolute;
        top: 12px;
        right: 12px;
    }

    .team-overlay .badge {
        font-weight: 500;
        padding: 0.35rem 0.7rem;
        font-size: 0.65rem;
        letter-spacing: 0.5px;
        border-radius: 6px;
    }

    .social-links {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .social-links a {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.8rem;
    }

    .social-links a:hover {
        background: #4e9af1;
        color: #fff !important;
        transform: translateY(-2px);
    }

    /* ============================================================
       CTA SECTION
    ============================================================ */
    .cta-section {
        background: linear-gradient(135deg, #1a2236, #2a3654);
        color: #fff;
        border-radius: 1.5rem;
    }
    
    .cta-section .btn-primary {
        background: linear-gradient(135deg, #4e9af1, #3b7dd8);
        border: none;
        box-shadow: 0 4px 15px rgba(78, 154, 241, 0.4);
    }
    
    .cta-section .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(78, 154, 241, 0.5);
    }
    
    .cta-section .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .cta-section .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .hero {
            padding: 2rem 0 !important;
        }
        
        .hero .display-4 {
            font-size: 2rem;
        }
        
        .section-pad {
            padding: 2rem 0 !important;
        }
        
        .display-5 {
            font-size: 1.8rem !important;
        }
        
        .cta-section {
            padding: 2rem 1.5rem !important;
        }
        
        .cta-section .btn-lg {
            padding: 0.5rem 1.5rem !important;
            font-size: 0.95rem !important;
        }
        
        .leader-card::before {
            font-size: 1.2rem;
            top: -8px;
            right: -8px;
        }
        
        .team-image-wrapper {
            height: 150px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // COUNTER ANIMATION
    // ============================================================
    const counters = document.querySelectorAll('[data-count]');
    
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-count'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const update = () => {
            current += step;
            if (current < target) {
                element.textContent = Math.floor(current);
                requestAnimationFrame(update);
            } else {
                element.textContent = target;
            }
        };
        
        update();
    }

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    } else {
        counters.forEach(counter => animateCounter(counter));
    }
});
</script>
@endpush
@endsection