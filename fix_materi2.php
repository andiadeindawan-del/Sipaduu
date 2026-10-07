<?php
$content = file_get_contents("resources/views/peserta/trainings/show.blade.php");

$old_materi_btn = '/<a href="\{\{ route\(\'peserta\.materi\.show\', \$materi->id\) \}\}" class="btn btn-sm btn-primary">\s*<i class="bi bi-eye me-1"><\/i> Lihat\s*<\/a>/s';

$new_materi_btn = "@if(isset(\$registration) && \$registration->status == 'disetujui')
                                        <a href=\"{{ route('peserta.materi.show', \$materi->id) }}\" class=\"btn btn-sm btn-primary\">
                                            <i class=\"bi bi-eye me-1\"></i> Lihat
                                        </a>
                                    @else
                                        <button type=\"button\" class=\"btn btn-sm btn-secondary\" disabled title=\"Menunggu persetujuan pendaftaran\">
                                            <i class=\"bi bi-lock me-1\"></i> Terkunci
                                        </button>
                                    @endif";

$content = preg_replace($old_materi_btn, $new_materi_btn, $content);

$old_mulai_btn = '/@if\(\$isEnrolled \?\? false\)\s*<a href="\{\{ route\(\'peserta\.materi\.index\'\) \}\}" class="btn btn-primary">\s*<i class="bi bi-book me-1"><\/i> Mulai Belajar\s*<\/a>\s*@endif/s';

$new_mulai_btn = "@if(isset(\$registration) && \$registration->status == 'disetujui')
                                    <a href=\"{{ route('peserta.materi.index') }}\" class=\"btn btn-primary\">
                                        <i class=\"bi bi-book me-1\"></i> Mulai Belajar
                                    </a>
                                @endif";

$content = preg_replace($old_mulai_btn, $new_mulai_btn, $content);

file_put_contents("resources/views/peserta/trainings/show.blade.php", $content);
echo "Done";
