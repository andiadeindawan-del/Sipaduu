<?php
$content = file_get_contents("resources/views/peserta/sertifikat/show.blade.php");

$search = "@section('content')
<div class=\"container py-4\">
    <div class=\"d-flex justify-content-between align-items-center mb-4\">
        <div>
            <h1 class=\"h3 fw-bold mb-1\"><i class=\"bi bi-award text-primary me-2\"></i> Detail Sertifikat</h1>
            <p class=\"text-muted mb-0\">Sertifikat kelulusan: {{ \$sertifikat->nama_sertifikat }}</p>
        </div>
        <div>
            <a href=\"{{ route('sertifikat.index') }}\" class=\"btn btn-outline-secondary me-2\">
                <i class=\"bi bi-arrow-left\"></i> Kembali
            </a>
            <button id=\"downloadBtn\" class=\"btn btn-primary\" disabled>
                <i class=\"bi bi-download\"></i> Unduh Sertifikat
            </button>
        </div>
    </div>";

$replace = "@section('header')
<div class=\"page-heading d-flex justify-content-between align-items-center\">
    <div class=\"page-heading-copy\">
        <span class=\"page-icon\"><i class=\"bi bi-award text-primary\"></i></span>
        <div>
            <p class=\"eyebrow\">Detail Sertifikat</p>
            <h1 class=\"h3 mb-1\">{{ \$sertifikat->nama_sertifikat }}</h1>
            <p class=\"text-muted mb-0\">Tinjau dan unduh sertifikat kelulusan Anda.</p>
        </div>
    </div>
    <div class=\"heading-actions d-flex gap-2\">
        <a href=\"{{ route('sertifikat.index') }}\" class=\"btn btn-outline-secondary btn-sm\">
            <i class=\"bi bi-arrow-left me-1\"></i> Kembali
        </a>
        <button id=\"downloadBtn\" class=\"btn btn-primary btn-sm\" disabled>
            <i class=\"bi bi-download me-1\"></i> Unduh Sertifikat
        </button>
    </div>
</div>
@endsection

@section('content')
<div class=\"container-fluid px-3 px-lg-4 pt-4\">";

$content = str_replace($search, $replace, $content);
file_put_contents("resources/views/peserta/sertifikat/show.blade.php", $content);
echo "Done";
