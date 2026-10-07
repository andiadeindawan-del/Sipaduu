<?php
$content = file_get_contents("resources/views/peserta/sertifikat/show.blade.php");

// change wording to "Download PDF"
$content = str_replace('<i class="bi bi-download me-1"></i> Unduh Sertifikat', '<i class="bi bi-file-earmark-pdf me-1"></i> Download PDF', $content);

// inject jsPDF CDN
$searchScripts = '<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>';
$replaceScripts = '<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>';
$content = str_replace($searchScripts, $replaceScripts, $content);

// replace download listener logic
$searchDownloadLogic = "downloadBtn.addEventListener('click', function() {
        try {
            const dataUrl = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            // Penamaan file sesuai nomor sertifikat
            let safeName = certData.nomor.replace('No: ', '').replace(/[^a-z0-9]/gi, '_').toLowerCase();
            link.download = `Sertifikat_\${safeName}.png`;
            link.href = dataUrl;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } catch (e) {
            alert('Tidak dapat mengunduh gambar. Pastikan gambar tidak terkena masalah CORS.');
        }
    });";

$replaceDownloadLogic = "downloadBtn.addEventListener('click', function() {
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
            pdf.save(`Sertifikat_\${safeName}.pdf`);
            
        } catch (e) {
            console.error(e);
            alert('Tidak dapat membuat PDF. Pastikan browser mendukung jsPDF dan tidak ada masalah CORS.');
        }
    });";

$content = str_replace($searchDownloadLogic, $replaceDownloadLogic, $content);

file_put_contents("resources/views/peserta/sertifikat/show.blade.php", $content);
echo "Done";
