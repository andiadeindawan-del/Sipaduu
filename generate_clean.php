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
                                                <a href=\"{{ asset('storage/' . (is_array(\$registration->user->$field) ? \$registration->user->{$field}[0] : \$registration->user->$field)) }}\" target=\"_blank\" class=\"text-primary\">
                                                    <i class=\"bi bi-file-earmark-pdf me-1\"></i> Lihat Dokumen
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>";
}

// 1. Pribadi
$pribadi_add = makeField("Provinsi", "provinsi") . makeField("Kabupaten/Kota", "kabupaten") . makeField("Kecamatan", "kecamatan") . makeField("Desa/Kelurahan", "desa");
$search = "                                <div class=\"col-12\">
                                    <div class=\"detail-item\">
                                        <label class=\"text-muted small fw-semibold text-uppercase\">Alamat Domisili</label>";
$content = str_replace($search, $pribadi_add . "\n" . $search, $content);

// 2. Usaha
$usaha_add1 = makeField("Jenis Usaha", "jenis_usaha") . makeField("Status Usaha", "status_usaha") . makeField("Bentuk Usaha", "bentuk_usaha") . makeField("Sektor Usaha", "sektor_usaha") . makeField("Bidang Usaha", "bidang_usaha");
$search = "                                <div class=\"col-12 col-md-6\">
                                    <div class=\"detail-item\">
                                        <label class=\"text-muted small fw-semibold text-uppercase\">Nama Usaha</label>";
$content = str_replace($search, $usaha_add1 . "\n" . $search, $content);

$usaha_add2 = makeField("Provinsi Usaha", "provinsi_usaha") . makeField("Kabupaten Usaha", "kabupaten_usaha") . makeField("Kecamatan Usaha", "kecamatan_usaha") . makeField("Desa Usaha", "desa_usaha");
$search = "                                <div class=\"col-12\">
                                    <div class=\"detail-item\">
                                        <label class=\"text-muted small fw-semibold text-uppercase\">Alamat Usaha</label>";
$content = str_replace($search, $usaha_add2 . "\n" . $search, $content);

$usaha_add3 = makeField("Karyawan Tetap (L)", "karyawan_tetap_laki_laki") . makeField("Karyawan Tetap (P)", "karyawan_tetap_perempuan") . makeField("Total Karyawan Tetap", "total_karyawan_tetap") . makeField("Karyawan Tdk Tetap (L)", "karyawan_tidak_tetap_laki_laki") . makeField("Karyawan Tdk Tetap (P)", "karyawan_tidak_tetap_perempuan") . makeField("Total Karyawan Tdk Tetap", "total_karyawan_tidak_tetap") . makeField("Total Tenaga Kerja", "total_tenaga_kerja");
$search = "                                <div class=\"col-12 col-md-6\">
                                    <div class=\"detail-item\">
                                        <label class=\"text-muted small fw-semibold text-uppercase\">Jumlah Karyawan</label>";
$content = str_replace($search, $usaha_add3 . "\n" . $search, $content);


// 3. Digital
$digital_add1 = makeField("Judul Usaha Online", "judul_usaha_online") . makeField("Shopee", "shopee") . makeField("Tokopedia", "tokopedia") . makeField("Lazada", "lazada") . makeField("Blibli", "blibli") . makeField("Marketplace Lainnya", "marketplace_lainnya", true) . makeField("Wilayah Pemasaran", "wilayah_pemasaran", true);
$digital_add2 = makeField("Facebook Usaha", "facebook_usaha") . makeField("Instagram Usaha", "instagram_usaha") . makeField("Tiktok Usaha", "tiktok_usaha");
$search = "                                <div class=\"col-12 col-md-6\">
                                    <div class=\"detail-item\">
                                        <label class=\"text-muted small fw-semibold text-uppercase\">Media Sosial Usaha</label>";
$content = str_replace($search, $digital_add1 . "\n" . $search . "\n" . $digital_add2, $content);

// 4. Dokumen
$dok_add = makeFile("NIB (Nomor Induk Berusaha)", "nib_file") . makeFile("NPWP Usaha", "npwp_file");
$search = "<!-- TAB: DOKUMEN -->
                        <div class=\"tab-pane fade\" id=\"dokumen\" role=\"tabpanel\">
                            <div class=\"row g-3\">";
$content = str_replace($search, $search . "\n" . $dok_add, $content);

// 5. KBLI Table
$kbli_table = "
                                <div class=\"col-12 mt-4\">
                                    <div class=\"d-flex justify-content-between align-items-center mb-2\">
                                        <h6 class=\"fw-bold text-secondary mb-0\"><i class=\"bi bi-tag-fill me-2\"></i>Daftar KBLI (Kegiatan Usaha)</h6>
                                    </div>
                                    @if(\$registration->user->kblis && \$registration->user->kblis->count() > 0)
                                    <div class=\"table-responsive\">
                                        <table class=\"table table-bordered table-hover mb-0\" style=\"font-size: 0.9rem;\">
                                            <thead class=\"table-light\">
                                                <tr>
                                                    <th style=\"width: 15%;\">Status</th>
                                                    <th style=\"width: 15%;\">Kode</th>
                                                    <th style=\"width: 70%;\">Judul & Uraian</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(\$registration->user->kblis as \$userKbli)
                                                <tr>
                                                    <td>
                                                        @if(\$userKbli->is_utama)
                                                            <span class=\"badge bg-primary\">KBLI Utama</span>
                                                        @else
                                                            <span class=\"badge bg-secondary\">Usaha Lainnya</span>
                                                        @endif
                                                    </td>
                                                    <td class=\"fw-bold\">{{ \$userKbli->kbli->kode ?? '-' }}</td>
                                                    <td>
                                                        <strong>{{ \$userKbli->kbli->judul ?? '-' }}</strong>
                                                        <div class=\"text-muted small mt-1\">{{ \$userKbli->kbli->uraian ?? '-' }}</div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <div class=\"alert alert-light border fst-italic mb-0 text-muted\">
                                        Peserta belum menambahkan data KBLI.
                                    </div>
                                    @endif
                                </div>
";
// Add to end of usaha tab. We need to find the closing div of row g-3 inside TAB: USAHA
// Let's just insert it before `<!-- TAB: DIGITAL & PEMASARAN -->` but wait, `kbli_table` should be inside the `row g-3`.
// Let's just insert it before `<!-- TAB: DIGITAL & PEMASARAN -->` by closing the row and tab?
// Actually, I can replace `<!-- TAB: DIGITAL & PEMASARAN -->` with the kbli_table + close tags, wait.
// Let's replace `                        <!-- TAB: DIGITAL & PEMASARAN -->` with:
// $kbli_table . "\n                            </div>\n                        </div>\n\n                        <!-- TAB: DIGITAL & PEMASARAN -->"
// BUT I need to make sure the original had `</div></div>` right above it!
// I reverted the file, so it does have `</div></div>` above it. But I should put `$kbli_table` INSIDE the row!
// Let's just do:
$search = "                        <!-- TAB: DIGITAL & PEMASARAN -->";
$replacement = $kbli_table . "\n                            </div>\n                        </div>\n\n" . $search;
// wait, I need to strip the existing `</div></div>` above it to insert it inside the row.
// Better way: find `<!-- TAB: DIGITAL & PEMASARAN -->`, replace `</div>\n                        </div>\n\n                        <!-- TAB: DIGITAL & PEMASARAN -->`
$search2 = "</div>
                        </div>

                        <!-- TAB: DIGITAL & PEMASARAN -->";
$replacement2 = $kbli_table . "\n                            </div>\n                        </div>\n\n                        <!-- TAB: DIGITAL & PEMASARAN -->";
$content = str_replace($search2, $replacement2, $content);


// Let's also fix the array handling for file_produk and jenis_pelatihan_diikuti which I did previously
// For file_produk
$file_produk_old = "<p class=\"fw-semibold mb-0\">
                                            @if(\$registration->user->file_produk)
                                                <a href=\"{{ asset('storage/' . \$registration->user->file_produk) }}\" target=\"_blank\" class=\"text-primary\">
                                                    <i class=\"bi bi-paperclip me-1\"></i> Lihat lampiran
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </p>";
$file_produk_new = "<p class=\"fw-semibold mb-0\">
                                            @if(is_array(\$registration->user->file_produk) && count(\$registration->user->file_produk) > 0)
                                                @foreach(\$registration->user->file_produk as \$file)
                                                    <a href=\"{{ asset('storage/' . \$file) }}\" target=\"_blank\" class=\"text-primary d-block mb-1\">
                                                        <i class=\"bi bi-paperclip me-1\"></i> Lihat lampiran {{ \$loop->iteration }}
                                                    </a>
                                                @endforeach
                                            @elseif(is_string(\$registration->user->file_produk) && \$registration->user->file_produk != '')
                                                <a href=\"{{ asset('storage/' . \$registration->user->file_produk) }}\" target=\"_blank\" class=\"text-primary\">
                                                    <i class=\"bi bi-paperclip me-1\"></i> Lihat lampiran
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </p>";
$content = str_replace($file_produk_old, $file_produk_new, $content);

// For jenis_pelatihan_diikuti
$jenis_old = "<p class=\"fw-semibold mb-0\">{{ \$registration->user->jenis_pelatihan_diikuti ?? '-' }}</p>";
$jenis_new = "<p class=\"fw-semibold mb-0\">{{ is_array(\$registration->user->jenis_pelatihan_diikuti) ? implode(', ', \$registration->user->jenis_pelatihan_diikuti) : (\$registration->user->jenis_pelatihan_diikuti ?? '-') }}</p>";
$content = str_replace($jenis_old, $jenis_new, $content);


file_put_contents("resources/views/admin/pendaftaran/show.blade.php", $content);
echo "Done";
