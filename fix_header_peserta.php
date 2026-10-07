<?php
$content = file_get_contents("resources/views/peserta/sertifikat/index.blade.php");

$search = "@section('content')
<div class=\"container py-4\">
    <div class=\"row mb-4\">
        <div class=\"col-12\">
            <h1 class=\"h3 fw-bold\"><i class=\"bi bi-award text-primary me-2\"></i> Sertifikat Saya</h1>
            <p class=\"text-muted\">Pantau status kelulusan dan unduh sertifikat pelatihan Anda.</p>
        </div>
    </div>";

$replace = "@section('header')
<div class=\"page-heading d-flex justify-content-between align-items-center\">
    <div class=\"page-heading-copy\">
        <span class=\"page-icon\"><i class=\"bi bi-award text-primary\"></i></span>
        <div>
            <p class=\"eyebrow\">Pencapaian</p>
            <h1 class=\"h3 mb-1\">Sertifikat Saya</h1>
            <p class=\"text-muted mb-0\">Pantau status kelulusan dan unduh sertifikat pelatihan Anda.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class=\"container-fluid px-3 px-lg-4 pt-4\">";

$content = str_replace($search, $replace, $content);
file_put_contents("resources/views/peserta/sertifikat/index.blade.php", $content);
echo "Done";
