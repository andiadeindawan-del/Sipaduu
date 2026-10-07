<?php
$content = file_get_contents("resources/views/admin/sertifikat/menunggu.blade.php");

$content = str_replace("\$passedUsers", "\$participants", $content);

$searchTableBody = "<tbody>
                                    @foreach(\$participants as \$user)
                                    <tr>
                                        <td class=\"text-center\">
                                            <input class=\"form-check-input user-checkbox\" type=\"checkbox\" name=\"user_ids[]\" value=\"{{ \$user->id }}\" checked>
                                        </td>
                                        <td>
                                            <div class=\"d-flex align-items-center\">
                                                <div class=\"ms-2\">
                                                    <h6 class=\"mb-0\">{{ \$user->nama }}</h6>
                                                    <small class=\"text-muted\">{{ \$user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ isset(\$user->passed_at) ? \Carbon\Carbon::parse(\$user->passed_at)->translatedFormat('d F Y H:i') : '-' }}</td>
                                        <td>
                                            <span class=\"badge bg-success\">{{ \$user->final_score ?? 0 }} / 100</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>";

$replaceTableBody = "<tbody>
                                    @foreach(\$participants as \$user)
                                    <tr>
                                        <td class=\"text-center\">
                                            @if(\$user->status_sertifikat === 'Layak Diterbitkan')
                                                <input class=\"form-check-input user-checkbox\" type=\"checkbox\" name=\"user_ids[]\" value=\"{{ \$user->id }}\" checked>
                                            @else
                                                <i class=\"bi bi-dash text-muted\"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class=\"d-flex align-items-center\">
                                                <div class=\"ms-2\">
                                                    <h6 class=\"mb-0\">{{ \$user->nama }}</h6>
                                                    <small class=\"text-muted\">{{ \$user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if(\$user->status_sertifikat === 'Layak Diterbitkan')
                                                <span class=\"badge bg-success\">Layak Diterbitkan</span>
                                            @elseif(\$user->status_sertifikat === 'Diterbitkan')
                                                <span class=\"badge bg-primary\">Diterbitkan</span>
                                            @else
                                                <span class=\"badge bg-danger\">{{ \$user->status_sertifikat }}</span>
                                            @endif
                                        </td>
                                        <td>{{ isset(\$user->passed_at) ? \Carbon\Carbon::parse(\$user->passed_at)->translatedFormat('d F Y H:i') : '-' }}</td>
                                        <td>
                                            <span class=\"badge bg-info\">{{ \$user->final_score ?? 0 }} / 100</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>";

// Fix table header
$searchHead = "<th>Tanggal Lulus Terakhir</th>
                                        <th>Rata-rata Nilai</th>";
$replaceHead = "<th>Status Kelayakan</th>
                                        <th>Tanggal Lulus Terakhir</th>
                                        <th>Rata-rata Nilai</th>";

$content = str_replace($searchTableBody, $replaceTableBody, $content);
$content = str_replace($searchHead, $replaceHead, $content);

file_put_contents("resources/views/admin/sertifikat/menunggu.blade.php", $content);
echo "Done";
