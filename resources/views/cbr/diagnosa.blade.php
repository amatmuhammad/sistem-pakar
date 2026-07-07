@extends('layouts.app')

@section('content')
<div class="container-xxl container-p-y">

    <h4 class="fw-bold mb-4">Diagnosa CBR</h4>

    <div class="card">
       <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">Diagnosis Hama & Penyakit Jagung</h5>

            <div class="d-flex gap-2">

                <button class="btn btn-primary btn-md"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDiagnosa">
                    <i class="bx bx-search"></i> Mulai Diagnosa
                </button>

                <button onclick="location.reload()"
                        class="btn btn-outline-danger btn-md">
                    <i class="bx bx-refresh"></i> Bersihkan riwayat
                </button>

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

<!-- MODAL DIAGNOSA -->
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
</div>
@endsection
