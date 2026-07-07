@extends('layouts.app')

@section('content')
<div class="container-xxl container-p-y statistik-page">

    {{-- Header --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Statistik Diagnosa</h4>
        <small class="text-muted">Perbandingan hasil diagnosa metode CBR & CF</small>
    </div>

    {{-- Ringkasan Perbandingan Metode --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card statistik-band border-0 h-100">
                <div class="card-body p-0">
                    <div class="row g-0 text-center">
                        <div class="col-6 col-lg-3 statistik-band-cell">
                            <small class="text-muted d-block mb-2">Diagnosa CBR</small>
                            <h3 class="fw-bold mb-1 statistik-cbr">{{ number_format($totalHasil) }}</h3>
                            <small class="text-muted">{{ number_format($totalKasus) }} basis kasus</small>
                        </div>
                        <div class="col-6 col-lg-3 statistik-band-cell">
                            <small class="text-muted d-block mb-2">Diagnosa CF</small>
                            <h3 class="fw-bold mb-1 statistik-cf">{{ number_format($totalHasilCf) }}</h3>
                            <small class="text-muted">{{ number_format($totalKasusCf) }} basis kasus</small>
                        </div>
                        <div class="col-6 col-lg-3 statistik-band-cell">
                            <small class="text-muted d-block mb-2">Rata Similarity</small>
                            <h3 class="fw-bold mb-1">{{ number_format($rataSimilarity, 1) }}%</h3>
                            <small class="text-muted">Kemiripan CBR</small>
                        </div>
                        <div class="col-6 col-lg-3 statistik-band-cell">
                            <small class="text-muted d-block mb-2">Rata CF</small>
                            <h3 class="fw-bold mb-1">{{ number_format($rataCf * 100, 1) }}%</h3>
                            <small class="text-muted">Keyakinan CF</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tren Diagnosa --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card statistik-panel border-0 h-100">
                <div class="card-header d-flex justify-content-between align-items-center border-0 pb-0">
                    <div>
                        <h6 class="mb-0">Tren Diagnosa</h6>
                        <small class="text-muted">Jumlah diagnosa per tanggal</small>
                    </div>
                    <div class="statistik-legend">
                        <span class="statistik-legend-item"><span class="dot statistik-cbr-bg"></span>CBR</span>
                        <span class="statistik-legend-item"><span class="dot statistik-cf-bg"></span>CF</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="trenChart" class="statistik-chart"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Perbandingan & Ranking --}}
    <div class="row g-3">
        <div class="col-12 col-xl-7">
            <div class="card statistik-panel border-0 h-100">
                <div class="card-header border-0 pb-0">
                    <h6 class="mb-0">Distribusi per Penyakit</h6>
                    <small class="text-muted">Diagnosa CBR vs CF per kategori</small>
                </div>
                <div class="card-body">
                    <div id="bandingChart" class="statistik-chart"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-5">
            <div class="card statistik-panel border-0 h-100">
                <div class="card-header border-0 pb-0">
                    <h6 class="mb-0">Penyakit Teratas</h6>
                    <small class="text-muted">Total diagnosa kedua metode</small>
                </div>
                <div class="card-body">
                    @forelse($rankingGabungan->take(5) as $index => $item)
                        @php
                            $percentage = $totalDiagnosa > 0 ? round(($item->total / $totalDiagnosa) * 100, 1) : 0;
                        @endphp
                        <div class="ranking-item">
                            <span class="ranking-no">{{ $index + 1 }}</span>
                            <div class="min-w-0 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-truncate d-block" title="{{ $item->nama_penyakit }}">{{ $item->nama_penyakit }}</span>
                                    <small class="text-muted ms-2">{{ $item->total }}x</small>
                                </div>
                                <div class="progress statistik-progress">
                                    <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bx bx-pie-chart-alt mb-2 statistik-empty-icon"></i>
                            <p class="mb-0">Belum ada data statistik.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.statistik-page {
    --statistik-text: #566a7f;
    --statistik-muted: #a1a8b3;
    --statistik-border: #eceef1;
    --statistik-surface: #f7f8fa;
    --cbr: #4caf50;
    --cf: #ff9800;
}

.statistik-cbr { color: var(--cbr); }
.statistik-cf  { color: var(--cf); }
.statistik-cbr-bg { background: var(--cbr); }
.statistik-cf-bg  { background: var(--cf); }

.statistik-band {
    border: 1px solid var(--statistik-border);
    border-radius: 14px;
    background: var(--statistik-surface);
}

.statistik-band-cell {
    padding: 1.4rem .75rem;
}

.statistik-band-cell:not(:last-child) {
    border-right: 1px solid var(--statistik-border);
}

@media (max-width: 991.98px) {
    .statistik-band-cell:nth-child(2) {
        border-right: 0;
    }
    .statistik-band-cell:nth-child(1),
    .statistik-band-cell:nth-child(2) {
        border-bottom: 1px solid var(--statistik-border);
    }
}

.statistik-panel {
    border: 1px solid var(--statistik-border);
    border-radius: 14px;
}

.statistik-panel .card-header {
    padding: 1.1rem 1.25rem .25rem;
}

.statistik-panel .card-body {
    padding: 1rem 1.25rem 1.25rem;
}

.statistik-legend {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.statistik-legend-item {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    font-size: .8rem;
    color: var(--statistik-text);
}

.statistik-legend-item .dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    display: inline-block;
}

.statistik-chart {
    min-height: 320px;
}

.ranking-item {
    display: flex;
    align-items: flex-start;
    gap: .85rem;
    padding: .85rem 0;
    border-top: 1px solid var(--statistik-border);
}

.ranking-item:first-of-type {
    border-top: 0;
    padding-top: .35rem;
}

.ranking-no {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: var(--statistik-surface);
    color: var(--statistik-text);
    font-size: .72rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: .1rem;
}

.statistik-progress {
    height: 5px;
    background: var(--statistik-surface);
    border-radius: 4px;
}

.statistik-progress .progress-bar {
    border-radius: 4px;
    background: var(--cbr);
}

.min-w-0 {
    min-width: 0;
}

.statistik-empty-icon {
    font-size: 2rem;
}

@media (max-width: 767.98px) {
    .statistik-chart {
        min-height: 280px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const textColor = '#566a7f';
    const gridColor = '#eceef1';
    const cbrColor = '#4caf50';
    const cfColor = '#ff9800';

    const tren = @json($trenDiagnosa);
    const emptyTren = tren.labels.length === 0;
    const trenLabels = emptyTren ? ['Belum ada data'] : tren.labels;
    const trenCbr = emptyTren ? [0] : tren.cbr;
    const trenCf = emptyTren ? [0] : tren.cf;

    new ApexCharts(document.querySelector('#trenChart'), {
        chart: {
            type: 'line',
            height: 320,
            toolbar: { show: false },
            fontFamily: 'Public Sans, sans-serif',
            zoom: { enabled: false }
        },
        series: [
            { name: 'CBR', data: trenCbr },
            { name: 'CF', data: trenCf }
        ],
        colors: [cbrColor, cfColor],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        markers: { size: 0, hover: { size: 5 } },
        xaxis: {
            categories: trenLabels,
            labels: { style: { colors: textColor } },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            min: 0,
            forceNiceScale: true,
            labels: { style: { colors: textColor }, formatter: (v) => Math.round(v) }
        },
        grid: { borderColor: gridColor, strokeDashArray: 5 },
        legend: { show: false },
        tooltip: { shared: true, intersect: false }
    }).render();

    const statCbr = @json($statistikPenyakit->pluck('total', 'nama_penyakit'));
    const statCf = @json($statistikPenyakitCf->pluck('total', 'nama_penyakit'));
    const labelsBanding = Object.keys({ ...statCbr, ...statCf }).slice(0, 8);

    const bandingCbr = labelsBanding.map((k) => statCbr[k] ?? 0);
    const bandingCf = labelsBanding.map((k) => statCf[k] ?? 0);
    const emptyBanding = labelsBanding.length === 0;
    const bandingLabels = emptyBanding ? ['Belum ada data'] : labelsBanding;

    new ApexCharts(document.querySelector('#bandingChart'), {
        chart: {
            type: 'bar',
            height: 320,
            toolbar: { show: false },
            fontFamily: 'Public Sans, sans-serif'
        },
        series: [
            { name: 'CBR', data: emptyBanding ? [0] : bandingCbr },
            { name: 'CF', data: emptyBanding ? [0] : bandingCf }
        ],
        colors: [cbrColor, cfColor],
        plotOptions: {
            bar: { horizontal: true, borderRadius: 4, columnWidth: '70%' }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: bandingLabels,
            min: 0,
            forceNiceScale: true,
            labels: { style: { colors: textColor }, formatter: (v) => Math.round(v) }
        },
        yaxis: { labels: { style: { colors: textColor } } },
        grid: { borderColor: gridColor, strokeDashArray: 5 },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            labels: { colors: textColor }
        },
        tooltip: { shared: true, intersect: false }
    }).render();
});
</script>
@endsection
