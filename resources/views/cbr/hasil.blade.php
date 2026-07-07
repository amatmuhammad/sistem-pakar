@extends('layouts.app')

@section('content')
<div class="container-xxl container-p-y">
    <h4 class="fw-bold mb-4">Hasil Diagnosa CBR</h4>

    <div class="card">
        <div class="card-header border-bottom">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h5 class="mb-0">Riwayat Hasil Diagnosa</h5>
                
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center">
                        <small class="text-muted me-2">Show</small>
                        <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = this.value">
                            @foreach([10, 25, 50, 100] as $val)
                                <option value="{{ request()->fullUrlWithQuery(['perPage' => $val]) }}" {{ request('perPage') == $val ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center">
                        {{-- Sertakan perPage agar tidak kembali ke default saat searching --}}
                        <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari penyakit..." value="{{ request('search') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body mt-3">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>ID Kasus</th>
                            <th>Tanggal</th>
                            <th>Jumlah Gejala</th>
                            <th>Hasil Diagnosa</th>
                            <th>Similarity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hasil as $h)
                        <tr>
                            <td>#{{ $hasil->firstItem() + $loop->index }}</td>
                            <td>{{ $h->kasus->tanggal }}</td>
                            <td>{{ $h->kasus->fitur->count() }}</td>
                            <td>
                                <span class="fw-semibold">{{ $h->penyakit->nama_penyakit }}</span>
                            </td>
                            <td>
                                <span class="badge bg-label-success">
                                    {{ number_format($h->similarity_final, 2) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bx bx-search-alt-2 mb-2" style="font-size: 2rem;"></i>
                                    <p>Data tidak ditemukan untuk "{{ request('search') }}"</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $hasil->firstItem() ?? 0 }} sampai {{ $hasil->lastItem() ?? 0 }} dari {{ $hasil->total() }} data
                </small>
                <div>
                    {{ $hasil->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection