@extends('layouts.app')

@section('content')
<div class="container-xxl container-p-y">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Perbandingan CBR vs CF</h4>
            <small class="text-muted">Satu baris mewakili satu pasangan diagnosis CBR dan CF.</small>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="mb-1">Detail Perbandingan</h5>
                <small class="text-muted">
                    Hanya diagnosis dengan user dan set gejala yang sama yang ditampilkan; setiap hasil digunakan satu kali.
                </small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <small class="text-muted">Show</small>
                <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = this.value">
                    @foreach ([10, 25, 50, 100] as $value)
                        <option value="{{ request()->fullUrlWithQuery(['perPage' => $value]) }}" {{ (int) request('perPage', 10) === $value ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card-body mt-3">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>ID CBR</th>
                            <th>Tanggal CBR</th>
                            <th>Diagnosis CBR</th>
                            <th>Similarity CBR</th>
                            <th>ID CF</th>
                            <th>Tanggal CF</th>
                            <th>Diagnosis CF</th>
                            <th>Certainty Factor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($perbandinganPaginate as $p)
                            <tr>
                                <td>{{ $perbandinganPaginate->firstItem() + $loop->index }}</td>
                                <td>#{{ $p['id_cbr'] }}</td>
                                <td>{{ $p['tanggal_cbr'] }}</td>
                                <td>{{ $p['cbr'] }}</td>
                                <td>
                                    <span class="fw-bold text-primary">{{ number_format($p['similarity_pct'], 2) }}%</span>
                                </td>
                                <td>#{{ $p['id_cf'] }}</td>
                                <td>{{ $p['tanggal_cf'] }}</td>
                                <td>{{ $p['cf'] }}</td>
                                <td>
                                    <span class="fw-bold text-success">{{ number_format($p['cf_pct'], 2) }}%</span>
                                </td>
                                <td>
                                    <span class="badge {{ $p['status'] === 'Sama' ? 'bg-label-success' : 'bg-label-danger' }}">
                                        {{ $p['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="bx bx-search-alt-2 mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0 text-muted">Belum ada pasangan CBR dan CF dengan gejala yang sama.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($perbandinganPaginate->hasPages())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan {{ $perbandinganPaginate->firstItem() ?? 0 }} sampai {{ $perbandinganPaginate->lastItem() ?? 0 }} dari {{ $perbandinganPaginate->total() }} pasangan diagnosis
                    </small>
                    <div>{{ $perbandinganPaginate->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
