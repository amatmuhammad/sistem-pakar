@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Aturan Certainty Factor</h4>
            <p class="text-muted small mb-0">Kelola bobot keyakinan pakar untuk setiap relasi gejala dan penyakit</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bx bx-plus me-1"></i> Tambah Rule
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Daftar Rules CF</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">PENYAKIT</th>
                        <th>GEJALA</th>
                        <th class="text-center">MB</th>
                        <th class="text-center">MD</th>
                        <th class="text-center">CF EXPERT</th>
                        <th class="text-center" width="100">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $a)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-primary">{{ $a->penyakit->nama_penyakit }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-dark fw-medium">{{ $a->gejala->nama_gejala }}</span>
                                <small class="text-muted">{{ $a->gejala->kode_gejala }}</small>
                            </div>
                        </td>
                        <td class="text-center"><span class="badge bg-label-success">{{ number_format($a->mb, 2) }}</span></td>
                        <td class="text-center"><span class="badge bg-label-danger">{{ number_format($a->md, 2) }}</span></td>
                        <td class="text-center">
                            <span class="fw-bold">{{ number_format($a->mb - $a->md, 2) }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group gap-2">
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#edit{{ $a->id }}">
                                    <i class="bx bx-edit-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapus{{ $a->id }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada aturan (rule) yang dikonfigurasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($data as $a)
<div class="modal fade" id="edit{{ $a->id }}" tabindex="-1" aria-labelledby="labelEdit{{ $a->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('aturan-cf.update', $a->id) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="labelEdit{{ $a->id }}">Edit Rule CF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Penyakit</label>
                        <select name="penyakit_id" class="form-select" required>
                            @foreach($penyakit as $p)
                                <option value="{{ $p->id }}" {{ $a->penyakit_id == $p->id ? 'selected' : '' }}>{{ $p->nama_penyakit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Gejala</label>
                        <select name="gejala_id" class="form-select" required>
                            @foreach($gejala as $g)
                                <option value="{{ $g->id }}" {{ $a->gejala_id == $g->id ? 'selected' : '' }}>[{{ $g->kode_gejala }}] {{ $g->nama_gejala }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nilai MB</label>
                        <input type="number" step="0.01" name="mb" class="form-control" value="{{ $a->mb }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nilai MD</label>
                        <input type="number" step="0.01" name="md" class="form-control" value="{{ $a->md }}" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@foreach($data as $a)
<div class="modal fade" id="hapus{{ $a->id }}" tabindex="-1" aria-labelledby="labelHapus{{ $a->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="{{ route('aturan-cf.destroy', $a->id) }}" method="POST" class="modal-content">
            @csrf @method('DELETE')
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pb-0">
                <div class="text-danger mb-3">
                    <i class="bx bx-shield-x display-1"></i>
                </div>
                <h5 class="modal-title mb-2" id="labelHapus{{ $a->id }}">Hapus Rule?</h5>
                <p class="text-muted small">Relasi antara <strong>{{ $a->penyakit->nama_penyakit }}</strong> dan <strong>{{ $a->gejala->nama_gejala }}</strong> akan dihapus.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-2 pb-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="bx bx-trash me-1"></i> Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="labelTambah" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('aturan-cf.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="labelTambah">Tambah Rule CF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Pilih Penyakit</label>
                        <select name="penyakit_id" class="form-select select2" required>
                            <option value="" selected disabled>-- Pilih Penyakit --</option>
                            @foreach($penyakit as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_penyakit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Pilih Gejala</label>
                        <select name="gejala_id" class="form-select select2" required>
                            <option value="" selected disabled>-- Pilih Gejala --</option>
                            @foreach($gejala as $g)
                                <option value="{{ $g->id }}">[{{ $g->kode_gejala }}] {{ $g->nama_gejala }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nilai MB</label>
                        <input type="number" step="0.01" min="0" max="1" name="mb" class="form-control" placeholder="0.0 - 1.0" required>
                        <div class="form-text">Measure of Belief</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nilai MD</label>
                        <input type="number" step="0.01" min="0" max="1" name="md" class="form-control" placeholder="0.0 - 1.0" required>
                        <div class="form-text">Measure of Disbelief</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan Rule</button>
            </div>
        </form>
    </div>
</div>
@endsection