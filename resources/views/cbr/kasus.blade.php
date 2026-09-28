 @extends('layouts.app')

@section('content')
<div class="container-xxl container-p-y">

    <h4 class="fw-bold mb-4"><span class="text-muted fw-light">CBR /</span> Basis Kasus & Hasil Diagnosa</h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Daftar Riwayat Diagnosis</h5>
            <div class="d-flex align-items-start gap-3">
                <div class="d-flex align-items-center">
                    <small class="text-muted me-2">Show</small>
                    <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = this.value">
                        @foreach([10, 25, 50, 100] as $val)
                            <option value="{{ request()->fullUrlWithQuery(['perPage' => $val]) }}" {{ request('perPage') == $val ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center">
                    <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
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
                            <th style="width: 8%">No</th>
                            <th style="width: 8%">ID Kasus</th>
                            <th style="width: 12%">Tanggal</th>
                            <th style="width: 8%">Jumlah Gejala</th>
                            <th style="width: 24%">Gejala yang Dialami</th>
                            <th style="width: 15%">Hasil Diagnosa</th>
                            <th style="width: 10%">Similarity</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 5%">Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kasus as $k)
                        <tr>
                            <td>{{ $kasus->firstItem() + $loop->index }}</td>
                            <td><span class="fw-bold">#{{ $k->id }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <span class="badge badge-center rounded-pill bg-label-primary">{{ $k->fitur->count() }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($k->fitur as $f)
                                        <span class="badge bg-label-warning" style="font-size: 0.75rem;">
                                            {{ $f->gejala->nama_gejala }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                @if($k->hasil && $k->hasil->penyakit)
                                    <span class="badge badge-warning fw-semibold">
                                        {{ $k->hasil->penyakit->nama_penyakit }}
                                    </span>
                                @else
                                    <span class="badge bg-label-secondary">Tidak Terdeteksi</span>
                                @endif
                            </td>
                            <td>
                                @if($k->hasil)
                                    @php
                                        $percentage = $k->hasil->similarity_final * 100;
                                        $color = $percentage >= 70 ? 'success' : ($percentage >= 40 ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge bg-{{ $color }}">
                                        {{ number_format($percentage, 2) }}%
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'baru' => 'info',
                                        'divalidasi' => 'success',
                                        'ditolak' => 'danger',
                                    ][$k->status_retain] ?? 'secondary';
                                @endphp
                                <span class="badge bg-label-{{ $statusClass }}">
                                    {{ ucfirst($k->status_retain) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($k->hasil)
                                    @if($k->hasil->divalidasi_pakar)
                                        <i class="bx bx-check-circle text-success" title="Tervalidasi"></i>
                                    @else
                                        <i class="bx bx-time text-muted" title="Belum divalidasi"></i>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada data kasus diagnosis.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $kasus->firstItem() ?? 0 }} sampai {{ $kasus->lastItem() ?? 0 }} dari {{ $kasus->total() }} data
                </small>
                <div>
                    {{ $kasus->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
 @endsection
