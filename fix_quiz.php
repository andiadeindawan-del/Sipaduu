<?php
$content = file_get_contents("resources/views/peserta/trainings/show.blade.php");

$old_quiz_btn = '/<a href="\{\{ route\(\'peserta\.quiz\.show\', \$quiz->id\) \}\}" class="btn btn-sm btn-success">\s*<i class="bi bi-play-circle me-1"><\/i> Kerjakan\s*<\/a>/s';

$new_quiz_btn = "@if(isset(\$registration) && \$registration->status == 'disetujui')
                                        <a href=\"{{ route('peserta.quiz.show', \$quiz->id) }}\" class=\"btn btn-sm btn-success\">
                                            <i class=\"bi bi-play-circle me-1\"></i> Kerjakan
                                        </a>
                                    @else
                                        <button type=\"button\" class=\"btn btn-sm btn-secondary\" disabled title=\"Menunggu persetujuan pendaftaran\">
                                            <i class=\"bi bi-lock me-1\"></i> Terkunci
                                        </button>
                                    @endif";

$content = preg_replace($old_quiz_btn, $new_quiz_btn, $content);
file_put_contents("resources/views/peserta/trainings/show.blade.php", $content);
echo "Done";
