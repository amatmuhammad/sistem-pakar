@extends('layouts.app')

@section('content')
<div class="container-xxl container-p-y">

    <h4 class="fw-bold mb-4">Diagnosa CBR</h4>

    <div class="card">
       <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">Diagnosis Hama & Penyakit Jagung Metode CBR</h5>

            <div class="d-flex gap-2">

                <button class="btn btn-primary btn-md"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDiagnosa">
                    <i class="bx bx-search"></i> Mulai Diagnosa
                </button>

                <form action="{{ route('diagnosa-cbr.clear-session') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-md">
                        <i class="bx bx-trash me-1"></i> Bersihkan Hasil Diagnosa
                    </button>
                </form>

            </div>

        </div>


        <div class="card-body">

           @if(session('hasil'))
                @php $data = session('hasil'); @endphp

                <div class="card border shadow-none mb-4">
                    <div class="card-header bg-label-success d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-success"><i class="bx bx-check-double me-1"></i> Hasil Analisis CBR</h5>
                        <small class="text-muted">{{ date('d M Y, H:i') }}</small>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="col-md-5 border-end">
                                <div class="mb-3">
                                    <label class="text-muted small text-uppercase fw-semibold">Penyakit Terdeteksi</label>
                                    <h3 class="text-primary mt-1">{{ $data->penyakit->nama_penyakit }}</h3>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="text-muted small text-uppercase fw-semibold">Tingkat Kepercayaan (Similarity)</label>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <div class="progress w-100" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                style="width: {{ $data->similarity_final * 100 }}%"></div>
                                        </div>
                                        <span class="fw-bold text-success">{{ number_format($data->similarity_final * 100, 2) }}%</span>
                                    </div>
                                </div>

                                <div class="alert bg-label-primary border-0 mt-4">
                                    <h6 class="alert-heading fw-bold mb-1"><i class="bx bx-bulb me-1"></i> Solusi Penanganan:</h6>
                                    <p class="mb-0 small">{{ $data->penyakit->solusi }}</p>
                                </div>
                            </div>

                            <div class="col-md-7 ps-md-4">
                                <label class="text-muted small text-uppercase fw-semibold mb-3 d-block">Gejala yang Anda Masukkan:</label>
                                <div class="list-group list-group-flush">
                                    @foreach($data->kasus->fitur as $f)
                                        <div class="list-group-item px-0 py-2 border-0 d-flex align-items-start">
                                            <i class="bx bx-check-square text-success me-2 mt-1"></i>
                                            <span>{{ $f->gejala->nama_gejala }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('assets/img/backgrounds/search.png') }}" 
                        alt="no-data" 
                        width="500"
                        class="mb-3" style="border-radius: 20px;">
                    <h5 class="text-muted">Siap untuk mendiagnosa?</h5>
                    <p class="text-muted">Klik tombol "Mulai Diagnosa" untuk memasukkan gejala tanaman jagung Anda.</p>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- <!-- MODAL DIAGNOSA -->
<div class="modal fade" id="modalDiagnosa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('diagnosa-cbr.proses') }}" method="POST" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Pilih Gejala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    @foreach($gejala as $g)
                    <div class="col-md-6 mb-2">
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="gejala[]"
                                   value="{{ $g->id }}">
                            <label class="form-check-label">
                                {{ $g->nama_gejala }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Proses Diagnosa</button>
            </div>

        </form>
    </div>
</div> --}}

