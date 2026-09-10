import re

def patch_file(path):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    npwp_old = re.compile(r'<label class="text-muted small fw-semibold text-uppercase d-block mb-2">NPWP</label>.*?@endif', re.DOTALL)
    npwp_new = """<label class="text-muted small fw-semibold text-uppercase d-block mb-2">NPWP</label>
                                @if($user->npwp_file && is_array($user->npwp_file) && count($user->npwp_file) > 0)
                                    <div class="d-flex flex-column gap-2">
                                    @foreach($user->npwp_file as $idx => $file)
                                        <a href="{{ route('profile.document', ['type' => 'npwp', 'userId' => $user->id, 'index' => $idx]) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 text-start">
                                            <i class="bi bi-file-earmark-pdf me-1"></i> NPWP {{ $idx + 1 }}
                                        </a>
                                    @endforeach
                                    </div>
                                @else
                                    <span class="badge bg-secondary">Belum diupload</span>
                                @endif"""
                                
    produk_old = re.compile(r'<label class="text-muted small fw-semibold text-uppercase d-block mb-2">File Produk \(Katalog/Brosur\)</label>.*?@endif', re.DOTALL)
    produk_new = """<label class="text-muted small fw-semibold text-uppercase d-block mb-2">File Produk (Katalog/Brosur)</label>
                                @if($user->file_produk && is_array($user->file_produk) && count($user->file_produk) > 0)
                                    <div class="d-flex flex-column gap-2">
                                    @foreach($user->file_produk as $idx => $file)
                                        <a href="{{ route('profile.document', ['type' => 'produk', 'userId' => $user->id, 'index' => $idx]) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 text-start">
                                            <i class="bi bi-file-earmark-arrow-down me-1"></i> File Produk {{ $idx + 1 }}
                                        </a>
                                    @endforeach
                                    </div>
                                @else
                                    <span class="badge bg-secondary">Belum diupload</span>
                                @endif"""

    content = npwp_old.sub(npwp_new, content)
    content = produk_old.sub(produk_new, content)

    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Patched {path}")

patch_file("resources/views/admin/users/show.blade.php")
