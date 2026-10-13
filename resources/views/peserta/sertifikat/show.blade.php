@extends('layouts.peserta')

@section('title', 'Sertifikat - ' . $sertifikat->nama_sertifikat)

@section('header')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-award text-primary"></i></span>
        <div>
            <p class="eyebrow">Detail Sertifikat</p>
            <h1 class="h3 mb-1">{{ $sertifikat->nama_sertifikat }}</h1>
            <p class="text-muted mb-0">Tinjau dan unduh sertifikat kelulusan Anda.</p>
        </div>
    </div>
    <div class="heading-actions d-flex gap-2">
        <a href="{{ route('peserta.sertifikat.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        @if($isCanvas)
            <button id="downloadBtn" class="btn btn-primary btn-sm" disabled>
                <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
            </button>
        @elseif($hasDirectFile)
            <a href="{{ route('peserta.sertifikat.download', $sertifikat->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-download me-1"></i> Download {{ $isDirectPdf ? 'PDF' : 'Sertifikat' }}
            </a>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 pt-4">

    @if($isCanvas)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-0 text-center bg-light" style="overflow-x: auto;">
                <!-- Canvas responsif -->
                <canvas id="certificateCanvas" class="img-fluid m-3" style="max-width: 100%; height: auto; box-shadow: 0 0 15px rgba(0,0,0,0.1); border-radius: 4px;"></canvas>
            </div>
        </div>
    @elseif($hasDirectFile)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-3 text-center bg-light" style="overflow-x: auto;">
                @if($isDirectImage)
                    <img src="{{ $urlDirectFile }}" alt="Sertifikat {{ $sertifikat->nama_sertifikat }}" 
                         class="img-fluid rounded shadow-sm" style="max-width: 100%; max-height: 80vh; object-fit: contain;">
                @elseif($isDirectPdf)
                    <iframe src="{{ $urlDirectFile }}" class="w-100 rounded" style="height: 80vh; border: none;"></iframe>
                @else
                    <div class="py-5">
                        <i class="bi bi-file-earmark-text text-primary fs-1 mb-3"></i>
                        <h5>File Sertifikat Siap Diunduh</h5>
                        <p class="text-muted">Format file tidak mendukung pratinjau langsung di browser.</p>
                        <a href="{{ route('peserta.sertifikat.download', $sertifikat->id) }}" class="btn btn-primary">
                            <i class="bi bi-download me-1"></i> Download File Sertifikat
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <div>
                <strong>File Sertifikat Belum Tersedia.</strong>
                <p class="mb-0">File sertifikat atau template belum diunggah oleh administrator.</p>
            </div>
        </div>
    @endif

    <!-- Informasi Detail Sertifikat -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Sertifikat</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <span class="text-muted small d-block">Nomor Sertifikat</span>
                    <span class="fw-semibold text-dark">{{ $sertifikat->nomor_sertifikat }}</span>
                </div>
                <div class="col-12 col-md-6">
                    <span class="text-muted small d-block">Penerbit</span>
                    <span class="fw-semibold text-dark">{{ $sertifikat->penerbit }}</span>
                </div>
                <div class="col-12 col-md-6">
                    <span class="text-muted small d-block">Tanggal Terbit</span>
                    <span class="fw-semibold text-dark">{{ date('d F Y', strtotime($sertifikat->tanggal_terbit)) }}</span>
                </div>
                <div class="col-12 col-md-6">
                    <span class="text-muted small d-block">Masa Berlaku</span>
                    <span class="fw-semibold text-dark">{{ $sertifikat->tanggal_berlaku_sampai ? date('d F Y', strtotime($sertifikat->tanggal_berlaku_sampai)) : 'Seumur Hidup' }}</span>
                </div>
                @if(!empty($namaPenandatangan))
                <div class="col-12 col-md-6">
                    <span class="text-muted small d-block">Penandatangan</span>
                    <span class="fw-semibold text-dark">{{ $namaPenandatangan }}</span>
                </div>
                @endif
                <div class="col-12 col-md-6">
                    <span class="text-muted small d-block">Verifikasi Sertifikat</span>
                    <a href="{{ $urlVerify }}" target="_blank" class="text-primary text-decoration-none">
                        <i class="bi bi-patch-check me-1"></i>Cek Validitas Sertifikat Online
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@if($isCanvas)
<!-- QRious Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('certificateCanvas');
    const ctx = canvas.getContext('2d');
    const downloadBtn = document.getElementById('downloadBtn');

    // Data Sertifikat
    const certData = {
        nama: "{!! addslashes($namaPeserta) !!}",
        pelatihan: "{!! addslashes($sertifikat->nama_sertifikat) !!}",
        nomor: "No: {!! addslashes($sertifikat->nomor_sertifikat) !!}",
        tanggal: "Diterbitkan: {{ date('d F Y', strtotime($sertifikat->tanggal_terbit)) }}",
        berlaku: "{{ $sertifikat->tanggal_berlaku_sampai ? 'Berlaku s.d: ' . date('d F Y', strtotime($sertifikat->tanggal_berlaku_sampai)) : '' }}",
        penandatangan: "{!! addslashes($namaPenandatangan ?? '') !!}",
        qrUrl: "{!! addslashes($urlVerify) !!}",
        templateUrl: "{!! $urlTemplate !!}",
        tandaTanganUrl: "{!! $urlTandaTangan !!}"
    };

    // Konfigurasi Render
    const config = {
        nama: { y: 650, font: 'bold 80px "Times New Roman"', color: '#333333', maxWidth: 1200 },
        pelatihan: { y: 780, font: 'italic 45px Arial', color: '#555555', maxWidth: 1400 },
        nomor: { y: 450, font: 'bold 30px Arial', color: '#777777' },
        tanggal: { y: 880, font: '25px Arial', color: '#555555' },
        berlaku: { y: 920, font: '25px Arial', color: '#555555' },
        penandatangan: { y: 1150, font: 'bold 30px Arial', color: '#000000', xOffset: 550 },
        qr: { y: 980, xOffset: -550, size: 150 },
        tandaTangan: { y: 980, xOffset: 550, width: 250, height: 150 }
    };

    // Fungsi memuat gambar sebagai Promise (tidak hard-crash jika tanda tangan opsional gagal)
    function loadImage(src, isRequired = false) {
        return new Promise((resolve, reject) => {
            if (!src) return resolve(null);
            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = () => resolve(img);
            img.onerror = () => {
                if (isRequired) {
                    reject(new Error('Gagal memuat template gambar: ' + src));
                } else {
                    console.warn('Gagal memuat gambar tanda tangan: ' + src);
                    resolve(null);
                }
            };
            img.src = src;
        });
    }

    // Fungsi menggambar QR Code
    function generateQRImage(url, size) {
        return new Promise((resolve) => {
            const qr = new QRious({
                value: url,
                size: size,
                level: 'H'
            });
            const img = new Image();
            img.onload = () => resolve(img);
            img.src = qr.toDataURL('image/png');
        });
    }

    // Fungsi utama render
    async function renderCertificate() {
        try {
            if (document.fonts && document.fonts.ready) {
                await document.fonts.ready;
            }

            // Muat gambar secara paralel
            const [templateImg, signatureImg, qrImg] = await Promise.all([
                loadImage(certData.templateUrl, true),
                loadImage(certData.tandaTanganUrl, false),
                generateQRImage(certData.qrUrl, config.qr.size)
            ]);

            if (!templateImg) {
                throw new Error("Template sertifikat tidak ditemukan.");
            }

            // Atur ukuran canvas mengikuti template asli
            const width = templateImg.width;
            const height = templateImg.height;
            canvas.width = width;
            canvas.height = height;

            // 1. Gambar Template Background
            ctx.drawImage(templateImg, 0, 0, width, height);

            // 2. Gambar Teks
            ctx.textAlign = 'center';
            const centerX = width / 2;

            // -- Nama Peserta
            ctx.font = config.nama.font;
            ctx.fillStyle = config.nama.color;
            ctx.fillText(certData.nama, centerX, config.nama.y, config.nama.maxWidth);

            // -- Nama Pelatihan
            ctx.font = config.pelatihan.font;
            ctx.fillStyle = config.pelatihan.color;
            ctx.fillText(certData.pelatihan, centerX, config.pelatihan.y, config.pelatihan.maxWidth);

            // -- Nomor Sertifikat
            ctx.font = config.nomor.font;
            ctx.fillStyle = config.nomor.color;
            ctx.fillText(certData.nomor, centerX, config.nomor.y);

            // -- Tanggal
            ctx.font = config.tanggal.font;
            ctx.fillStyle = config.tanggal.color;
            ctx.fillText(certData.tanggal, centerX, config.tanggal.y);
            
            if (certData.berlaku) {
                ctx.font = config.berlaku.font;
                ctx.fillText(certData.berlaku, centerX, config.berlaku.y);
            }

            // 3. Gambar Tanda Tangan
            const signatureX = centerX + config.tandaTangan.xOffset - (config.tandaTangan.width / 2);
            if (signatureImg) {
                const aspect = signatureImg.width / signatureImg.height;
                let drawW = config.tandaTangan.width;
                let drawH = drawW / aspect;
                if (drawH > config.tandaTangan.height) {
                    drawH = config.tandaTangan.height;
                    drawW = drawH * aspect;
                }
                
                const offsetX = signatureX + (config.tandaTangan.width - drawW) / 2;
                const offsetY = config.tandaTangan.y + (config.tandaTangan.height - drawH) / 2;

                ctx.drawImage(signatureImg, offsetX, offsetY, drawW, drawH);
            }

            // -- Nama Penandatangan
            if (certData.penandatangan) {
                ctx.font = config.penandatangan.font;
                ctx.fillStyle = config.penandatangan.color;
                ctx.fillText(certData.penandatangan, centerX + config.penandatangan.xOffset, config.penandatangan.y);
            }

            // 4. Gambar QR Code
            const qrX = centerX + config.qr.xOffset - (config.qr.size / 2);
            ctx.drawImage(qrImg, qrX, config.qr.y, config.qr.size, config.qr.size);

            // Enable tombol download
            downloadBtn.disabled = false;
            
        } catch (error) {
            console.error(error);
            alert("Gagal memuat sertifikat. " + error.message);
        }
    }

    // Eksekusi render
    renderCertificate();

    // Setup tombol download
    downloadBtn.addEventListener('click', function() {
        try {
            const dataUrl = canvas.toDataURL('image/jpeg', 1.0);
            const { jsPDF } = window.jspdf;
            const orientation = canvas.width > canvas.height ? 'l' : 'p';
            
            const pdf = new jsPDF({
                orientation: orientation,
                unit: 'px',
                format: [canvas.width, canvas.height]
            });
            
            pdf.addImage(dataUrl, 'JPEG', 0, 0, canvas.width, canvas.height);
            let safeName = certData.nomor.replace('No: ', '').replace(/[^a-z0-9]/gi, '_').toLowerCase();
            pdf.save(`Sertifikat_${safeName}.pdf`);
            
        } catch (e) {
            console.error(e);
            alert('Tidak dapat membuat PDF. Pastikan browser mendukung jsPDF dan tidak ada masalah CORS.');
        }
    });
});
</script>
@endif
@endsection