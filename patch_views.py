import re

ui_template = """
                                    <!-- INFORMASI USAHA ONLINE -->
                                    <div class="col-12 mt-4">
                                        <div class="card bg-light border-0 shadow-sm">
                                            <div class="card-header bg-primary text-white fw-bold">
                                                <i class="bi bi-globe me-2"></i>INFORMASI USAHA ONLINE
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Judul Usaha <span class="text-muted">(Opsional)</span></label>
                                                        <input type="text" class="form-control" name="judul_usaha_online" value="{{ old('judul_usaha_online', $user->judul_usaha_online) }}" placeholder="Contoh: Toko Kue Andi">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Website Usaha <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="website_usaha" value="{{ old('website_usaha', $user->website_usaha) }}" placeholder="https://www.contoh.com">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Facebook <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="facebook_usaha" value="{{ old('facebook_usaha', $user->facebook_usaha) }}" placeholder="https://facebook.com/contoh">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Instagram <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="instagram_usaha" value="{{ old('instagram_usaha', $user->instagram_usaha) }}" placeholder="https://instagram.com/contoh">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">TikTok <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="tiktok_usaha" value="{{ old('tiktok_usaha', $user->tiktok_usaha) }}" placeholder="https://tiktok.com/@contoh">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- MARKETPLACE -->
                                    <div class="col-12 mt-4">
                                        <div class="card bg-light border-0 shadow-sm">
                                            <div class="card-header bg-secondary text-white fw-bold">
                                                <i class="bi bi-shop me-2"></i>MARKETPLACE YANG DIGUNAKAN
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Shopee <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="shopee" value="{{ old('shopee', $user->shopee) }}" placeholder="https://shopee.co.id/contoh">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Tokopedia <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="tokopedia" value="{{ old('tokopedia', $user->tokopedia) }}" placeholder="https://www.tokopedia.com/contoh">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Lazada <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="lazada" value="{{ old('lazada', $user->lazada) }}" placeholder="https://www.lazada.co.id/shop/contoh">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Blibli <span class="text-muted">(Opsional)</span></label>
                                                        <input type="url" class="form-control" name="blibli" value="{{ old('blibli', $user->blibli) }}" placeholder="https://www.blibli.com/merchant/contoh">
                                                    </div>
                                                    
                                                    <div class="col-12 mt-4 border-top pt-3">
                                                        <label class="form-label fw-bold">Marketplace Lainnya <span class="text-muted fw-normal">(Opsional)</span></label>
                                                        <div id="marketplace-container">
                                                            @php
                                                                $oldM = old('marketplace_lainnya_nama');
                                                                $oldL = old('marketplace_lainnya_link');
                                                                $dbM = $user->marketplace_lainnya ?? [];
                                                            @endphp

                                                            @if($oldM && is_array($oldM))
                                                                @foreach($oldM as $idx => $n)
                                                                    <div class="row g-2 mb-2 mp-row">
                                                                        <div class="col-md-5">
                                                                            <input type="text" class="form-control" name="marketplace_lainnya_nama[]" value="{{ $n }}" placeholder="Nama Marketplace (cth: Bukalapak)">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="url" class="form-control" name="marketplace_lainnya_link[]" value="{{ $oldL[$idx] ?? '' }}" placeholder="Link Marketplace (https://...)">
                                                                        </div>
                                                                        <div class="col-md-1 d-flex align-items-center">
                                                                            <button type="button" class="btn btn-danger btn-sm w-100 remove-mp"><i class="bi bi-trash"></i></button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @elseif(!empty($dbM) && is_array($dbM))
                                                                @foreach($dbM as $item)
                                                                    <div class="row g-2 mb-2 mp-row">
                                                                        <div class="col-md-5">
                                                                            <input type="text" class="form-control" name="marketplace_lainnya_nama[]" value="{{ $item['nama'] ?? '' }}" placeholder="Nama Marketplace (cth: Bukalapak)">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="url" class="form-control" name="marketplace_lainnya_link[]" value="{{ $item['link'] ?? '' }}" placeholder="Link Marketplace (https://...)">
                                                                        </div>
                                                                        <div class="col-md-1 d-flex align-items-center">
                                                                            <button type="button" class="btn btn-danger btn-sm w-100 remove-mp"><i class="bi bi-trash"></i></button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-mp-btn"><i class="bi bi-plus-circle me-1"></i> Tambahkan Marketplace Lainnya</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
"""

js_template = """
    // Add Marketplace Lainnya dynamically
    $('#add-mp-btn').on('click', function() {
        let html = `
            <div class="row g-2 mb-2 mp-row">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="marketplace_lainnya_nama[]" placeholder="Nama Marketplace (cth: Bukalapak)">
                </div>
                <div class="col-md-6">
                    <input type="url" class="form-control" name="marketplace_lainnya_link[]" placeholder="Link Marketplace (https://...)">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm w-100 remove-mp"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        `;
        $('#marketplace-container').append(html);
    });

    $(document).on('click', '.remove-mp', function() {
        $(this).closest('.mp-row').remove();
    });
"""

def process_file(path):
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()

    # Match the block from <div class="col-12 col-md-6"> Website Usaha up to marketplace textarea closing div
    # Note: in index.blade.php:
    # <div class="col-12 col-md-6">\s*<label class="form-label fw-semibold">Website Usaha.*?<textarea class="form-control" name="marketplace" rows="2">.*?</textarea>\s*</div>
    pattern = re.compile(r'<div class="col-12 col-md-6">\s*<label class="form-label fw-semibold">Website Usaha.*?</textarea>\s*</div>', re.DOTALL)
    
    # Check if we can find it
    if pattern.search(content):
        content = pattern.sub(ui_template, content, count=1)
        
        # Append JS to the end before </script>
        content = content.replace("</script>", js_template + "\n</script>", 1)
        
        with open(path, "w", encoding="utf-8") as f:
            f.write(content)
        print(f"Patched {path}")
    else:
        print(f"Could not find target block in {path}")

process_file("resources/views/peserta/profile/index.blade.php")
process_file("resources/views/admin/users/edit.blade.php")