<!-- MODAL DIAGNOSA -->
<div class="modal fade" id="modalDiagnosa" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form action="{{ route('diagnosa-cbr.proses') }}" method="POST" id="formDiagnosa" class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">

      @csrf

      <!-- Header -->
      <div class="modal-header border-0 px-4 py-4" style="background: linear-gradient(135deg, #40e300 0%, #FBEC5D 100%); border-radius: 1.25rem 1.25rem 0 0;">
        <div>
          <h5 class="modal-title fw-bold text-white mb-1">
            <i class="bi bi-stethoscope me-2" style="font-size: 1.3rem;"></i>Diagnosa Hama & Penyakit Jagung
          </h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body px-4 py-4">
        
        <!-- Search Bar Section -->
        <div class="mb-4">
          <div class="input-group" style="background: #f8f9fa; border-radius: 2rem; overflow: hidden; border: 2px solid #e9ecef;">
            <span class="input bg-transparent border-0 ps-4">
              <i class="bi bi-search text-primary fw-bold" style="font-size: 1.1rem;"></i>
            </span>
            <input type="text" id="searchGejala"
                  class="form-control border-0 bg-transparent ps-0"
                  placeholder="Cari gejala..."
                  autocomplete="off"
                  style="box-shadow: none !important; font-size: 0.95rem;">
            <button type="button" id="clearSearch"
                    class="btn border-0 text-danger fw-bold d-none pe-3"
                    aria-label="Hapus pencarian"
                    style="background: transparent;">
              <i class="bi bi-x-circle-fill" style="font-size: 1.1rem;"></i>
            </button>
          </div>
        </div>

        <!-- Actions & Count Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="d-flex gap-2">
            <button type="button" id="btnPilihSemua" class="btn btn-sm btn-info fw-medium text-white border border-primary rounded-pill px-3 py-2" style="transition: all 0.3s ease;">
              <i class="bi bi-check-all me-1"></i> Pilih Semua
            </button>
            <button type="button" id="btnHapusSemua" class="btn btn-sm btn-danger fw-medium text-white border border-danger rounded-pill px-3 py-2" style="transition: all 0.3s ease;">
              <i class="bi bi-x-circle me-1"></i> Hapus Semua
            </button>
          </div>
          <span id="countGejala" class="badge bg-danger rounded-pill px-3 py-2 fw-medium" style="font-size: 0.85rem;">0 gejala</span>
        </div>

        <!-- Symptoms List -->
        <div class="gejala-list" style="max-height: 380px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #ccc transparent;">
          <div class="row g-3">
            @foreach($gejala as $g)
            <div class="col-md-6 gejala-item">
              <label class="gejala-card d-flex align-items-center border border-2 rounded-3 p-3 mb-0 cursor-pointer" 
                     for="gejala-{{ $g->id }}"
                     style="cursor: pointer; transition: all 0.25s ease; border-color: #e9ecef; background: #fff; position: relative;">
                <input class="form-check-input flex-shrink-0 me-3 gejala-checkbox"
                       type="checkbox"
                       name="gejala[]"
                       id="gejala-{{ $g->id }}"
                       value="{{ $g->id }}"
                       style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
                <span class="fw-medium text-dark" style="font-size: 0.95rem;">{{ $g->nama_gejala }}</span>
                <i class="bi bi-check-circle-fill text-success ms-auto opacity-0" style="font-size: 1.1rem; transition: opacity 0.2s ease;"></i>
              </label>
            </div>
            @endforeach
          </div>
          
          <!-- Empty State -->
          <div class="text-center py-5 d-none" id="emptySearchState">
            <i class="bi bi-search text-muted" style="font-size: 2.5rem; opacity: 0.5;"></i>
            <p class="text-muted mt-2 small">Tidak ada gejala yang cocok</p>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer bg-light border-top-0 px-4 py-4" style="border-radius: 0 0 1.25rem 1.25rem;">
        <button type="button" class="btn btn-danger text-white fw-medium border px-4 rounded-pill" data-bs-dismiss="modal" style="transition: all 0.2s ease;">
          <i class="bi bi-x-lg me-1"></i> Batal
        </button>
        <button type="submit" class="btn btn-primary fw-medium px-4 rounded-pill" style="background: linear-gradient(135deg, #40e300 0%, #FBEC5D 100%); border-radius: 1.25rem 1.25rem 0 0;">
          <i class="bi bi-check-circle me-1"></i> Proses Diagnosa
        </button>
      </div>

    </form>
  </div>
</div>

<!-- CSS tambahan -->
<style>
  /* Modal styling */
  #modalDiagnosa .modal-content {
    border-radius: 1.25rem;
  }

  /* Scrollbar styling */
  .gejala-list::-webkit-scrollbar {
    width: 8px;
  }

  .gejala-list::-webkit-scrollbar-track {
    background: transparent;
  }

  .gejala-list::-webkit-scrollbar-thumb {
    background: #d0d0d0;
    border-radius: 10px;
    transition: background 0.3s ease;
  }

  .gejala-list::-webkit-scrollbar-thumb:hover {
    background: #999;
  }

  /* Search input styling */
  #searchGejala {
    background: transparent !important;
    border: none !important;
    transition: all 0.3s ease;
  }

  #searchGejala:focus {
    box-shadow: none !important;
    outline: none;
  }

  #searchGejala::placeholder {
    color: #adb5bd;
    font-weight: 500;
  }

  #searchGejala:focus::placeholder {
    opacity: 0.5;
  }

  /* Search container focus */
  #searchGejala:focus-within,
  #searchGejala:focus + #clearSearch {
    outline: none;
  }

  .input-group:focus-within {
    border-color: #667eea !important;
    background: linear-gradient(to right, rgba(102, 126, 234, 0.03), rgba(118, 75, 162, 0.03));
  }

  /* Clear button styling */
  #clearSearch {
    background: transparent;
    cursor: pointer;
    opacity: 0.8;
    transition: all 0.2s ease;
    border: none !important;
  }

  #clearSearch:hover {
    opacity: 1;
    transform: scale(1.2);
    color: #dc3545 !important;
  }

  #clearSearch:active {
    transform: scale(0.95);
  }

  /* Action buttons */
  #btnPilihSemua:hover,
  #btnHapusSemua:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
  }

  #btnPilihSemua:active,
  #btnHapusSemua:active {
    transform: translateY(0);
  }

  /* Gejala card styling */
  .gejala-card {
    background: #fff;
    border-color: #e9ecef;
    position: relative;
    overflow: hidden;
  }

  .gejala-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
  }

  .gejala-card:hover {
    border-color: #667eea;
    background: #f8f9fa;
    box-shadow: 0 0.25rem 0.75rem rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
  }

  .gejala-card:hover::before {
    opacity: 1;
  }

  /* Checkbox styling */
  .gejala-checkbox {
    accent-color: #667eea;
    border-color: #e9ecef !important;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .gejala-checkbox:hover {
    border-color: #667eea !important;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  .gejala-checkbox:checked {
    background-color: #667eea;
    border-color: #667eea !important;
  }

  .gejala-checkbox:checked:hover {
    background-color: #5a6fd8;
    border-color: #5a6fd8 !important;
  }

  /* Show check icon when checked */
  .gejala-checkbox:checked + span + .bi-check-circle-fill {
    opacity: 1 !important;
  }

  /* Count badge */
  #countGejala {
    font-size: 0.85rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    box-shadow: 0 0.25rem 0.5rem rgba(102, 126, 234, 0.2);
  }

  /* Footer buttons */
  .modal-footer .btn {
    font-weight: 500;
    transition: all 0.2s ease;
  }

  .modal-footer .btn-light:hover {
    background-color: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
  }

  .modal-footer .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
  }

  .modal-footer .btn-primary:active {
    transform: translateY(0);
  }

  /* Empty state */
  #emptySearchState {
    animation: fadeIn 0.3s ease;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Modal header gradient */
  #modalDiagnosa .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  }

  /* Responsive adjustments */
  @media (max-width: 576px) {
    .gejala-item {
      flex: 0 0 100% !important;
    }

    .gejala-list {
      max-height: 300px !important;
    }

    #searchGejala {
      font-size: 1rem;
    }
  }
