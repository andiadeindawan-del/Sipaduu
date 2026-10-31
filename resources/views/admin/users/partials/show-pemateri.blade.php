@php
    $trainings = $user->trainingDiajar ?? collect([]);
    $totalTrainings = $trainings->count();
    $totalMateris = $trainings->flatMap->materis->count();
    $totalQuizzes = $trainings->flatMap->quizzes->count();
    $totalDokumentasi = $trainings->flatMap->dokumentasis->count();
    $totalPeserta = $trainings->flatMap->registrations->count();

    // Format WA
    $cleanPhone = preg_replace('/[^0-9]/', '', (string)$user->no_telepon);
    if (str_starts_with($cleanPhone, '0')) {
        $cleanPhone = '62' . substr($cleanPhone, 1);
    }
@endphp

<!-- ============================================================ -->
<!-- TOP STATS METRIC GRID PEMATERI -->
<!-- ============================================================ -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card border-0 shadow-sm" style="border-left: 4px solid #6f42c1 !important;">
            <div class="metric-top">
                <span class="metric-label" style="color: #6f42c1;">Pelatihan Diajar</span>
                <span class="metric-icon" style="color: #6f42c1;"><i class="bi bi-mortarboard-fill"></i></span>
            </div>
            <div class="metric-value" style="color: #6f42c1;">{{ $totalTrainings }}</div>
            <div class="metric-meta">
                <span class="text-muted small">Pelatihan ditugaskan</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card border-0 shadow-sm" style="border-left: 4px solid #28c76f !important;">
            <div class="metric-top">
                <span class="metric-label text-success">Materi Diunggah</span>
                <span class="metric-icon text-success"><i class="bi bi-file-earmark-text-fill"></i></span>
            </div>
            <div class="metric-value text-success">{{ $totalMateris }}</div>
            <div class="metric-meta">
                <span class="text-muted small">Modul & materi belajar</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card border-0 shadow-sm" style="border-left: 4px solid #ff9f43 !important;">
            <div class="metric-top">
                <span class="metric-label text-warning">Kuis & Ujian</span>
                <span class="metric-icon text-warning"><i class="bi bi-patch-question-fill"></i></span>
            </div>
            <div class="metric-value text-warning">{{ $totalQuizzes }}</div>
            <div class="metric-meta">
                <span class="text-muted small">Evaluasi & asesmen</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card border-0 shadow-sm" style="border-left: 4px solid #00cfe8 !important;">
            <div class="metric-top">
                <span class="metric-label text-info">Dokumentasi</span>
                <span class="metric-icon text-info"><i class="bi bi-camera-fill"></i></span>
            </div>
            <div class="metric-value text-info">{{ $totalDokumentasi }}</div>
            <div class="metric-meta">
                <span class="text-muted small">Foto kegiatan pelatihan</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- ============================================================ -->
    <!-- KOLOM KIRI: PROFIL CARD & DETAIL AKUN PEMATERI -->
    <!-- ============================================================ -->
    <div class="col-12 col-lg-4">
        <!-- Card Profil Utama -->
        <div class="panel border-0 shadow-sm mb-4">
            <div class="panel-header">
                <h5 class="section-title"><i class="bi bi-person-video3" style="color: #6f42c1;"></i> Profil Pemateri</h5>
                <span class="badge" style="background-color: #6f42c1; color: #fff;">
                    Instruktur Resmi
                </span>
            </div>
            <div class="p-4 text-center">
                <!-- Avatar -->
                <div class="mb-3 position-relative d-inline-block">
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" 
                             alt="Avatar" 
                             class="rounded-circle mx-auto d-block shadow-sm" 
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #6f42c1;">
                    @else
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm" 
                             style="width: 120px; height: 120px; background: linear-gradient(135deg, #6f42c1 0%, #432874 100%); color: #fff; font-size: 44px; font-weight: 700; border: 4px solid #e8ecf1;">
                            {{ strtoupper(substr($user->nama ?? $user->name ?? 'P', 0, 2)) }}
                        </div>
                    @endif
                </div>

                <h5 class="fw-bold mb-1 text-dark">{{ $user->nama ?? $user->name }}</h5>
                <p class="text-muted small mb-3">{{ $user->email }}</p>

                <!-- Badges -->
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge shadow-sm" style="background-color: #6f42c1; color: #fff; font-size: 0.8rem; padding: 6px 14px; border-radius: 20px;">
                        <i class="bi bi-person-video3 me-1"></i> Pemateri
                    </span>
                    <span class="badge {{ $user->status === 'aktif' ? 'bg-success' : 'bg-secondary' }} px-3 py-1.5 shadow-sm" style="border-radius: 20px; font-size: 0.8rem;">
                        <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i>
                        {{ ucfirst($user->status ?? 'Aktif') }}
                    </span>
                </div>

                <!-- Informasi Akun (Sinkron dengan Profil Pemateri) -->
                <div class="mt-4 pt-3 border-top text-start">
                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                        <i class="bi bi-info-circle me-1"></i> Informasi Akun Pemateri
                    </h6>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-card-heading me-1 text-primary"></i> NIK</span>
                        <span class="fw-bold text-dark font-monospace">{{ $user->nik ?? '-' }}</span>
                    </div>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-telephone me-1 text-success"></i> No. Telepon</span>
                        <span class="fw-semibold text-dark">{{ $user->no_telepon ?? '-' }}</span>
                    </div>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-envelope me-1 text-info"></i> Email</span>
                        <span class="fw-semibold text-dark text-truncate ms-2" style="max-width: 170px;" title="{{ $user->email }}">
                            {{ $user->email }}
                        </span>
                    </div>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-building me-1 text-warning"></i> Departemen</span>
                        <span class="fw-semibold text-dark">{{ $user->departemen ?? '-' }}</span>
                    </div>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-briefcase me-1 text-secondary"></i> Jabatan</span>
                        <span class="fw-semibold text-dark">{{ $user->jabatan ?? '-' }}</span>
                    </div>

                    <div class="py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-calendar-check me-1 text-primary"></i> Bergabung</span>
                        <span class="fw-semibold text-dark">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                    </div>

                    <div class="pt-2 d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-clock me-1 text-muted"></i> Terakhir Aktif</span>
                        <span class="text-muted small">{{ $user->updated_at ? $user->updated_at->diffForHumans() : '-' }}</span>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-4 pt-3 border-top d-flex flex-column gap-2">
                    @if(!empty($cleanPhone))
                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="btn btn-success btn-sm w-100 fw-semibold">
                        <i class="bi bi-whatsapp me-1"></i> Hubungi via WhatsApp
                    </a>
                    @endif
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm w-100 fw-semibold text-dark">
                        <i class="bi bi-pencil-square me-1"></i> Edit Profil Pemateri
                    </a>
                    <a href="{{ route('admin.users.change-password.form', $user->id) }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-key me-1"></i> Reset Password
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- KOLOM KANAN: AKTIVITAS & PELATIHAN PEMATERI -->
    <!-- ============================================================ -->
    <div class="col-12 col-lg-8">
        <div class="panel border-0 shadow-sm">
            <div class="panel-header border-bottom">
                <ul class="nav nav-tabs card-header-tabs border-0" id="pemateriDetailTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold" id="tab-trainings" data-bs-toggle="tab" data-bs-target="#content-trainings" type="button" role="tab">
                            <i class="bi bi-journal-bookmark me-1" style="color: #6f42c1;"></i> 
                            Pelatihan yang Diajar 
                            <span class="badge rounded-pill ms-1" style="background-color: #6f42c1; color: #fff;">{{ $totalTrainings }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="tab-materis" data-bs-toggle="tab" data-bs-target="#content-materis" type="button" role="tab">
                            <i class="bi bi-file-earmark-text text-success me-1"></i> 
                            Materi 
                            <span class="badge bg-success rounded-pill ms-1">{{ $totalMateris }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="tab-quizzes" data-bs-toggle="tab" data-bs-target="#content-quizzes" type="button" role="tab">
                            <i class="bi bi-patch-question text-warning me-1"></i> 
                            Kuis 
                            <span class="badge bg-warning text-dark rounded-pill ms-1">{{ $totalQuizzes }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="tab-dokumentasi" data-bs-toggle="tab" data-bs-target="#content-dokumentasi" type="button" role="tab">
                            <i class="bi bi-camera text-info me-1"></i> 
                            Dokumentasi 
                            <span class="badge bg-info rounded-pill ms-1">{{ $totalDokumentasi }}</span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="p-4">
                <div class="tab-content" id="pemateriDetailTabsContent">
                    <!-- ============================================== -->
                    <!-- TAB 1: PELATIHAN YANG DIAJAR -->
                    <!-- ============================================== -->
                    <div class="tab-pane fade show active" id="content-trainings" role="tabpanel">
                        @if($trainings->count() > 0)
                            <div class="row g-3">
                                @foreach($trainings as $t)
                                <div class="col-12">
                                    <div class="card border rounded-3 p-3 shadow-none hover-shadow transition-all">
                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-2">
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark">
                                                    <a href="{{ route('admin.trainings.show', $t->id) }}" class="text-decoration-none text-dark hover-primary">
                                                        {{ $t->judul ?? $t->title ?? 'Pelatihan Tanpa Judul' }}
                                                    </a>
                                                </h6>
                                                <div class="text-muted small d-flex flex-wrap gap-3">
                                                    <span>
                                                        <i class="bi bi-calendar3 me-1 text-primary"></i>
                                                        {{ $t->tanggal_mulai ? $t->tanggal_mulai->format('d M Y') : '-' }} 
                                                        s/d 
                                                        {{ $t->tanggal_selesai ? $t->tanggal_selesai->format('d M Y') : '-' }}
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-geo-alt me-1 text-danger"></i>
                                                        {{ $t->lokasi ?? ucfirst($t->tipe ?? 'Online') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div>
                                                @php
                                                    $statusBadge = match(strtolower($t->status ?? '')) {
                                                        'published', 'aktif' => 'bg-success',
                                                        'berjalan' => 'bg-primary',
                                                        'selesai' => 'bg-info',
                                                        'draf', 'draft' => 'bg-secondary',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusBadge }} px-3 py-1.5" style="border-radius: 20px;">
                                                    <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                                    {{ ucfirst($t->status) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap justify-content-between align-items-center pt-2 mt-2 border-top">
                                            <div class="d-flex gap-3 text-muted small">
                                                <span><i class="bi bi-file-earmark-text text-success me-1"></i><strong>{{ $t->materis ? $t->materis->count() : 0 }}</strong> Materi</span>
                                                <span><i class="bi bi-patch-question text-warning me-1"></i><strong>{{ $t->quizzes ? $t->quizzes->count() : 0 }}</strong> Kuis</span>
                                                <span><i class="bi bi-people text-primary me-1"></i><strong>{{ $t->registrations ? $t->registrations->count() : 0 }}</strong> Peserta</span>
                                            </div>
                                            <a href="{{ route('admin.trainings.show', $t->id) }}" class="btn btn-outline-primary btn-sm">
                                                Lihat Pelatihan <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-journal-x fs-1 text-muted d-block mb-3"></i>
                                <h6 class="fw-bold">Belum Ada Pelatihan yang Ditugaskan</h6>
                                <p class="text-muted small mb-3">Pemateri ini belum ditugaskan untuk mengajar pelatihan apapun.</p>
                                <a href="{{ route('admin.trainings.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Buat Pelatihan & Tugaskan Pemateri
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 2: MATERI PELATIHAN -->
                    <!-- ============================================== -->
                    <div class="tab-pane fade" id="content-materis" role="tabpanel">
                        @php
                            $allMateris = $trainings->flatMap->materis;
                        @endphp
                        @if($allMateris->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Judul Materi</th>
                                            <th>Pelatihan</th>
                                            <th>Tipe</th>
                                            <th>Diunggah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allMateris as $m)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-dark">
                                                    <i class="bi bi-file-earmark-text text-success me-2"></i>
                                                    {{ $m->judul ?? $m->title ?? 'Materi' }}
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $m->training->judul ?? $m->training->title ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary">{{ strtoupper($m->tipe ?? 'Dokumen') }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $m->created_at ? $m->created_at->format('d/m/Y') : '-' }}</small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-file-earmark-x fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted small mb-0">Belum ada materi pelatihan yang diunggah oleh pemateri ini.</p>
                            </div>
                        @endif
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 3: KUIS & EVALUASI -->
                    <!-- ============================================== -->
                    <div class="tab-pane fade" id="content-quizzes" role="tabpanel">
                        @php
                            $allQuizzes = $trainings->flatMap->quizzes;
                        @endphp
                        @if($allQuizzes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Judul Kuis</th>
                                            <th>Pelatihan</th>
                                            <th>Jumlah Soal</th>
                                            <th>Passing Score</th>
                                            <th>Durasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allQuizzes as $q)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-dark">
                                                    <i class="bi bi-patch-question text-warning me-2"></i>
                                                    {{ $q->judul ?? $q->title ?? 'Kuis' }}
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $q->training->judul ?? $q->training->title ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ $q->questions ? $q->questions->count() : ($q->total_questions ?? 0) }} Soal</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success">{{ $q->passing_score ?? 70 }}%</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $q->durasi_menit ?? $q->duration ?? '-' }} Menit</small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-patch-question fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted small mb-0">Belum ada kuis atau evaluasi yang dibuat untuk pelatihan ini.</p>
                            </div>
                        @endif
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 4: DOKUMENTASI KEGIATAN -->
                    <!-- ============================================== -->
                    <div class="tab-pane fade" id="content-dokumentasi" role="tabpanel">
                        @php
                            $allDokumentasi = $trainings->flatMap->dokumentasis;
                        @endphp
                        @if($allDokumentasi->count() > 0)
                            <div class="row g-3">
                                @foreach($allDokumentasi as $dok)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card border rounded-3 overflow-hidden shadow-none">
                                        @if($dok->foto)
                                        <img src="{{ asset('storage/' . $dok->foto) }}" 
                                             class="card-img-top" 
                                             alt="Dokumentasi" 
                                             style="height: 160px; object-fit: cover;">
                                        @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 160px;">
                                            <i class="bi bi-image fs-1 text-muted"></i>
                                        </div>
                                        @endif
                                        <div class="p-3">
                                            <h6 class="fw-semibold text-truncate mb-1">{{ $dok->judul ?? 'Foto Kegiatan' }}</h6>
                                            <p class="text-muted small mb-0">{{ $dok->created_at ? $dok->created_at->format('d M Y') : '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-camera fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted small mb-0">Belum ada foto dokumentasi kegiatan yang diunggah.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
