<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat - SIPADUU</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .verify-card {
            max-width: 600px;
            margin: 2rem auto;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="text-center mb-4">
            <h1 class="h3 fw-bold text-primary"><i class="bi bi-shield-check"></i> Verifikasi Sertifikat</h1>
            <p class="text-muted">Sistem Informasi Pelatihan (SIPADUU)</p>
        </div>

        <div class="card verify-card border-0">
            <div class="card-body p-4">
                <form action="{{ route('sertifikat.verify') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="nomor_sertifikat" class="form-control" placeholder="Masukkan Nomor Sertifikat" value="{{ request('nomor_sertifikat', $nomorSertifikat) }}" required>
                        <button class="btn btn-primary" type="submit">Cek Validitas</button>
                    </div>
                </form>

                @if($nomorSertifikat)
                    <hr class="my-4">
                    @if($sertifikat)
                        @if($sertifikat->status === 'aktif')
                            <div class="text-center mb-4">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                                <h3 class="mt-2 text-success fw-bold">SERTIFIKAT VALID</h3>
                                <p class="text-muted">Sertifikat ini resmi diterbitkan oleh sistem kami.</p>
                            </div>
                        @else
                            <div class="text-center mb-4">
                                <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                                <h3 class="mt-2 text-danger fw-bold">SERTIFIKAT {{ strtoupper($sertifikat->status) }}</h3>
                                <p class="text-muted">Sertifikat ini sudah tidak aktif atau dicabut.</p>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th class="bg-light" style="width: 40%;">Nomor Sertifikat</th>
                                        <td class="fw-bold">{{ $sertifikat->nomor_sertifikat }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Diberikan Kepada</th>
                                        <td class="fw-bold">{{ $sertifikat->user->nama ?? $sertifikat->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Atas Kelulusan</th>
                                        <td>{{ $sertifikat->nama_sertifikat }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Tanggal Terbit</th>
                                        <td>{{ date('d M Y', strtotime($sertifikat->tanggal_terbit)) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Berlaku Sampai</th>
                                        <td>{{ $sertifikat->tanggal_berlaku_sampai ? date('d M Y', strtotime($sertifikat->tanggal_berlaku_sampai)) : 'Seumur Hidup' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Penerbit</th>
                                        <td>{{ $sertifikat->penerbit }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-search text-danger mb-3" style="font-size: 3rem;"></i>
                            <h4 class="text-danger fw-bold">TIDAK VALID</h4>
                            <p class="text-muted mb-0">Nomor sertifikat <strong>{{ $nomorSertifikat }}</strong> tidak ditemukan dalam sistem kami.</p>
                        </div>
                    @endif
                @endif
            </div>
            <div class="card-footer bg-light text-center py-3">
                <small class="text-muted">&copy; {{ date('Y') }} Dinas Koperasi, Perindustrian dan Perdagangan Provinsi Sulawesi Barat</small>
            </div>
        </div>
    </div>
</body>
</html>
