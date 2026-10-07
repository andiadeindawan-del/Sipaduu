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
        <button id="downloadBtn" class="btn btn-primary btn-sm" disabled>
            <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 pt-4">

    <div class="card shadow-sm border-0">
        <div class="card-body p-0 text-center bg-light" style="overflow-x: auto;">
            <!-- Canvas responsif -->
            <canvas id="certificateCanvas" class="img-fluid m-3" style="max-width: 100%; height: auto; box-shadow: 0 0 15px rgba(0,0,0,0.1); border-radius: 4px;"></canvas>
        </div>
    </div>
</div>

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
        penandatangan: "{!! addslashes($sertifikat->nama_penandatangan) !!}",
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
        penandatangan: { y: 1150, font: 'bold 30px Arial', color: '#000000', xOffset: 550 }, // Dari tengah geser ke kanan
        qr: { y: 980, xOffset: -550, size: 150 }, // Dari tengah geser ke kiri
        tandaTangan: { y: 980, xOffset: 550, width: 250, height: 150 }
    };

    // Fungsi memuat gambar sebagai Promise
    function loadImage(src) {
        return new Promise((resolve, reject) => {
            if (!src) return resolve(null);
            const img = new Image();
            img.crossOrigin = 'Anonymous'; // Mencegah tainted canvas jika domain sama tapi protokol berbeda
            img.onload = () => resolve(img);
            img.onerror = () => reject(new Error('Gagal memuat gambar: ' + src));
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
            // Tunggu font selesai diload browser (opsional, tp disarankan)
            if (document.fonts && document.fonts.ready) {
                await document.fonts.ready;
            }

            // Muat gambar secara paralel
            const [templateImg, signatureImg, qrImg] = await Promise.all([
                loadImage(certData.templateUrl),
                loadImage(certData.tandaTanganUrl),
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

            const centerX = width / 2;

            // 1. Gambar Template Background
            ctx.drawImage(templateImg, 0, 0, width, height);

            // 2. Gambar Teks (Centered)
            ctx.textAlign = 'center';

            // -- Nama Peserta (Auto Scale)
            ctx.font = config.nama.font;
            ctx.fillStyle = config.nama.color;
            let currentNameFont = config.nama.font;
            let nameWidth = ctx.measureText(certData.nama).width;
            
            // Jika terlalu panjang, perkecil font
            if (nameWidth > config.nama.maxWidth) {
                const scaleRatio = config.nama.maxWidth / nameWidth;
                const newSize = Math.floor(80 * scaleRatio);
                ctx.font = `bold ${newSize}px "Times New Roman"`;
            }
            ctx.fillText(certData.nama, centerX, config.nama.y);

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
                // Menyesuaikan rasio aspek tanda tangan
                const aspect = signatureImg.width / signatureImg.height;
                let drawW = config.tandaTangan.width;
                let drawH = drawW / aspect;
                if (drawH > config.tandaTangan.height) {
                    drawH = config.tandaTangan.height;
                    drawW = drawH * aspect;
                }
                
                // Centering tanda tangan di kotak bayangannya
                const offsetX = signatureX + (config.tandaTangan.width - drawW) / 2;
                const offsetY = config.tandaTangan.y + (config.tandaTangan.height - drawH) / 2;

                ctx.drawImage(signatureImg, offsetX, offsetY, drawW, drawH);
            }

            // -- Nama Penandatangan
            ctx.font = config.penandatangan.font;
            ctx.fillStyle = config.penandatangan.color;
            ctx.fillText(certData.penandatangan, centerX + config.penandatangan.xOffset, config.penandatangan.y);

            // 4. Gambar QR Code
            const qrX = centerX + config.qr.xOffset - (config.qr.size / 2);
            ctx.drawImage(qrImg, qrX, config.qr.y, config.qr.size, config.qr.size);

            // Enable tombol download
            downloadBtn.disabled = false;
            
        } catch (error) {
            console.error(error);
            alert("Gagal memuat sertifikat. Pastikan template dan tanda tangan tersedia. Pesan: " + error.message);
        }
    }

    // Eksekusi render
    renderCertificate();

    // Setup tombol download
    downloadBtn.addEventListener('click', function() {
        try {
            const dataUrl = canvas.toDataURL('image/jpeg', 1.0);
            
            // Mengatur jsPDF
            const { jsPDF } = window.jspdf;
            // Deteksi orientasi otomatis
            const orientation = canvas.width > canvas.height ? 'l' : 'p';
            
            const pdf = new jsPDF({
                orientation: orientation,
                unit: 'px',
                format: [canvas.width, canvas.height]
            });
            
            pdf.addImage(dataUrl, 'JPEG', 0, 0, canvas.width, canvas.height);
            
            // Penamaan file sesuai nomor sertifikat
            let safeName = certData.nomor.replace('No: ', '').replace(/[^a-z0-9]/gi, '_').toLowerCase();
            pdf.save(`Sertifikat_${safeName}.pdf`);
            
        } catch (e) {
            console.error(e);
            alert('Tidak dapat membuat PDF. Pastikan browser mendukung jsPDF dan tidak ada masalah CORS.');
        }
    });
});
</script>
@endsection