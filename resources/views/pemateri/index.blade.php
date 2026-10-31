@extends('layouts.pemateri')

@section('title', 'Dashboard Pemateri')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- ========================================================== -->
    <!-- PAGE HEADING -->
    <!-- ========================================================== -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center rounded-3" style="width: 50px; height: 50px;">
                <i class="bi bi-grid-1x2 fs-3"></i>
            </div>
            <div>
                <h1 class="h3 mb-0">Dashboard</h1>
                <p class="text-muted small mb-0">Selamat datang kembali, {{ auth()->user()->nama ?? auth()->user()->name ?? 'Pemateri' }}!</p>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- STATISTIK KARTU -->
    <!-- ========================================================== -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-2">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark-fill"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings }}</div>
                <div class="metric-meta">
                    <span class="text-warning"><i class="bi bi-check2"></i> Pelatihan saya</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Materi</span>
                    <span class="metric-icon"><i class="bi bi-file-earmark-text-fill"></i></span>
                </div>
                <div class="metric-value">{{ $totalMateris }}</div>
                <div class="metric-meta">
                    <span class="text-primary"><i class="bi bi-cloud-arrow-up"></i> Dokumen &amp; file</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2">
            <div class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Kuis</span>
                    <span class="metric-icon"><i class="bi bi-question-circle-fill"></i></span>
                </div>
                <div class="metric-value">{{ $totalQuizzes }}</div>
                <div class="metric-meta">
                    <span class="text-danger"><i class="bi bi-check-circle"></i> Evaluasi</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Survey</span>
                    <span class="metric-icon" style="color: #00cfe8;"><i class="bi bi-ui-radios"></i></span>
                </div>
                <div class="metric-value">{{ $totalSurveys ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info"><i class="bi bi-chat-left-dots"></i> Kuesioner evaluasi</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Dokumentasi</span>
                    <span class="metric-icon" style="color: #28c76f;"><i class="bi bi-images"></i></span>
                </div>
                <div class="metric-value">{{ $totalDokumentasis ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success"><i class="bi bi-link-45deg"></i> Arsip link</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2">
            <div class="metric-card metric-secondary">
                <div class="metric-top">
                    <span class="metric-label">Respon Kuis</span>
                    <span class="metric-icon"><i class="bi bi-people-fill"></i></span>
                </div>
                <div class="metric-value">{{ $totalAttempts ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-secondary"><i class="bi bi-check2-circle"></i> Pengerjaan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- PELATIHAN TERSEDIA (TABEL) -->
    <!-- ========================================================== -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title">
                            <i class="bi bi-journal-bookmark text-warning"></i>
                            Pelatihan yang Tersedia
                            @if(isset($trainings) && $trainings->count() > 0)
                            <span class="badge bg-primary ms-2">{{ $trainings->count() }}</span>
                            @endif
                        </h5>
                        <p class="text-muted small mb-0">Pilih pelatihan untuk diisi materi &amp; kuis</p>
                    </div>
                    <a href="{{ route('pemateri.trainings.index') }}" class="btn btn-sm btn-outline-primary">
                        Kelola Semua <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    @if(isset($trainings) && $trainings->count() > 0)
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pelatihan</th>
                                <th>Kategori</th>
                                <th>Materi</th>
                                <th>Kuis</th>
                                <th>Survey</th>
                                <th>Dokumentasi</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trainings as $index => $training)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($training->gambar)
                                            <img class="avatar-img avatar-sm" src="{{ asset('storage/' . $training->gambar) }}" alt="{{ $training->judul }}">
                                        @else
                                            <div class="avatar-text avatar-sm bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                                                <i class="bi bi-journal-text"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="fw-semibold mb-0">{{ $training->judul }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge text-bg-light">{{ $training->kategori->nama ?? 'Umum' }}</span>
                                </td>
                                <td>
                                    <span class="text-muted"><i class="bi bi-file-earmark-text text-danger me-1"></i>{{ $training->materis_count }}</span>
                                </td>
                                <td>
                                    <span class="text-muted"><i class="bi bi-question-circle text-warning me-1"></i>{{ $training->quizzes_count }}</span>
                                </td>
                                <td>
                                    <span class="text-muted"><i class="bi bi-ui-radios text-info me-1"></i>{{ $training->surveys_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="text-muted"><i class="bi bi-images text-success me-1"></i>{{ $training->dokumentasis_count ?? 0 }}</span>
                                </td>
                                <td>
                                    @if($training->isFinished())
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-check-circle-fill me-1" style="font-size: 8px;"></i> Selesai
                                        </span>
                                    @else
                                        <span class="badge {{ $training->status == 'aktif' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> {{ ucfirst($training->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($training->isFinished())
                                        <a href="{{ route('pemateri.trainings.show', $training) }}" class="btn btn-sm btn-outline-secondary" title="Lihat Riwayat &amp; Hasil">
                                            <i class="bi bi-eye"></i> Riwayat
                                        </a>
                                    @else
                                        <a href="{{ route('pemateri.trainings.show', $training) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-box-arrow-in-right"></i> Pilih
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-secondary d-block mb-3"></i>
                        <h6 class="text-dark fw-bold mb-1">Belum Ada Pelatihan yang Ditugaskan</h6>
                        <p class="text-muted small mb-0">Admin belum menugaskan pelatihan apapun ke akun Anda. Begitu ditugaskan, pelatihan akan otomatis tampil di sini.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- MATERI TERBARU & KUIS TERBARU -->
    <!-- ========================================================== -->
    <div class="row g-3 mb-4">
        <!-- Materi Terbaru -->
        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <div class="panel-header d-flex align-items-center justify-content-between py-2 px-3">
                    <div>
                        <h6 class="section-title mb-0">
                            <i class="bi bi-file-earmark-text me-1"></i> Materi Terbaru
                        </h6>
                    </div>
                    <a href="{{ route('pemateri.materi.index') }}" class="text-primary small text-decoration-none">
                        Kelola <i class="bi bi-chevron-right small"></i>
                    </a>
                </div>
                <div class="p-2 activity-list" style="max-height: 280px; overflow-y: auto;">
                    @if($recentMateris->isEmpty())
                        <div class="text-center py-3">
                            <i class="bi bi-file-earmark-x text-muted d-block mb-1" style="font-size: 1.5rem;"></i>
                            <p class="text-muted small mb-0">Belum ada materi yang diupload</p>
                        </div>
                    @else
                        @foreach($recentMateris as $materi)
                        <div class="d-flex gap-2 align-items-center py-2 px-2 border-bottom">
                            <div class="flex-shrink-0">
                                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <p class="fw-semibold mb-0 small text-truncate">{{ $materi->judul }}</p>
                                <p class="text-muted small mb-0 text-truncate">
                                    <i class="bi bi-journal-text me-1"></i>{{ $materi->training->judul ?? '-' }}
                                    @if($materi->training && $materi->training->isFinished())
                                        <span class="badge bg-secondary ms-1">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1">{{ ucfirst($materi->status) }}</span>
                                    @endif
                                </p>
                            </div>
                            @if(!($materi->training && $materi->training->isFinished()))
                                <a href="{{ route('pemateri.materi.edit', $materi) }}" class="btn btn-sm btn-light flex-shrink-0" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @else
                                <a href="{{ route('pemateri.materi.show', $materi) }}" class="btn btn-sm btn-light flex-shrink-0" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Kuis Terbaru -->
        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <div class="panel-header d-flex align-items-center justify-content-between py-2 px-3">
                    <div>
                        <h6 class="section-title mb-0">
                            <i class="bi bi-question-circle me-1"></i> Kuis Terbaru
                        </h6>
                    </div>
                    <a href="{{ route('pemateri.quiz.index') }}" class="text-primary small text-decoration-none">
                        Kelola <i class="bi bi-chevron-right small"></i>
                    </a>
                </div>
                <div class="p-2 activity-list" style="max-height: 280px; overflow-y: auto;">
                    @if($recentQuizzes->isEmpty())
                        <div class="text-center py-3">
                            <i class="bi bi-question-square text-muted d-block mb-1" style="font-size: 1.5rem;"></i>
                            <p class="text-muted small mb-0">Belum ada kuis yang dibuat</p>
                        </div>
                    @else
                        @foreach($recentQuizzes as $quiz)
                        <div class="d-flex gap-2 align-items-center py-2 px-2 border-bottom">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <i class="bi bi-question-circle"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <p class="fw-semibold mb-0 small text-truncate">{{ $quiz->judul }}</p>
                                <p class="text-muted small mb-0 text-truncate">
                                    <i class="bi bi-journal-text me-1"></i>{{ $quiz->training->judul ?? '-' }}
                                    @if($quiz->training && $quiz->training->isFinished())
                                        <span class="badge bg-secondary ms-1">Selesai</span>
                                    @endif
                                    <span class="badge bg-info bg-opacity-10 text-info ms-1">{{ $quiz->questions_count }} Soal</span>
                                    <span class="badge bg-success bg-opacity-10 text-success ms-1">{{ $quiz->attempts_count ?? 0 }} Mengisi</span>
                                </p>
                            </div>
                            <div class="d-flex gap-1 flex-shrink-0">
                                <a href="{{ route('pemateri.quiz.show', $quiz) }}" class="btn btn-sm btn-light" title="Lihat Soal &amp; Hasil">
                                    <i class="bi bi-ui-checks"></i>
                                </a>
                                @if(!($quiz->training && $quiz->training->isFinished()))
                                    <a href="{{ route('pemateri.quiz.edit', $quiz) }}" class="btn btn-sm btn-light" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- SURVEY TERBARU & DOKUMENTASI TERBARU -->
    <!-- ========================================================== -->
    <div class="row g-3 mb-4">
        <!-- Survey Terbaru -->
        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <div class="panel-header d-flex align-items-center justify-content-between py-2 px-3">
                    <div>
                        <h6 class="section-title mb-0">
                            <i class="bi bi-ui-radios me-1" style="color: #00cfe8;"></i> Survey Terbaru
                        </h6>
                    </div>
                    <a href="{{ route('pemateri.survey.index') }}" class="text-primary small text-decoration-none">
                        Kelola <i class="bi bi-chevron-right small"></i>
                    </a>
                </div>
                <div class="p-2 activity-list" style="max-height: 280px; overflow-y: auto;">
                    @if($recentSurveys->isEmpty())
                        <div class="text-center py-3">
                            <i class="bi bi-ui-radios text-muted d-block mb-1" style="font-size: 1.5rem;"></i>
                            <p class="text-muted small mb-0">Belum ada survey yang dibuat</p>
                        </div>
                    @else
                        @foreach($recentSurveys as $survey)
                        <div class="d-flex gap-2 align-items-center py-2 px-2 border-bottom">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <i class="bi bi-ui-radios"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <p class="fw-semibold mb-0 small text-truncate">{{ $survey->judul }}</p>
                                <p class="text-muted small mb-0 text-truncate">
                                    <i class="bi bi-journal-text me-1"></i>{{ $survey->training->judul ?? '-' }}
                                    <span class="badge bg-primary bg-opacity-10 text-primary ms-1">{{ $survey->questions_count }} Pertanyaan</span>
                                    <span class="badge bg-success bg-opacity-10 text-success ms-1">{{ $survey->responses_count }} Respon</span>
                                </p>
                            </div>
                            <div class="d-flex gap-1 flex-shrink-0">
                                <a href="{{ route('pemateri.survey.show', $survey) }}" class="btn btn-sm btn-light" title="Detail Survey">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('pemateri.survey.edit', $survey) }}" class="btn btn-sm btn-light" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Dokumentasi Terbaru -->
        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <div class="panel-header d-flex align-items-center justify-content-between py-2 px-3">
                    <div>
                        <h6 class="section-title mb-0">
                            <i class="bi bi-images me-1" style="color: #28c76f;"></i> Dokumentasi Terbaru
                        </h6>
                    </div>
                    <a href="{{ route('pemateri.dokumentasi.index') }}" class="text-primary small text-decoration-none">
                        Kelola <i class="bi bi-chevron-right small"></i>
                    </a>
                </div>
                <div class="p-2 activity-list" style="max-height: 280px; overflow-y: auto;">
                    @if($recentDokumentasis->isEmpty())
                        <div class="text-center py-3">
                            <i class="bi bi-images text-muted d-block mb-1" style="font-size: 1.5rem;"></i>
                            <p class="text-muted small mb-0">Belum ada dokumentasi yang diunggah</p>
                        </div>
                    @else
                        @foreach($recentDokumentasis as $doc)
                        <div class="d-flex gap-2 align-items-center py-2 px-2 border-bottom">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <i class="bi bi-images"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <p class="fw-semibold mb-0 small text-truncate">{{ $doc->judul }}</p>
                                <p class="text-muted small mb-0 text-truncate">
                                    <i class="bi bi-journal-text me-1"></i>{{ $doc->training->judul ?? '-' }}
                                </p>
                            </div>
                            <div class="d-flex gap-1 flex-shrink-0">
                                <a href="{{ $doc->link }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Buka Link">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                                <a href="{{ route('pemateri.dokumentasi.edit', $doc) }}" class="btn btn-sm btn-light" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- QUICK ACTION & RINGKASAN -->
    <!-- ========================================================== -->
    <div class="row g-4 mb-4">
        <!-- Quick Action -->
        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title">
                            <i class="bi bi-lightning-charge"></i> Aksi Cepat
                        </h5>
                        <p class="text-muted small mb-0">Kelola aktivitas pelatihan Anda</p>
                    </div>
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <a href="{{ route('pemateri.trainings.index') }}" class="btn btn-outline-primary w-100 py-2">
                                <i class="bi bi-search me-1"></i> Pilih Pelatihan
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('pemateri.materi.create') }}" class="btn btn-outline-danger w-100 py-2">
                                <i class="bi bi-upload me-1"></i> Upload Materi
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('pemateri.quiz.create') }}" class="btn btn-outline-warning text-dark w-100 py-2">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Kuis
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('pemateri.survey.create') }}" class="btn btn-outline-info w-100 py-2">
                                <i class="bi bi-ui-radios me-1"></i> Buat Survey
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('pemateri.dokumentasi.create') }}" class="btn btn-outline-success w-100 py-2">
                                <i class="bi bi-images me-1"></i> Tambah Dokumentasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Hari Ini -->
        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h5 class="section-title">
                            <i class="bi bi-calendar-day"></i> Ringkasan Total Aktivitas
                        </h5>
                        <p class="text-muted small mb-0">{{ now()->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="text-center p-3 border rounded bg-light">
                                <h6 class="text-muted small">Pelatihan</h6>
                                <h3 class="mb-0 text-dark">{{ $totalTrainings }}</h3>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 border rounded bg-light">
                                <h6 class="text-muted small">Materi</h6>
                                <h3 class="mb-0 text-danger">{{ $totalMateris }}</h3>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 border rounded bg-light">
                                <h6 class="text-muted small">Kuis</h6>
                                <h3 class="mb-0 text-warning">{{ $totalQuizzes }}</h3>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 border rounded bg-light">
                                <h6 class="text-muted small">Survey</h6>
                                <h3 class="mb-0 text-info">{{ $totalSurveys ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 border rounded bg-light">
                                <h6 class="text-muted small">Dokumentasi</h6>
                                <h3 class="mb-0 text-success">{{ $totalDokumentasis ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 border rounded bg-light">
                                <h6 class="text-muted small">Pengisian</h6>
                                <h3 class="mb-0 text-primary">{{ $totalAttempts }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- PENGERJAAN KUIS TERBARU OLEH PESERTA -->
    <!-- ========================================================== -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="section-title">
                            <i class="bi bi-people-fill text-success"></i> Aktivitas Pengisian Kuis oleh Peserta
                        </h5>
                        <p class="text-muted small mb-0">Peserta yang baru saja mengerjakan dan menyelesaikan kuis evaluasi</p>
                    </div>
                    <a href="{{ route('pemateri.quiz.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua Kuis <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    @if(isset($recentAttempts) && $recentAttempts->count() > 0)
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Peserta</th>
                                    <th>Kuis</th>
                                    <th>Nilai</th>
                                    <th>Status Kelulusan</th>
                                    <th>Waktu Selesai</th>
                                    <th class="text-end">Rincian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttempts as $index => $attempt)
                                    @php
                                        $passingScore = $attempt->quiz->passing_score ?? 70;
                                        $isPassed = ($attempt->score >= $passingScore);
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                                    {{ strtoupper(substr($attempt->user->nama ?? $attempt->user->name ?? 'P', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="fw-semibold text-dark">{{ $attempt->user->nama ?? $attempt->user->name ?? 'Peserta' }}</span>
                                                    @if(!empty($attempt->user->nama_usaha))
                                                        <small class="text-muted d-block">{{ $attempt->user->nama_usaha }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $attempt->quiz->judul ?? '-' }}</span>
                                            @if($attempt->quiz && $attempt->quiz->training)
                                                <small class="text-muted d-block">{{ $attempt->quiz->training->judul }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attempt->status === 'completed')
                                                <span class="fw-bold fs-6 {{ $isPassed ? 'text-success' : 'text-danger' }}">
                                                    {{ $attempt->score }}%
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attempt->status === 'completed')
                                                @if($isPassed)
                                                    <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Lulus</span>
                                                @else
                                                    <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i> Belum Lulus</span>
                                                @endif
                                            @else
                                                <span class="badge bg-warning text-dark">{{ $attempt->status_label }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-dark d-block">
                                                {{ $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i') : ($attempt->started_at ? $attempt->started_at->format('d/m/Y H:i') : '-') }}
                                            </small>
                                            <small class="text-muted">Durasi: {{ $attempt->formatted_duration ?? '-' }}</small>
                                        </td>
                                        <td class="text-end">
                                            @if($attempt->quiz)
                                                <a href="{{ route('pemateri.quiz.attempt.show', [$attempt->quiz, $attempt]) }}" class="btn btn-outline-primary btn-sm" title="Lihat lembar jawaban peserta">
                                                    <i class="bi bi-eye me-1"></i> Jawaban
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-clipboard-x fs-2 d-block mb-2 text-secondary"></i>
                            <p class="mb-0">Belum ada aktivitas pengerjaan kuis dari peserta.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .metric-card {
        background: #fff;
        border-radius: 0.75rem;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border-left: 4px solid transparent;
        height: 100%;
        transition: transform 0.2s ease;
    }
    .metric-card:hover { transform: translateY(-4px); }
    .metric-primary { border-left-color: #4e9af1; }
    .metric-success { border-left-color: #28c76f; }
    .metric-warning { border-left-color: #ff9f43; }
    .metric-danger  { border-left-color: #ea5455; }
    .metric-info    { border-left-color: #17a2b8; }

    .metric-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: .4rem;
    }
    .metric-label {
        font-size: .75rem;
        color: #8a93a3;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .metric-icon { color: #c3cad6; font-size: 1.3rem; }
    .metric-value { font-size: 1.5rem; font-weight: 700; color: #1a2236; }
    .metric-meta { font-size: .75rem; color: #8a93a3; display: flex; gap: .35rem; }

    .panel {
        background: #fff;
        border-radius: .75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0;
        font-size: 1rem;
    }
    .section-title i { color: #4e9af1; }

    .chart-bars {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 10px;
        height: 200px;
        padding: 10px 0;
    }
    .chart-column {
        flex: 1;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        height: 100%;
    }
    .chart-column span {
        display: block;
        width: 100%;
        max-width: 40px;
        background: #4e9af1;
        border-radius: 6px 6px 0 0;
        min-height: 10px;
        transition: height 0.6s ease;
    }
    .chart-column small {
        margin-top: 8px;
        font-size: 0.7rem;
        color: #8a93a3;
    }
    .bar-42 span { height: 42%; background: #4e9af1; }
    .bar-58 span { height: 58%; background: #28c76f; }
    .bar-72 span { height: 72%; background: #ff9f43; }
    .bar-83 span { height: 83%; background: #17a2b8; }

    .avatar-text {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: .8rem;
        color: #fff;
    }
    .avatar-img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }

    .activity-list .border-bottom:last-child {
        border-bottom: none !important;
    }

    .btn-group .btn:hover {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }

    @media (max-width: 768px) {
        .chart-bars { height: 150px; }
        .chart-column span { max-width: 30px; }
    }
</style>
@endpush