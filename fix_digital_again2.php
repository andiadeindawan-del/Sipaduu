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

$digital_add1 = makeField("Judul Usaha Online", "judul_usaha_online") . makeField("Shopee", "shopee") . makeField("Tokopedia", "tokopedia") . makeField("Lazada", "lazada") . makeField("Blibli", "blibli") . makeField("Marketplace Lainnya", "marketplace_lainnya", true) . makeField("Wilayah Pemasaran", "wilayah_pemasaran", true);
$digital_add2 = makeField("Facebook Usaha", "facebook_usaha") . makeField("Instagram Usaha", "instagram_usaha") . makeField("Tiktok Usaha", "tiktok_usaha");

// Inject correctly into the digital tab
$content = str_replace("</div><!-- TAB: INFORMASI PELATIHAN -->", $digital_add1 . "\n" . $digital_add2 . "\n                            </div>\n                        </div>\n\n                        <!-- TAB: INFORMASI PELATIHAN -->", $content);

file_put_contents("resources/views/admin/pendaftaran/show.blade.php", $content);

