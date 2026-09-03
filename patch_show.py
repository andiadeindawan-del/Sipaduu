import re

ui = """
                        <!-- SALURAN PEMASARAN ONLINE -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-globe me-2"></i>SALURAN PEMASARAN ONLINE</h6>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="detail-item h-100 p-3 bg-light rounded border">
                                <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Usaha Online</h6>
                                
                                <div class="mb-2">
                                    <label class="text-muted small fw-semibold text-uppercase d-block">Judul Usaha</label>
                                    <span class="fw-bold text-dark">{{ $user->judul_usaha_online ?? '-' }}</span>
                                </div>
                                
                                <div class="mb-2">
                                    <label class="text-muted small fw-semibold text-uppercase d-block">Website</label>
                                    @if($user->website_usaha)
                                        <a href="{{ $user->website_usaha }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                            <i class="bi bi-link-45deg me-1"></i>Kunjungi Website
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>

                                <div class="mb-2">
                                    <label class="text-muted small fw-semibold text-uppercase d-block">Facebook</label>
                                    @if($user->facebook_usaha)
                                        <a href="{{ $user->facebook_usaha }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                            <i class="bi bi-facebook me-1"></i>Facebook
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>

                                <div class="mb-2">
                                    <label class="text-muted small fw-semibold text-uppercase d-block">Instagram</label>
                                    @if($user->instagram_usaha)
                                        <a href="{{ $user->instagram_usaha }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                            <i class="bi bi-instagram me-1"></i>Instagram
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>

                                <div class="mb-0">
                                    <label class="text-muted small fw-semibold text-uppercase d-block">TikTok</label>
                                    @if($user->tiktok_usaha)
                                        <a href="{{ $user->tiktok_usaha }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                            <i class="bi bi-tiktok me-1"></i>TikTok
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="detail-item h-100 p-3 bg-light rounded border">
                                <h6 class="fw-bold mb-3 border-bottom pb-2">Marketplace Yang Digunakan</h6>
                                
                                <div class="mb-2">
                                    <label class="text-muted small fw-semibold text-uppercase d-block">Shopee</label>
                                    @if($user->shopee)
                                        <a href="{{ $user->shopee }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                            <i class="bi bi-shop me-1"></i>Kunjungi Shopee
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>

                                <div class="mb-2">
                                    <label class="text-muted small fw-semibold text-uppercase d-block">Tokopedia</label>
                                    @if($user->tokopedia)
                                        <a href="{{ $user->tokopedia }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                            <i class="bi bi-shop me-1"></i>Kunjungi Tokopedia
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>

                                <div class="mb-2">
                                    <label class="text-muted small fw-semibold text-uppercase d-block">Lazada</label>
                                    @if($user->lazada)
                                        <a href="{{ $user->lazada }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                            <i class="bi bi-shop me-1"></i>Kunjungi Lazada
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>

                                <div class="mb-2">
                                    <label class="text-muted small fw-semibold text-uppercase d-block">Blibli</label>
                                    @if($user->blibli)
                                        <a href="{{ $user->blibli }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                            <i class="bi bi-shop me-1"></i>Kunjungi Blibli
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>

                                <div class="mb-0">
                                    <label class="text-muted small fw-semibold text-uppercase d-block">Marketplace Lainnya</label>
                                    @php
                                        $mps = $user->marketplace_lainnya;
                                    @endphp
                                    @if(!empty($mps) && is_array($mps) && count($mps) > 0)
                                        <ul class="list-unstyled mb-0">
                                            @foreach($mps as $mp)
                                                <li>
                                                    @if(!empty($mp['link']))
                                                        <a href="{{ $mp['link'] }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                                            <i class="bi bi-link me-1"></i>{{ $mp['nama'] ?? 'Kunjungi Marketplace' }}
                                                        </a>
                                                    @else
                                                        <span class="fw-bold">{{ $mp['nama'] ?? '-' }}</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
"""

with open("resources/views/admin/users/show.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

# Pattern to replace from <div class="col-12 col-md-6"> containing Website Usaha up to Marketplace
# <div class="col-12 col-md-6">\s*<div class="detail-item">\s*<label class="text-muted small fw-semibold text-uppercase">Website Usaha</label>.*?</p>\s*</div>\s*</div>
# we have Media Sosial in between, and Marketplace.
pattern = re.compile(r'<div class="col-12 col-md-6">\s*<div class="detail-item">\s*<label class="text-muted small fw-semibold text-uppercase">Website Usaha.*?</p>\s*</div>\s*</div>', re.DOTALL)
content = pattern.sub(ui, content, count=1)

with open("resources/views/admin/users/show.blade.php", "w", encoding="utf-8") as f:
    f.write(content)
print("Show patched")
