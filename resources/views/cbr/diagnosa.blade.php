@extends('layouts.app')

@section('content')
<div class="container-xxl container-p-y">

    <h4 class="fw-bold mb-4">Diagnosa CBR</h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Diagnosis Hama & Penyakit Jagung</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDiagnosa">
                <i class="bx bx-search"></i> Mulai Diagnosa
            </button>
        </div>

        <div class="card-body">

            @if(session('hasil'))
                <div class="alert alert-success">
                    <h5>Hasil Diagnosa</h5>
                    <p><b>Penyakit:</b> {{ session('hasil')->penyakit->nama_penyakit }}</p>
                    <p><b>Similarity:</b> {{ session('hasil')->similarity_final }}</p>
                    <p><b>Solusi:</b> {{ session('hasil')->penyakit->solusi }}</p>
                </div>
            @else
                <div class="text-muted">Belum ada hasil diagnosa.</div>
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
