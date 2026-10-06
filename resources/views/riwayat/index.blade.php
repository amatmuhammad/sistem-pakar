@extends('layouts.app')

@section('content')
<div class="container-xxl container-p-y">

    <h4 class="fw-bold mb-4"><span class="text-muted fw-light">Riwayat /</span> Basis Kasus & Hasil Diagnosa</h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Daftar Riwayat Diagnosis (CBR & CF)</h5>
            <div class="d-flex align-items-start gap-3 flex-wrap">
                {{-- Filter Metode --}}
                <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="metode" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                        <option value="">Semua Metode</option>
                        <option value="CBR" {{ request('metode') === 'CBR' ? 'selected' : '' }}>CBR</option>
                        <option value="CF" {{ request('metode') === 'CF' ? 'selected' : '' }}>CF</option>
                    </select>
                </form>

                <div class="d-flex align-items-center">
                    <small class="text-muted me-2">Show</small>
                    <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = this.value">
                        @foreach([10, 25, 50, 100] as $val)
                            <option value="{{ request()->fullUrlWithQuery(['perPage' => $val]) }}" {{ (int) request('perPage', 10) === $val ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center">
                    <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                    <input type="hidden" name="metode" value="{{ request('metode') }}">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari ID / penyakit..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-success">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 8%">Metode</th>
                            <th style="width: 8%">ID Kasus</th>
                            <th style="width: 12%">Tanggal</th>
                            <th style="width: 10%">Jumlah Gejala</th>
                            <th style="width: 24%">Gejala yang Dialami</th>
                            <th style="width: 15%">Hasil Diagnosa</th>
                            <th style="width: 10%">Nilai</th>
                            <th style="width: 8%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $r)
                        <tr>
                            <td>{{ $riwayat->firstItem() + $loop->index }}</td>
                            <td>
                                @if($r['metode'] === 'CBR')
                                    <span class="badge bg-label-primary">CBR</span>
                                @else
                                    <span class="badge bg-label-info">CF</span>
                                @endif
                            </td>
                            <td><span class="fw-bold">#{{ $r['id'] }}</span></td>
                            <td>{{ $r['tanggal'] ? \Carbon\Carbon::parse($r['tanggal'])->format('d/m/Y H:i') : '-' }}</td>
                            <td class="text-center">
                                <span class="badge badge-center rounded-pill bg-label-primary">{{ $r['jumlah_gejala'] }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($r['gejala'] as $namaGejala)
                                        <span class="badge bg-label-warning" style="font-size: 0.75rem;">{{ $namaGejala }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                @if($r['penyakit'])
                                    <span class="badge badge-warning fw-semibold">{{ $r['penyakit'] }}</span>
                                @else
                                    <span class="badge bg-label-secondary">Tidak Terdeteksi</span>
                                @endif
                            </td>
                            <td>
                                @if(!is_null($r['nilai']))
                                    @php
                                        $percentage = $r['nilai'] * 100;
                                        $color = $percentage >= 70 ? 'success' : ($percentage >= 40 ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ number_format($percentage, 2) }}%</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-label-secondary">{{ ucfirst($r['status']) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada data riwayat diagnosa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $riwayat->firstItem() ?? 0 }} sampai {{ $riwayat->lastItem() ?? 0 }} dari {{ $riwayat->total() }} data
                </small>
                <div>
                    {{ $riwayat->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
