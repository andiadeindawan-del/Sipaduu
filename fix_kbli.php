<?php
$content = file_get_contents("resources/views/admin/pendaftaran/show.blade.php");

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
                                                    <td class=\"fw-bold\">{{ \$userKbli->kbli->kode ?? \"-\" }}</td>
                                                    <td>
                                                        <strong>{{ \$userKbli->kbli->judul ?? \"-\" }}</strong>
                                                        <div class=\"text-muted small mt-1\">{{ \$userKbli->kbli->uraian ?? \"-\" }}</div>
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
$content = preg_replace("/<\/div>\s*<\/div>\s*<!-- TAB: DIGITAL & PEMASARAN -->/s", $kbli_table . "\n                            </div>\n                        </div>\n\n                        <!-- TAB: DIGITAL & PEMASARAN -->", $content);
file_put_contents("resources/views/admin/pendaftaran/show.blade.php", $content);

