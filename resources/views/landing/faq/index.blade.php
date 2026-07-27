@extends('layouts.landing')

@section('title', 'FAQ - Pertanyaan yang Sering Diajukan')

@section('content')

<!-- ============================================================
     FAQ SECTION
============================================================ -->
<section class="section-pad">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Search FAQ -->
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="faqSearch" 
                               placeholder="Cari pertanyaan...">
                        <button class="btn btn-primary" type="button" id="clearSearch">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- FAQ Accordion -->
                <div class="accordion" id="faqAccordion">
                    @foreach($faqs ?? [] as $index => $faq)
                    <div class="accordion-item border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" 
                                    type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#collapse{{ $index }}" 
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" 
                                    aria-controls="collapse{{ $index }}">
                                <i class="bi bi-question-circle text-primary me-2"></i>
                                {{ $faq['question'] }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" 
                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                             aria-labelledby="heading{{ $index }}" 
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                <i class="bi bi-arrow-right-short text-primary me-1"></i>
                                {{ $faq['answer'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Still Have Questions -->
                <div class="text-center mt-5 p-4 bg-light rounded-4">
                    <i class="bi bi-chat-dots fs-1 text-primary d-block mb-3"></i>
                    <h5 class="fw-bold">Masih Punya Pertanyaan?</h5>
                    <p class="text-muted">Tim kami siap membantu Anda. Hubungi kami melalui:</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center mt-3">
                        <a href="{{ route('landing.kontak.index') }}" class="btn btn-primary">
                            <i class="bi bi-envelope me-1"></i> Halaman Kontak
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@push('styles')
<style>
    .accordion-button {
        background: #fff;
        color: #1a2236;
        font-weight: 600;
        padding: 1.2rem 1.5rem;
        box-shadow: none !important;
        border-radius: 0.75rem !important;
    }
    .accordion-button:not(.collapsed) {
        background: #f8fafc;
        color: var(--primary);
    }
    .accordion-button:focus {
        box-shadow: none !important;
        border-color: transparent !important;
    }
    .accordion-button::after {
        background-size: 1rem;
    }
    .accordion-body {
        padding: 1.2rem 1.5rem;
        background: #f8fafc;
        border-radius: 0 0 0.75rem 0.75rem;
    }
    .accordion-item {
        border: 1px solid #e8ecf1 !important;
        border-radius: 0.75rem !important;
    }
    .accordion-item:last-of-type {
        border-bottom: 1px solid #e8ecf1 !important;
    }
    
    .card-feature {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-feature:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    }
    
    .hero {
        background: linear-gradient(135deg, #1a2236 0%, #2a3654 50%, #1a2236 100%);
        color: #fff;
    }
    
    .icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
    }
    .icon-primary { background: #eaf1fd; color: var(--primary); }
    .icon-success { background: #dff6e8; color: #28c76f; }
    .icon-info { background: #e0f4fe; color: #17a2b8; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================================
    // SEARCH FAQ
    // ============================================================
    const searchInput = document.getElementById('faqSearch');
    const clearBtn = document.getElementById('clearSearch');
    const accordionItems = document.querySelectorAll('.accordion-item');

    function filterFAQs(searchTerm) {
        const term = searchTerm.toLowerCase().trim();
        let hasVisible = false;

        accordionItems.forEach(function(item) {
            const question = item.querySelector('.accordion-button').textContent.toLowerCase();
            const answer = item.querySelector('.accordion-body').textContent.toLowerCase();
            
            if (term === '' || question.includes(term) || answer.includes(term)) {
                item.style.display = 'block';
                hasVisible = true;
            } else {
                item.style.display = 'none';
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        let noResultMsg = document.getElementById('noResultMsg');
        if (!hasVisible && term !== '') {
            if (!noResultMsg) {
                noResultMsg = document.createElement('div');
                noResultMsg.id = 'noResultMsg';
                noResultMsg.className = 'text-center py-4';
                noResultMsg.innerHTML = `
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted">Tidak ada pertanyaan yang sesuai dengan "<strong>${term}</strong>"</p>
                    <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('faqSearch').value=''; filterFAQs('');">
                        Reset Pencarian
                    </button>
                `;
                document.getElementById('faqAccordion').after(noResultMsg);
            } else {
                noResultMsg.style.display = 'block';
                noResultMsg.querySelector('strong').textContent = term;
            }
        } else if (noResultMsg) {
            noResultMsg.style.display = 'none';
        }
    }

    // Event listener untuk search
    searchInput.addEventListener('input', function() {
        filterFAQs(this.value);
    });

    // Clear button
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        filterFAQs('');
        searchInput.focus();
    });

    // ============================================================
    // AUTO CLOSE ALERTS
    // ============================================================
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // ============================================================
    // EXPAND FIRST ITEM ON MOBILE
    // ============================================================
    if (window.innerWidth < 768) {
        const firstAccordion = document.querySelector('.accordion-collapse');
        if (firstAccordion) {
            // Already expanded by default
        }
    }
});
</script>
@endpush
@endsection