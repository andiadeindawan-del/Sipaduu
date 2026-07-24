@extends('layouts.peserta')

@section('title', 'Detail Sertifikat')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Sertifikat</h5>
                    <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-sm btn-light">Kembali</a>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-award text-primary" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">{{ $sertifikat->nama_sertifikat }}</h4>
                        <p class="text-muted">Nomor: {{ $sertifikat->nomor_sertifikat }}</p>
                    </div>

                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="30%" class="text-muted">Penerbit</th>
                                <td>{{ $sertifikat->penerbit }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status</th>
                                <td>
                                    <span class="badge {{ $sertifikat->status === 'aktif' ? 'bg-success' : ($sertifikat->status === 'expired' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($sertifikat->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal Terbit</th>
                                <td>{{ $sertifikat->tanggal_terbit ? $sertifikat->tanggal_terbit->format('d F Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Berlaku Sampai</th>
                                <td>{{ $sertifikat->tanggal_berlaku_sampai ? $sertifikat->tanggal_berlaku_sampai->format('d F Y') : 'Seumur Hidup' }}</td>
                            </tr>
                            @if($sertifikat->training)
                            <tr>
                                <th class="text-muted">Terkait Pelatihan</th>
                                <td>{{ $sertifikat->training->judul }}</td>
                            </tr>
                            @endif
                            @if($sertifikat->deskripsi)
                            <tr>
                                <th class="text-muted">Deskripsi</th>
                                <td>{{ $sertifikat->deskripsi }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    @if($sertifikat->file_path && $sertifikat->status === 'aktif')
                    <div class="text-center mt-4">
                        <a href="{{ route('peserta.sertifikat.download', $sertifikat->id) }}" class="btn btn-success" target="_blank">
                            <i class="bi bi-download me-2"></i> Download Sertifikat
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
