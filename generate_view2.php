<?php
$content = file_get_contents("resources/views/admin/pendaftaran/show.blade.php");

function makeField($label, $field, $is_array=false) {
    if ($is_array) {
        $val = "{{ is_array(\$registration->user->$field) ? implode(\", \", \$registration->user->$field) : (\$registration->user->$field ?? \"-\") }}";
    } else {
        $val = "{{ \$registration->user->$field ?? \"-\" }}";
    }
    return "
                                <div class=\"col-12 col-md-6\">
                                    <div class=\"detail-item\">
                                        <label class=\"text-muted small fw-semibold text-uppercase\">$label</label>
                                        <p class=\"fw-semibold mb-0\">$val</p>
                                    </div>
                                </div>";
}

function makeFile($label, $field) {
    return "
                                <div class=\"col-12\">
                                    <div class=\"detail-item\">
                                        <label class=\"text-muted small fw-semibold text-uppercase\">$label</label>
                                        <p class=\"fw-semibold mb-0\">
                                            @if(\$registration->user->$field)
                                                <a href=\"{{ asset('storage/' . (is_array(\$registration->user->$field) ? \$registration->user->$field[0] : \$registration->user->$field)) }}\" target=\"_blank\" class=\"text-primary\">
                                                    <i class=\"bi bi-file-earmark-pdf me-1\"></i> Lihat Dokumen
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>";
}

$digital_add1 = makeField("Judul Usaha Online", "judul_usaha_online") . makeField("Shopee", "shopee") . makeField("Tokopedia", "tokopedia") . makeField("Lazada", "lazada") . makeField("Blibli", "blibli") . makeField("Marketplace Lainnya", "marketplace_lainnya", true) . makeField("Wilayah Pemasaran", "wilayah_pemasaran", true);
$digital_add2 = makeField("Facebook Usaha", "facebook_usaha") . makeField("Instagram Usaha", "instagram_usaha") . makeField("Tiktok Usaha", "tiktok_usaha");

// Re-try Digital
if (strpos($content, "shopee") === false) {
    // Find Media Sosial Usaha label
    $search = '<label class="text-muted small fw-semibold text-uppercase">Website Usaha</label>';
    $content = preg_replace("/(<label class=\"text-muted small fw-semibold text-uppercase\">Website Usaha<\/label>.*?<\/div>\s*<\/div>)/s", "$1\n" . $digital_add1 . $digital_add2, $content);
}

// Re-try Dokumen
$dok_add = makeFile("NIB (Nomor Induk Berusaha)", "nib_file") . makeFile("NPWP Usaha", "npwp_file");
if (strpos($content, "npwp_file") === false) {
    $content = preg_replace("/(<!-- TAB: DOKUMEN -->.*?<div class=\"row g-[^\"]+\">)/s", "$1\n" . $dok_add, $content);
}

file_put_contents("resources/views/admin/pendaftaran/show.blade.php", $content);
echo "Done";
