@extends('layouts.admin')

@section('title', 'Menunggu Penerbitan Sertifikat')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-clock-history" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Menunggu Penerbitan</h1>
                <p class="text-muted mb-0">Terbitkan sertifikat untuk peserta yang telah lulus kuis pelatihan.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mt-3 shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.sertifikat.menunggu') }}" method="GET" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <label for="training_id" class="form-label fw-bold">Pilih Pelatihan:</label>
                        <select name="training_id" id="training_id" class="form-select" required>
                            <option value="">-- Pilih Pelatihan --</option>
                            @foreach($trainings as $training)
                                <option value="{{ $training->id }}" {{ $trainingId == $training->id ? 'selected' : '' }}>
                                    {{ $training->judul }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Tampilkan Peserta Lulus
                        </button>
                    </div>
                </div>
            </form>

            @if($trainingId)
                @if($participants->isEmpty())
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Tidak ada peserta yang menunggu sertifikat untuk pelatihan ini, atau belum ada kuis yang di-publish.
                    </div>
                @else
                    <form action="{{ route('admin.sertifikat.terbitkan-massal') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="training_id" value="{{ $trainingId }}">
                        
                        <h5 class="mt-4 mb-3"><i class="bi bi-people"></i> Daftar Peserta Lulus</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;" class="text-center">
                                            <input class="form-check-input" type="checkbox" id="selectAll" checked>
                                        </th>
                                        <th>Nama Peserta</th>
                                        <th>Status Kelayakan</th>
                                        <th>Tanggal Lulus Terakhir</th>
                                        <th>Rata-rata Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($participants as $user)
                                    <tr>
                                        <td class="text-center">
                                            @if($user->status_sertifikat === 'Layak Diterbitkan')
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
                                            @if($user->status_sertifikat === 'Layak Diterbitkan')
                                                <span class="badge bg-success">Layak Diterbitkan</span>
                                            @elseif($user->status_sertifikat === 'Diterbitkan')
                                                <span class="badge bg-primary">Diterbitkan</span>
                                            @else
                                                <span class="badge bg-danger">{{ $user->status_sertifikat }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->passed_at ? date('d M Y H:i', strtotime($user->passed_at)) : '-' }}</td>
                                        <td><span class="badge bg-info">{{ number_format($user->final_score ?? 0, 1) }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3"><i class="bi bi-file-earmark-check"></i> Data Sertifikat</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Template Sertifikat (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="template_sertifikat" id="template_sertifikat" class="form-control" accept=".jpg,.jpeg,.png" required>
                                <small class="text-muted">Maksimal 4MB. Format: JPG, PNG.</small>
                                <div class="mt-2" id="templatePreviewContainer" style="display: none;">
                                    <img id="templatePreview" src="#" alt="Preview Template" style="max-width: 100%; max-height: 200px; border-radius: 5px; border: 1px solid #ccc; padding: 3px;">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanda Tangan (Wajib) <span class="text-danger">*</span></label>
                                <input type="file" name="tanda_tangan" id="tanda_tangan" class="form-control" accept=".png" required>
                                <small class="text-muted">Maksimal 1MB. Format: PNG Transparan.</small>
                                <div class="mt-2" id="signaturePreviewContainer" style="display: none;">
                                    <img id="signaturePreview" src="#" alt="Preview Tanda Tangan" style="max-width: 100%; max-height: 150px; border-radius: 5px; border: 1px solid #ccc; padding: 3px; object-fit: contain;">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Penandatangan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_penandatangan" class="form-control" placeholder="Contoh: Dr. Budi Santoso, M.Si" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Penerbit <span class="text-danger">*</span></label>
                                <input type="text" name="penerbit" class="form-control" value="Dinas Koperasi, Perindustrian dan Perdagangan Provinsi Sulawesi Barat" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Terbit <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_terbit" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Berlaku Sampai</label>
                                <input type="date" name="tanggal_berlaku_sampai" class="form-control">
                                <small class="text-muted">Kosongkan jika berlaku selamanya.</small>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-bold">Format Nomor Sertifikat <span class="text-danger">*</span></label>
                                <input type="text" name="format_nomor" class="form-control" value="{no}/SERT/KOPERINDAG/{bulan_romawi}/{tahun}" required>
                                <small class="text-muted">Gunakan placeholder: <code>{no}</code>, <code>{bulan_romawi}</code>, <code>{tahun}</code>. Contoh: {no}/SERT/KOPERINDAG/{bulan_romawi}/{tahun}</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Mulai Nomor Urut <span class="text-danger">*</span></label>
                                <input type="number" name="nomor_awal" class="form-control" value="1" min="1" required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Contoh: Diberikan atas partisipasi dan kelulusan..."></textarea>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-send-check"></i> Terbitkan Sertifikat
                            </button>
                        </div>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select All Checkbox logic
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (!this.checked) {
                        selectAll.checked = false;
                    } else if (document.querySelectorAll('.user-checkbox:checked').length === checkboxes.length) {
                        selectAll.checked = true;
                    }
                });
            });
        }

        // Preview images logic
        function setupPreview(inputId, previewId, containerId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const container = document.getElementById(containerId);
            
            if (input) {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            container.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        container.style.display = 'none';
                    }
                });
            }
        }
        
        setupPreview('template_sertifikat', 'templatePreview', 'templatePreviewContainer');
        setupPreview('tanda_tangan', 'signaturePreview', 'signaturePreviewContainer');
    });
</script>
@endsection
