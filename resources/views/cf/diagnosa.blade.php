@extends('layouts.app')

@section('content')
<div class="container-xxl container-p-y">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h4 class="fw-bold mb-1">Diagnosa Certainty Factor</h4>
            <small class="text-muted">Pilih gejala dan tingkat keyakinan untuk menganalisis hama & penyakit</small>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Diagnosa penyakit dan hama tanaman jagung menggunakan metode CF</h5> <!-- Sesuaikan judul card jika ada -->
            
            <div class="d-flex align-items-center gap-2 ms-auto">
                <!-- Tombol Mulai Diagnosa -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDiagnosa">
                    <i class="bx bx-plus me-1"></i> Mulai Diagnosa
                </button>
                
                <!-- Tombol Bersihkan Riwayat -->
                <form action="{{ route('diagnosa-cf.clear-session') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bx bx-trash me-1"></i> Bersihkan Hasil Diagnosa
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if(session('hasil'))
            @php $res = session('hasil'); @endphp
                <div class="card shadow-none border border-primary">
                    <div class="card-body row p-4 g-0">
                        <div class="col-md-6 border-end-md">
                            <label class="text-muted small fw-bold text-uppercase">Hasil Analisis</label>
                            <h2 class="text-primary fw-bold mb-0">{{ $res->penyakit->nama_penyakit }}</h2>
                            <small class="text-muted">{{ $res->penyakit->jenis ?? '' }}</small>

                            <div class="mt-4">
                                <label class="text-muted small fw-bold">Tingkat Keyakinan</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="progress w-100" style="height: 15px;">
                                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
                                             style="width: {{ $res->cf_final * 100 }}%"></div>
                                    </div>
                                    <h4 class="mb-0 fw-bold">{{ number_format($res->cf_final * 100, 2) }}%</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ps-md-4 mt-3 mt-md-0">
                            <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Gejala yang Dipilih:</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($res->kasus->gejala as $item)
                                    <span class="badge bg-label-info">
                                        <i class="bx bx-check me-1"></i>{{ $item->gejala->nama_gejala }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="mt-4 p-3 bg-warning rounded">
                                <h6 class="fw-bold mb-1 text-white">Saran Penanganan:</h6>
                                <p class="mb-0 small text-white">{{ $res->penyakit->solusi ?? '-' }}</p>
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

<div class="modal fade" id="modalDiagnosa" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form action="{{ route('diagnosa-cf.proses') }}" method="POST" class="modal-content" id="formDiagnosa">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Pilih Gejala & Tingkat Keyakinan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60" class="text-center">Pilih</th>
                                <th>Kode</th>
                                <th>Gejala</th>
                                <th width="230">Keyakinan (CF)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gejala as $g)
                            <tr>
                                <td class="text-center">
                                    <input class="form-check-input check-cf" type="checkbox" data-target="cf_{{ $g->id }}">
                                </td>
                                <td><span class="badge bg-label-secondary">{{ $g->kode_gejala }}</span></td>
                                <td>{{ $g->nama_gejala }}</td>
                                <td>
                                    <select name="cf[{{ $g->id }}]" id="cf_{{ $g->id }}" class="form-select form-select-sm select-cf" disabled>
                                        <option value="" selected disabled>Pilih Nilai</option>
                                        <option value="1.0">Pasti (1.0)</option>
                                        <option value="0.8">Hampir Pasti (0.8)</option>
                                        <option value="0.6">Kemungkinan Besar (0.6)</option>
                                        <option value="0.4">Mungkin (0.4)</option>
                                        <option value="0.2">Sedikit Yakin (0.2)</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <small class="text-muted me-auto" id="selectedCount">0 gejala dipilih</small>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Proses Diagnosa</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const checkboxes = document.querySelectorAll('.check-cf');
    const countEl = document.getElementById('selectedCount');

    function updateCount() {
        const total = document.querySelectorAll('.check-cf:checked').length;
        if (countEl) {
            countEl.textContent = total + ' gejala dipilih';
        }
    }

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const select = document.getElementById(this.dataset.target);
            if (select) {
                select.disabled = !this.checked;
                if (!this.checked) {
                    select.value = '';
                    select.selectedIndex = 0;
                }
            }
            updateCount();
        });
    });

    const form = document.getElementById('formDiagnosa');
    if (form) {
        form.addEventListener('submit', function (e) {
            const checked = document.querySelectorAll('.check-cf:checked').length;
            if (checked === 0) {
                e.preventDefault();
                alert('Pilih minimal satu gejala beserta tingkat keyakinannya.');
            }
        });
    }
})();
</script>
@endsection