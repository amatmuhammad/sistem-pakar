@extends('layouts.app')

@section('content')
<div class="container-xxl container-p-y">

    <h4 class="fw-bold mb-4">Perbandingan Akurasi CBR vs CF</h4>

    <!-- CARD AKURASI -->
    <div class="row mb-4">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Akurasi CBR</small>
                    <h3 class="fw-bold text-primary">
                        {{ number_format($akurasiCbr,2) }}%
                    </h3>
                    <small class="text-muted">Dari {{ $total }} data yang dibandingkan</small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Akurasi Certainty Factor</small>
                    <h3 class="fw-bold text-success">
                        {{ number_format($akurasiCf,2) }}%
                    </h3>
                    <small class="text-muted">Dari {{ $total }} data yang dibandingkan</small>
                </div>
            </div>
        </div>

    </div>

    <!-- TABEL PERBANDINGAN -->
    <div class="card">
        <div class="card-header border-bottom">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h5 class="mb-0">Detail Perbandingan</h5>

                <div class="d-flex align-items-center">
                    <small class="text-muted me-2">Show</small>
                    <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = this.value">
                        @foreach([10, 25, 50, 100] as $val)
                            <option value="{{ request()->fullUrlWithQuery(['perPage' => $val]) }}" {{ (int) request('perPage', 10) === $val ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body mt-3">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Hasil CBR</th>
                            <th>Hasil CF</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perbandinganPaginate as $p)
                        <tr>
                            <td>{{ $perbandinganPaginate->firstItem() + $loop->index }}</td>
                            <td>{{ $p['cbr'] }}</td>
                            <td>{{ $p['cf'] }}</td>
                            <td>
                                <span class="badge {{ $p['status']=='Sama' ? 'bg-label-success':'bg-label-danger' }}">
                                    {{ $p['status'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bx bx-search-alt-2 mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada data perbandingan.</p>
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
                    Menampilkan {{ $perbandinganPaginate->firstItem() ?? 0 }} sampai {{ $perbandinganPaginate->lastItem() ?? 0 }} dari {{ $perbandinganPaginate->total() }} data
                </small>
                <div>
                    {{ $perbandinganPaginate->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection