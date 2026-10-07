<?php
$content = file_get_contents("resources/views/admin/sertifikat/menunggu.blade.php");

$regex = '/<tbody>\s*@foreach\(\$participants as \$user\).*?<\/tbody>/s';
$replace = '<tbody>
                                    @foreach($participants as $user)
                                    <tr>
                                        <td class="text-center">
                                            @if($user->status_sertifikat === \'Layak Diterbitkan\')
                                                <input class="form-check-input user-checkbox" type="checkbox" name="user_ids[]" value="{{ $user->id }}" checked>
                                            @else
                                                <i class="bi bi-dash text-muted"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <h6 class="mb-0">{{ $user->nama ?? $user->name }}</h6>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($user->status_sertifikat === \'Layak Diterbitkan\')
                                                <span class="badge bg-success">Layak Diterbitkan</span>
                                            @elseif($user->status_sertifikat === \'Diterbitkan\')
                                                <span class="badge bg-primary">Diterbitkan</span>
                                            @else
                                                <span class="badge bg-danger">{{ $user->status_sertifikat }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->passed_at ? date(\'d M Y H:i\', strtotime($user->passed_at)) : \'-\' }}</td>
                                        <td><span class="badge bg-info">{{ number_format($user->final_score ?? 0, 1) }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>';

$content = preg_replace($regex, $replace, $content);
file_put_contents("resources/views/admin/sertifikat/menunggu.blade.php", $content);
echo "Done";
