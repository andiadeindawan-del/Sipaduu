import re

path = r"c:\laragon\www\SIPADUU\resources\views\peserta\profile\index.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Hapus Jenis Usaha
jenis_usaha_pattern = re.compile(r'\s*<div class="col-12 col-md-6">\s*<label class="form-label fw-semibold">Jenis Usaha.*?</div>', re.DOTALL)
content = jenis_usaha_pattern.sub("", content, count=1)

# 2. Ganti KBLI Section
kbli_section_new = """<!-- HIERARCHICAL KBLI SECTION -->
                                    <div class="col-12 mt-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-briefcase me-2"></i>DATA USAHA (KBLI) <span class="text-danger">*</span></h6>
                                        <p class="text-muted small mb-3">Peserta wajib memiliki minimal satu KBLI Utama. Anda dapat menambahkan beberapa jenis usaha lain yang relevan.</p>
                                        
                                        <div id="kbli-repeater-container">
                                            <!-- Baris KBLI akan di-generate via JavaScript -->
                                        </div>
                                        
                                        <div class="mt-3 mb-4">
                                            <button type="button" class="btn btn-sm btn-outline-primary fw-bold" id="btn-add-usaha">
                                                <i class="bi bi-plus-lg"></i> Tambahkan Usaha Lainnya
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Data Karyawan -->"""
content = re.sub(r'<!-- KBLI Section -->.*?<!-- Data Karyawan -->', kbli_section_new, content, flags=re.DOTALL)

