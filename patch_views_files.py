import re

npwp_old = re.compile(r'@if\(\$user->npwp_file\).*?<input type="file" class="form-control @error\('npwp_file'\) is-invalid @enderror" name="npwp_file" accept="\.jpg,\.jpeg,\.png,\.pdf" \{\{ \$user->npwp_file \? ''' : 'required' \}\}>', re.DOTALL)

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

produk_old = re.compile(r'@if\(\$user->file_produk\).*?<input type="file" class="form-control @error\('file_produk'\) is-invalid @enderror" name="file_produk" accept="\.jpg,\.jpeg,\.png,\.pdf,\.doc,\.docx" \{\{ \$user->file_produk \? ''' : 'required' \}\}>', re.DOTALL)

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

def patch_file(path):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    content = npwp_old.sub(npwp_new, content)
    content = produk_old.sub(produk_new, content)
    
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Patched {path}")

patch_file("resources/views/peserta/profile/index.blade.php")
patch_file("resources/views/admin/users/edit.blade.php")
