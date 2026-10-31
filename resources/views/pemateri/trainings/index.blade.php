@extends('layouts.pemateri')

@section('title', 'Pilih Pelatihan')

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-journal-bookmark"></i></span>
        <div>
            <p class="eyebrow">Aktivitas Pemateri</p>
            <h1 class="h3 mb-0">Pilih Pelatihan</h1>
            <p class="text-muted mb-0">Pilih pelatihan untuk mengupload materi dan menambahkan kuis evaluasi.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

    <!-- FILTER & SEARCH -->
    <div class="panel border-0 shadow-sm mb-4">
        <div class="p-3">
            <form action="{{ route('pemateri.trainings.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul pelatihan atau lokasi..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-2">
                    <select name="tipe" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="online" {{ request('tipe') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="offline" {{ request('tipe') == 'offline' ? 'selected' : '' }}>Offline</option>
                        <option value="hybrid" {{ request('tipe') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'kategori_id', 'tipe']))
                        <a href="{{ route('pemateri.trainings.index') }}" class="btn btn-outline-secondary" title="Reset">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- DAFTAR KARTU PELATIHAN -->
    @if($trainings->isEmpty())
        <div class="panel border-0 shadow-sm p-5 text-center text-muted">
            <i class="bi bi-journal-x fs-1 d-block mb-3 text-secondary"></i>
            @if(request()->anyFilled(['search', 'kategori_id', 'tipe']))
                <h5 class="text-dark">Tidak ada pelatihan yang cocok dengan filter pencarian</h5>
                <p class="small text-muted mb-0">Coba ubah kata kunci pencarian atau filter yang Anda pilih.</p>
            @else
                <h5 class="text-dark">Belum Ada Pelatihan yang Ditugaskan</h5>
                <p class="small text-muted mb-0">Anda belum ditugaskan pada pelatihan apapun oleh Administrator. Pelatihan yang Anda ampu akan otomatis muncul di sini setelah Admin memilih akun Anda sebagai pemateri.</p>
            @endif
        </div>
    @else
        <div class="row g-4">
            @foreach($trainings as $training)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                        @if($training->gambar)
                            <img src="{{ asset('storage/' . $training->gambar) }}" class="card-img-top" alt="{{ $training->judul }}" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px; border-bottom: 1px solid #f0f0f0;">
                                <i class="bi bi-journal-code text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif

                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2.5 py-1" style="font-size: 0.75rem;">
                                    {{ $training->kategori->nama ?? 'Umum' }}
                                </span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 text-uppercase" style="font-size: 0.7rem;">
                                        {{ $training->tipe ?? 'Online' }}
                                    </span>
                                    @if($training->isFinished())
                                        <span class="badge bg-secondary text-white px-2 py-1" style="font-size: 0.7rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <h5 class="card-title fw-bold text-dark mb-2" style="font-size: 1.05rem;">
                                <a href="{{ route('pemateri.trainings.show', $training) }}" class="text-decoration-none text-dark hover-primary">
                                    {{ $training->judul }}
                                </a>
                            </h5>

                            <p class="text-muted small mb-3 flex-grow-1" style="font-size: 0.85rem; line-height: 1.5;">
                                {{ Str::limit(strip_tags($training->deskripsi), 95) }}
                            </p>

                            <div class="d-flex align-items-center text-muted small mb-3 border-top pt-2" style="font-size: 0.8rem;">
                                <i class="bi bi-calendar3 me-1.5 text-primary"></i>
                                <span>{{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d M Y') : '-' }}</span>
                                <span class="mx-2">•</span>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-file-earmark-text text-danger me-1"></i> {{ $training->materis_count }} Materi
                                </span>
                                <span class="badge bg-light text-dark border ms-1">
                                    <i class="bi bi-question-circle text-warning me-1"></i> {{ $training->quizzes_count }} Kuis
                                </span>
                                <span class="badge bg-light text-dark border ms-1">
                                    <i class="bi bi-ui-radios text-info me-1"></i> {{ $training->surveys_count ?? 0 }} Survey
                                </span>
                                <span class="badge bg-light text-dark border ms-1">
                                    <i class="bi bi-images text-success me-1"></i> {{ $training->dokumentasis_count ?? 0 }} Dok
                                </span>
                            </div>

                            <div class="border-top pt-3 d-flex flex-column gap-2">
                                @if($training->isFinished())
                                    <a href="{{ route('pemateri.trainings.show', $training) }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                                        <i class="bi bi-eye me-1"></i> Lihat Riwayat &amp; Hasil
                                    </a>
                                    <div class="text-center py-1">
                                        <span class="badge bg-light text-muted border w-100 py-1.5 small">
                                            <i class="bi bi-lock-fill me-1"></i> Pelatihan Selesai (Konten Ditutup)
                                        </span>
                                    </div>
                                @else
                                    <a href="{{ route('pemateri.trainings.show', $training) }}" class="btn btn-primary btn-sm fw-semibold">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Pilih &amp; Kelola Pelatihan
                                    </a>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('pemateri.materi.create', ['training_id' => $training->id]) }}" class="btn btn-outline-danger btn-sm w-50" title="Upload Materi">
                                            <i class="bi bi-upload me-1"></i> Upload Materi
                                        </a>
                                        <a href="{{ route('pemateri.quiz.create', ['training_id' => $training->id]) }}" class="btn btn-outline-warning text-dark btn-sm w-50" title="Tambah Kuis">
                                            <i class="bi bi-plus-circle me-1"></i> Tambah Kuis
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $trainings->links() }}
        </div>
    @endif

</div>
@endsection
