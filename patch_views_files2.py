import re

def patch_file(path):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    # Search for NPWP block manually to avoid regex quote hell
    start_npwp = content.find("@if($user->npwp_file)")
    end_npwp = content.find("'required' }}>", start_npwp)
    if end_npwp != -1:
        end_npwp += len("'required' }}>")
        
        npwp_new = """@if($user->npwp_file && is_array($user->npwp_file))
                                            <div class="mb-3 d-flex flex-wrap gap-2">
                                                @foreach($user->npwp_file as $idx => $file)
                                                    @php $ext = pathinfo($file, PATHINFO_EXTENSION); @endphp
                                                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                                        <img src="{{ route('profile.document', ['type' => 'npwp', 'userId' => $user->id, 'index' => $idx]) }}" alt="NPWP" class="img-thumbnail" style="max-height: 150px;">
                                                    @else
                                                        <a href="{{ route('profile.document', ['type' => 'npwp', 'userId' => $user->id, 'index' => $idx]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-file-earmark-pdf"></i> Dokumen NPWP {{ $idx + 1 }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        <input type="file" multiple class="form-control @error('npwp_file') is-invalid @enderror" name="npwp_file[]" accept=".jpg,.jpeg,.png,.pdf" {{ $user->npwp_file ? '' : 'required' }}>"""
        
        content = content[:start_npwp] + npwp_new + content[end_npwp:]


    start_produk = content.find("@if($user->file_produk)")
    end_produk = content.find("'required' }}>", start_produk)
    if end_produk != -1:
        end_produk += len("'required' }}>")
        
        produk_new = """@if($user->file_produk && is_array($user->file_produk))
                                            <div class="mb-3 d-flex flex-wrap gap-2">
                                                @foreach($user->file_produk as $idx => $file)
                                                    @php $ext = pathinfo($file, PATHINFO_EXTENSION); @endphp
                                                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                                        <img src="{{ route('profile.document', ['type' => 'produk', 'userId' => $user->id, 'index' => $idx]) }}" alt="Produk" class="img-thumbnail" style="max-height: 150px;">
                                                    @else
                                                        <a href="{{ route('profile.document', ['type' => 'produk', 'userId' => $user->id, 'index' => $idx]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-file-earmark-arrow-down"></i> File Produk {{ $idx + 1 }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        <input type="file" multiple class="form-control @error('file_produk') is-invalid @enderror" name="file_produk[]" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" {{ $user->file_produk ? '' : 'required' }}>"""
        
        content = content[:start_produk] + produk_new + content[end_produk:]

    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Patched {path}")

patch_file("resources/views/peserta/profile/index.blade.php")
patch_file("resources/views/admin/users/edit.blade.php")