</style>

<!-- JavaScript -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.gejala-checkbox');
    const countEl = document.getElementById('countGejala');
    const searchInput = document.getElementById('searchGejala');
    const clearBtn = document.getElementById('clearSearch');
    const form = document.getElementById('formDiagnosa');
    const items = document.querySelectorAll('.gejala-item');
    const emptyState = document.getElementById('emptySearchState');

    // Update jumlah gejala terpilih
    function updateCount() {
      const checked = document.querySelectorAll('.gejala-checkbox:checked').length;
      countEl.textContent = checked + ' gejala';
      
      // Ubah warna badge berdasarkan jumlah
      if (checked > 0) {
        countEl.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
        countEl.style.boxShadow = '0 0.25rem 0.5rem rgba(40, 167, 69, 0.2)';
      } else {
        countEl.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
        countEl.style.boxShadow = '0 0.25rem 0.5rem rgba(102, 126, 234, 0.2)';
      }
    }

    // Event listeners untuk semua checkbox
    checkboxes.forEach(cb => {
      cb.addEventListener('change', updateCount);
    });

    // Tombol "Pilih Semua"
    document.getElementById('btnPilihSemua').addEventListener('click', function (e) {
      e.preventDefault();
      checkboxes.forEach(cb => {
        const item = cb.closest('.gejala-item');
        if (item && item.style.display !== 'none') {
          cb.checked = true;
        }
      });
      updateCount();
    });

    // Tombol "Hapus Semua"
    document.getElementById('btnHapusSemua').addEventListener('click', function (e) {
      e.preventDefault();
      checkboxes.forEach(cb => {
        cb.checked = false;
      });
      updateCount();
    });

    // Filter pencarian
    function filterGejala() {
      const keyword = searchInput.value.toLowerCase().trim();
      let visibleCount = 0;
      let totalCount = 0;

      items.forEach(item => {
        const label = item.querySelector('span').textContent.toLowerCase();
        const isMatch = label.includes(keyword);
        item.style.display = isMatch ? '' : 'none';
        
        if (isMatch) {
          visibleCount++;
        }
        totalCount++;
      });

      // Toggle empty state
      if (keyword.length > 0 && visibleCount === 0) {
        emptyState.classList.remove('d-none');
      } else {
        emptyState.classList.add('d-none');
      }

      // Toggle clear button
      clearBtn.classList.toggle('d-none', keyword.length === 0);

      // Update visual feedback
      if (keyword.length > 0) {
        if (visibleCount === 0) {
          searchInput.style.borderColor = '#f8d7da';
        } else {
          searchInput.style.borderColor = '#d1ecf1';
        }
      }
    }

    searchInput.addEventListener('input', filterGejala);

    // Tombol clear
    clearBtn.addEventListener('click', function (e) {
      e.preventDefault();
      searchInput.value = '';
      searchInput.dispatchEvent(new Event('input'));
      searchInput.focus();
    });

    // Validasi form submit
    form.addEventListener('submit', function (e) {
      const checked = document.querySelectorAll('.gejala-checkbox:checked').length;
      if (checked === 0) {
        e.preventDefault();
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
        alertDiv.innerHTML = `
          
            <strong>Perhatian!</strong> Silakan pilih minimal satu gejala untuk melanjutkan diagnosa.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        form.querySelector('.modal-body').insertBefore(alertDiv, form.querySelector('.input-group').parentElement);
      }
    });

    // Initialize
    updateCount();
  });
</script>



@endsection
