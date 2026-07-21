@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Data Gejala</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bx bx-plus me-1"></i> Tambah Gejala
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Daftar Gejala</h5>
            <h3>{{ $totalbobot }}</h3>
        </div>
        <div class="table-responsive text-nowrap p-4">
            <table class="table table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Gejala (Symptom Name)</th>
                        <th>Bobot CBR</th>
                        <th class="text-center" width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $g)
                    <tr>
                        <td class="ps-4"><span class="badge bg-label-primary">{{ $g->kode_gejala }}</span></td>
                        <td class="text-wrap">{{ $g->nama_gejala }}</td>
                        <td><span class="fw-bold text-primary">{{ number_format($g->bobot_cbr, 2) }}</span></td>
                        <td class="text-center">
                            <div class="btn-group gap-2">
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#edit{{ $g->id }}">
                                    <i class="bx bx-edit-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapus{{ $g->id }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data gejala tersedia.</td>
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

@foreach($data as $g)
<div class="modal fade" id="edit{{ $g->id }}" tabindex="-1" aria-labelledby="labelEdit{{ $g->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('gejala.update', $g->id) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="labelEdit{{ $g->id }}">Edit Gejala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="kode{{ $g->id }}">Kode Gejala</label>
                    <input type="text" id="kode{{ $g->id }}" name="kode_gejala" class="form-control" value="{{ $g->kode_gejala }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="nama{{ $g->id }}">Nama Gejala</label>
                    <input type="text" id="nama{{ $g->id }}" name="nama_gejala" class="form-control" value="{{ $g->nama_gejala }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="bobot{{ $g->id }}">Bobot CBR</label>
                    <input type="number" id="bobot{{ $g->id }}" step="0.01" name="bobot_cbr" class="form-control" value="{{ $g->bobot_cbr }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="hapus{{ $g->id }}" tabindex="-1" aria-labelledby="labelHapus{{ $g->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="{{ route('gejala.destroy', $g->id) }}" method="POST" class="modal-content">
            @csrf @method('DELETE')
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pb-0">
                <div class="text-danger mb-3">
                    <i class="bx bx-error-circle display-1"></i>
                </div>
                <h5 class="modal-title mb-2" id="labelHapus{{ $g->id }}">Hapus Data?</h5>
                <p class="text-muted">Gejala <strong>{{ $g->nama_gejala }}</strong> akan dihapus permanen dari sistem.</p>
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
        <form action="{{ route('gejala.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="labelTambah">Tambah Gejala Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode Gejala</label>
                    <input type="text" name="kode_gejala" class="form-control" placeholder="G001" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Gejala</label>
                    <input type="text" name="nama_gejala" class="form-control" placeholder="Masukkan nama gejala" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Bobot CBR</label>
                    <input type="number" step="0.01" name="bobot_cbr" class="form-control" placeholder="0.50" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection