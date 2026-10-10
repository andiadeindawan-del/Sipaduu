<?php
$content = file_get_contents("resources/views/admin/sertifikat/create.blade.php");

$searchTraining = '<!-- Training -->
                            <div class="col-12">
                                <label for="training_id" class="form-label fw-semibold">Training</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                                    <select class="form-select @error(\'training_id\') is-invalid @enderror" 
                                            id="training_id" name="training_id">
                                        <option value="">Pilih Training (Opsional)</option>
                                        @foreach($trainings ?? [] as $training)
                                        <option value="{{ $training->id }}" {{ old(\'training_id\') == $training->id ? \'selected\' : \'\' }}>
                                            {{ $training->judul }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error(\'training_id\')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>';

$searchUser = '<!-- User/Peserta -->
                            <div class="col-12">
                                <label for="user_id" class="form-label fw-semibold">Peserta <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <select class="form-select @error(\'user_id\') is-invalid @enderror" 
                                            id="user_id" name="user_id" required>
                                        <option value="">Pilih Peserta</option>
                                        @foreach($users ?? [] as $user)
                                        <option value="{{ $user->id }}" {{ old(\'user_id\') == $user->id ? \'selected\' : \'\' }}>
                                            {{ $user->nama }} - {{ $user->email }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error(\'user_id\')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>';

$replaceUser = '<!-- User/Peserta -->
                            <div class="col-12">
                                <label for="user_id" class="form-label fw-semibold">Peserta <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <select class="form-select @error(\'user_id\') is-invalid @enderror" 
                                            id="user_id" name="user_id" required>
                                        <option value="">Pilih Training terlebih dahulu</option>
                                    </select>
                                </div>
                                @error(\'user_id\')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>';

$replaceTraining = '<!-- Training -->
                            <div class="col-12">
                                <label for="training_id" class="form-label fw-semibold">Training <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                                    <select class="form-select @error(\'training_id\') is-invalid @enderror" 
                                            id="training_id" name="training_id" required>
                                        <option value="">Pilih Training</option>
                                        @foreach($trainings ?? [] as $training)
                                        <option value="{{ $training->id }}" {{ old(\'training_id\') == $training->id ? \'selected\' : \'\' }}>
                                            {{ $training->judul }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error(\'training_id\')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>';

$content = str_replace($searchUser, "", $content);
$content = str_replace($searchTraining, $replaceTraining . "\n\n                            " . $replaceUser, $content);

$scriptAdd = "
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trainingSelect = document.getElementById('training_id');
        const userSelect = document.getElementById('user_id');

        trainingSelect.addEventListener('change', function() {
            const trainingId = this.value;
            userSelect.innerHTML = '<option value=\"\">Memuat peserta...</option>';

            if (trainingId) {
                fetch(`/admin/sertifikat/eligible-users/\${trainingId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length === 0) {
                            userSelect.innerHTML = '<option value=\"\">Tidak ada peserta yang lulus/memenuhi syarat</option>';
                        } else {
                            userSelect.innerHTML = '<option value=\"\">Pilih Peserta yang Lulus</option>';
                            data.forEach(user => {
                                userSelect.innerHTML += `<option value=\"\${user.id}\">\${user.nama} - \${user.email}</option>`;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        userSelect.innerHTML = '<option value=\"\">Gagal memuat peserta</option>';
                    });
            } else {
                userSelect.innerHTML = '<option value=\"\">Pilih Training terlebih dahulu</option>';
            }
        });
    });
</script>
";

$content = str_replace("@endsection", $scriptAdd . "\n@endsection", $content);
file_put_contents("resources/views/admin/sertifikat/create.blade.php", $content);
echo "Done";