# 3. Ganti script KBLI
script_new = """
    // === HIERARCHICAL KBLI LOGIC ===
    let kbliIndex = 0;
    const userKblis = {!! $user->userKblis->load('kbli')->toJson() !!};
    
    function fetchCategories(selectElem, selectedValue = null) {
        $.ajax({
            url: '/api/kbli/categories',
            method: 'GET',
            success: function(res) {
                selectElem.empty().append('<option value="">Pilih Kategori</option>');
                res.forEach(item => {
                    let selected = (selectedValue === item.kategori_kode) ? 'selected' : '';
                    selectElem.append(`<option value="${item.kategori_kode}" ${selected}>${item.kategori_kode} - ${item.kategori_nama}</option>`);
                });
            }
        });
    }

    function fetchGolongans(kategoriKode, selectElem, selectedValue = null) {
        if (!kategoriKode) {
            selectElem.empty().append('<option value="">Pilih Golongan</option>').prop('disabled', true);
            return;
        }
        $.ajax({
            url: '/api/kbli/golongans',
            method: 'GET',
            data: { kategori: kategoriKode },
            success: function(res) {
                selectElem.empty().append('<option value="">Pilih Golongan</option>').prop('disabled', false);
                res.forEach(item => {
                    let selected = (selectedValue === item.golongan_pokok_kode) ? 'selected' : '';
                    selectElem.append(`<option value="${item.golongan_pokok_kode}" ${selected}>${item.golongan_pokok_kode} - ${item.golongan_pokok_nama}</option>`);
                });
            }
        });
    }

    function addKbliRow(data = null) {
        let isUtama = data ? data.is_utama : (kbliIndex === 0);
        let id = kbliIndex++;
        let labelUsaha = (id === 0) ? 'JENIS USAHA UTAMA' : 'USAHA LAINNYA #' + (id + 1);
        
        let html = `
        <div class="card mb-3 kbli-row shadow-sm border-0" data-index="${id}">
            <div class="card-header bg-light border-bottom-0 d-flex justify-content-between align-items-center">
                <span class="fw-bold text-primary"><i class="bi bi-tag-fill me-2"></i>${labelUsaha}</span>
                ${id > 0 ? '<button type="button" class="btn btn-sm btn-outline-danger border-0 btn-remove-row"><i class="bi bi-trash"></i> Hapus</button>' : ''}
            </div>
            <div class="card-body bg-white border">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select select-kategori" required>
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold small">Golongan <span class="text-danger">*</span></label>
                        <select class="form-select select-golongan" required disabled>
                            <option value="">Pilih Golongan</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">KBLI / Kegiatan Usaha <span class="text-danger">*</span></label>
                        <select class="form-control select-kbli" required disabled>
                            ${data && data.kbli ? `<option value="${data.kbli.id}" selected>${data.kbli.kode} - ${data.kbli.judul}</option>` : '<option value="">Cari kode, judul, atau uraian KBLI...</option>'}
                        </select>
                        <input type="hidden" name="kbli_id[]" class="kbli-id-hidden" value="${data ? data.kbli_id : ''}">
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded text-muted small uraian-box">
                            ${data && data.kbli ? data.kbli.uraian : 'Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.'}
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="form-check">
                            <input class="form-check-input kbli-utama-radio" type="radio" name="kbli_utama" value="${data ? data.kbli_id : id}" id="utama_${id}" ${isUtama ? 'checked' : ''} required>
                            <label class="form-check-label fw-bold text-dark" for="utama_${id}">
                                ⭐ Jadikan KBLI Utama
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
        
        let $row = $(html);
        $('#kbli-repeater-container').append($row);
        
        let $selectKategori = $row.find('.select-kategori');
        let $selectGolongan = $row.find('.select-golongan');
        let $selectKbli = $row.find('.select-kbli');
        let $uraianBox = $row.find('.uraian-box');
        let $hiddenId = $row.find('.kbli-id-hidden');
        let $radioUtama = $row.find('.kbli-utama-radio');
        
        // Fetch init categories
        let initKat = data && data.kbli ? data.kbli.kategori_kode : null;
        let initGol = data && data.kbli ? data.kbli.golongan_pokok_kode : null;
        fetchCategories($selectKategori, initKat);
        if (initKat) {
            fetchGolongans(initKat, $selectGolongan, initGol);
            $selectKbli.prop('disabled', false);
        }

        // Kategori Change Event
        $selectKategori.on('change', function() {
            let val = $(this).val();
            $selectGolongan.empty().append('<option value="">Pilih Golongan</option>').prop('disabled', true);
            $selectKbli.empty().prop('disabled', true);
            $hiddenId.val('');
            $radioUtama.val(id); // reset fallback
            $uraianBox.html('Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.');
            if (val) fetchGolongans(val, $selectGolongan);
        });

        // Golongan Change Event
        $selectGolongan.on('change', function() {
            let val = $(this).val();
            $selectKbli.empty().prop('disabled', !val);
            $hiddenId.val('');
            $radioUtama.val(id);
            $uraianBox.html('Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.');
        });
        
        // Init Select2 for this row
        $selectKbli.select2({
            ajax: {
                url: '/api/kbli/search',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return { 
                        q: params.term,
                        kategori: $selectKategori.val(),
                        golongan: $selectGolongan.val()
                    };
                },
                processResults: function (res) {
                    return { results: res };
                }
            },
            placeholder: '🔍 Cari kode, judul, atau uraian KBLI...',
            minimumInputLength: 2,
            templateResult: function (kbli) {
                if (kbli.loading) return kbli.text;
                return $(
                    "<div class='p-1'>" +
                    "<div class='fw-bold text-dark'>" + kbli.text + "</div>" +
                    "<div class='small text-muted mt-1'>Uraian: " + (kbli.uraian || '-') + "</div>" +
                    "</div>"
                );
            },
            templateSelection: function (kbli) {
                return kbli.text || kbli.id || '🔍 Cari kode, judul, atau uraian...';
            }
        }).on('select2:select', function(e) {
            let item = e.params.data;
            $hiddenId.val(item.id);
            $radioUtama.val(item.id); // set radio to actual ID
            $uraianBox.html(`<strong>Uraian:</strong><br/>${item.uraian}`);
        }).on('select2:clear', function() {
            $hiddenId.val('');
            $radioUtama.val(id);
            $uraianBox.html('Pilih KBLI untuk melihat deskripsi uraian kegiatan usaha.');
        });
    }

    // Initialize existing or empty row
    if (userKblis && userKblis.length > 0) {
        userKblis.forEach(function(uk) {
            addKbliRow(uk);
        });
    } else {
        addKbliRow();
    }

    $('#btn-add-usaha').on('click', function() {
        addKbliRow();
    });

    $(document).on('click', '.btn-remove-row', function() {
        var row = $(this).closest('.kbli-row');
        var isUtama = row.find('.kbli-utama-radio').is(':checked');
        row.remove();
        
        // Jika Utama dihapus, jadikan row pertama sebagai utama
        if (isUtama && $('.kbli-row').length > 0) {
            $('.kbli-row').first().find('.kbli-utama-radio').prop('checked', true);
        }
    });
"""
content = re.sub(r'// Inisialisasi Select2 untuk Input Pencarian KBLI.*?\}\);', script_new, content, flags=re.DOTALL)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated index.blade.php")
