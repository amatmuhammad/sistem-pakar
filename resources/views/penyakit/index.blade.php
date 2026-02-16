@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Data Penyakit & Hama</h4>
            <p class="text-muted small mb-0">Manajemen daftar penyakit, hama, dan solusi penanganannya</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bx bx-plus me-1"></i> Tambah Data
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Daftar Penyakit</h5>
        </div>
        <div class="table-responsive text-nowrap p-2">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">NAMA PENYAKIT</th>
                        <th>JENIS</th>
                        <th>SOLUSI</th>
                        <th class="text-center" width="150">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $p)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-dark">{{ $p->nama_penyakit }}</span>
                        </td>
                        <td>
                            <span class="badge bg-label-info text-capitalize">{{ $p->jenis }}</span>
                        </td>
                        <td class="text-wrap" style="max-width: 300px;">
                            <small class="text-muted">{{ Str::limit($p->solusi, 80) }}</small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group gap-2">
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#edit{{ $p->id }}" title="Edit">
                                    <i class="bx bx-edit-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapus{{ $p->id }}" title="Hapus">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">Belum ada data penyakit tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan {{ $data->firstItem() }} sampai {{ $data->lastItem() }} dari {{ $data->total() }} data
                    </small>
                    <div>
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($data as $p)
<div class="modal fade" id="edit{{ $p->id }}" tabindex="-1" aria-labelledby="labelEdit{{ $p->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('penyakit.update', $p->id) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="labelEdit{{ $p->id }}">Perbarui Data Penyakit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Penyakit</label>
                        <input type="text" name="nama_penyakit" class="form-control" value="{{ $p->nama_penyakit }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jenis</label>
                        <select name="jenis" class="form-select">
                            <option value="Hama" {{ $p->jenis == 'Hama' ? 'selected' : '' }}>Hama</option>
                            <option value="Penyakit" {{ $p->jenis == 'Penyakit' ? 'selected' : '' }}>Penyakit</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Solusi Penanganan</label>
                        <textarea name="solusi" class="form-control" rows="4" required>{{ $p->solusi }}</textarea>
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

<div class="modal fade" id="hapus{{ $p->id }}" tabindex="-1" aria-labelledby="labelHapus{{ $p->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="{{ route('penyakit.destroy', $p->id) }}" method="POST" class="modal-content">
            @csrf @method('DELETE')
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pb-0">
                <div class="text-danger mb-3">
                    <i class="bx bx-error-circle display-1"></i>
                </div>
                <h5 class="modal-title mb-2" id="labelHapus{{ $p->id }}">Hapus Data?</h5>
                <p class="text-muted small">Anda akan menghapus data penyakit <strong>{{ $p->nama_penyakit }}</strong> secara permanen.</p>
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('penyakit.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="labelTambah">Tambah Penyakit Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Penyakit / Hama</label>
                        <input type="text" name="nama_penyakit" class="form-control" placeholder="Masukkan nama..." required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jenis</label>
                        <select name="jenis" class="form-select">
                            <option value="Penyakit">Penyakit</option>
                            <option value="Hama">Hama</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Solusi</label>
                        <textarea name="solusi" class="form-control" rows="4" placeholder="Jelaskan langkah-langkah solusinya..." required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection