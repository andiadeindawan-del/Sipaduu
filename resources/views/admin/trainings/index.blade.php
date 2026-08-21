@extends('layouts.admin')

@section('title', 'Manajemen Pelatihan')

@section('header')
<div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-journal-bookmark"></i></span>
        <div>
            <p class="eyebrow">Manajemen</p>
            <h1 class="h3 mb-0">Pelatihan</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pelatihan</span>
                    <span class="metric-icon"><i class="bi bi-journal-bookmark"></i></span>
                </div>
                <div class="metric-value">{{ $totalTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">+12%</span>
                    <span>bulan ini</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Published</span>
                    <span class="metric-icon"><i class="bi bi-check-circle"></i></span>
                </div>
                <div class="metric-value">{{ $publishedTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-success">Aktif</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Draft</span>
                    <span class="metric-icon"><i class="bi bi-pencil"></i></span>
                </div>
                <div class="metric-value">{{ $draftTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-warning">Perlu review</span>
                    <span>draft</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-info">
                <div class="metric-top">
                    <span class="metric-label">Berjalan</span>
                    <span class="metric-icon"><i class="bi bi-play-circle"></i></span>
                </div>
                <div class="metric-value">{{ $ongoingTrainings ?? 0 }}</div>
                <div class="metric-meta">
                    <span class="text-info">Sedang berlangsung</span>
                    <span>pelatihan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h5 class="section-title"><i class="bi bi-table"></i> Daftar Pelatihan</h5>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <form action="{{ route('admin.trainings.index') }}" method="GET" class="d-flex gap-2">
                    <input class="form-control form-control-sm" type="search" name="search" 
                           placeholder="Cari pelatihan..." value="{{ request('search') }}" style="width: 200px;">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                
                @if(request('search'))
                <a href="{{ route('admin.trainings.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
                @endif
                
                {{-- Tombol Tambah --}}
                <a href="{{ route('admin.trainings.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah
                </a>
            </div>
        </div>
        <div class="table-responsive">
            @if($trainings->count() > 0)
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trainings as $index => $training)
                    <tr>
                        <td>{{ $trainings->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <p class="fw-semibold mb-0">{{ $training->judul }}</p>
                            </div>
                        </td>
                        <td>
                            @if($training->kategori)
                            <span class="text-muted">{{ $training->kategori->nama }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                <div><i class=""></i> {{ $training->tanggal_mulai ? $training->tanggal_mulai->format('d/m/Y') : '-' }}</div>
                                <div><i class=""></i> {{ $training->tanggal_selesai ? $training->tanggal_selesai->format('d/m/Y') : '-' }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted">
                                {{ $training->participants_count ?? 0 }}
                                @if($training->kapasitas)
                                / {{ $training->kapasitas }}
                                @endif
                            </span>
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'draft' => ['label' => 'Draft', 'class' => 'badge-draft'],
                                    'published' => ['label' => 'Published', 'class' => 'badge-published'],
                                    'berjalan' => ['label' => 'Berjalan', 'class' => 'badge-berjalan'],
                                    'selesai' => ['label' => 'Selesai', 'class' => 'badge-selesai'],
                                    'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'badge-dibatalkan'],
                                ];
                                $status = $statusMap[$training->status] ?? ['label' => $training->status, 'class' => 'badge-draft'];
                            @endphp
                            <span class="badge {{ $status['class'] }}">
                                <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                {{-- Link Show --}}
                                <a href="{{ route('admin.trainings.show', $training->id) }}" class="badge bg-info text-white border-0 p-2 text-decoration-none" title="Lihat">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                
                                {{-- Link Edit --}}
                                <a href="{{ route('admin.trainings.edit', $training->id) }}" class="badge bg-warning text-dark border-0 p-2 text-decoration-none" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                
                                {{-- Tombol Delete dengan form --}}
                                <form action="{{ route('admin.trainings.destroy', $training->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelatihan {{ $training->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="badge bg-danger text-white border-0 p-2" title="Hapus">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="h5">Belum ada pelatihan</p>
                    <p class="small">
                        @if(request('search'))
                            Tidak ada pelatihan yang sesuai dengan pencarian "{{ request('search') }}".
                        @else
                            Mulai dengan menambahkan pelatihan baru
                        @endif
                    </p>
                    @if(request('search'))
                    <a href="{{ route('admin.trainings.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                    </a>
                    @endif
                    <a href="{{ route('admin.trainings.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-plus-circle"></i> Tambah Pelatihan
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if($trainings->hasPages())
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3 px-3 pb-3">
            <p class="text-muted small mb-0">
                Menampilkan {{ $trainings->firstItem() ?? 0 }} sampai {{ $trainings->lastItem() ?? 0 }} 
                dari {{ $trainings->total() ?? 0 }} pelatihan
            </p>
            <nav aria-label="Pelatihan pagination">
                {{ $trainings->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection