@extends('layouts.app')

@section('content')
<div class="container-xxl container-p-y">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h4 class="fw-bold mb-1">Dashboard Sistem Pakar</h4>
            <small class="text-muted">Ringkasan aktivitas diagnosa CBR dan CF</small>
        </div>
    </div>

    <!-- SUMMARY CARD -->
    <div class="row mb-4">

        <div class="col-md-3 mb-2">
            <div class="card statistik-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-label-primary me-3">
                        <i class="bx bx-list-ul"></i>
                    </div>
                    <div>
                        <small class="text-muted">Total Gejala</small>
                        <h4 class="mb-0 fw-bold">{{ $totalGejala }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-2">
            <div class="card statistik-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-label-success me-3">
                        <i class="bx bx-bug"></i>
                    </div>
                    <div>
                        <small class="text-muted">Penyakit & Hama</small>
                        <h4 class="mb-0 fw-bold">{{ $totalPenyakit }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-2">
            <div class="card statistik-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-label-warning me-3">
                        <i class="bx bx-folder"></i>
                    </div>
                    <div>
                        <small class="text-muted">Basis Kasus</small>
                        <h4 class="mb-0 fw-bold">{{ $totalKasus }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-2">
            <div class="card statistik-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-label-info me-3">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div>
                        <small class="text-muted">Hasil Diagnosa</small>
                        <h4 class="mb-0 fw-bold">{{ $totalDiagnosa }}</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card dashboard-chart-card border-0 shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1">Hasil Diagnosa per Tanggal</h5>
                        <small class="text-muted">Perbandingan jumlah diagnosa metode CBR dan CF</small>
                    </div>
                    <span class="badge bg-label-primary">Trend</span>
                </div>
                <div class="card-body">
                    <div id="chartDiagnosaTanggal" class="dashboard-chart"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card dashboard-chart-card border-0 shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1">Penyakit Sering Didiagnosa</h5>
                        <small class="text-muted">Ranking diagnosis berdasarkan metode</small>
                    </div>
                    <span class="badge bg-label-success">Top 8</span>
                </div>
                <div class="card-body">
                    <div id="chartPenyakitTerbanyak" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.statistik-card {
    border-radius: 12px;
    transition: all .25s ease;
}

.statistik-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,.08);
}

.icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-box i {
    font-size: 1.45rem;
}

.dashboard-chart-card {
    border-radius: 12px;
}

.dashboard-chart {
    min-height: 335px;
}

@media (max-width: 767.98px) {
    .dashboard-chart {
        min-height: 300px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const diagnosaPerTanggal = @json($diagnosaPerTanggal);
    const penyakitTerbanyak = @json($penyakitTerbanyak);

    const emptyLabel = ['Belum ada data'];
    const normalizeLabels = (labels) => labels.length ? labels : emptyLabel;
    const normalizeSeries = (series, labels) => labels.length ? series : [0];

    const chartTextColor = '#566a7f';
    const gridColor = '#eceef1';

    new ApexCharts(document.querySelector('#chartDiagnosaTanggal'), {
        chart: {
            type: 'area',
            height: 335,
            toolbar: { show: false },
            fontFamily: 'Public Sans, sans-serif'
        },
        series: [
            {
                name: 'CBR',
                data: normalizeSeries(diagnosaPerTanggal.cbr, diagnosaPerTanggal.labels)
            },
            {
                name: 'CF',
                data: normalizeSeries(diagnosaPerTanggal.cf, diagnosaPerTanggal.labels)
            }
        ],
        colors: ['#71dd37', '#ffab00'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
                stops: [0, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        // ❌ Hapus semua pengaturan markers (tidak perlu didefinisikan)
        dataLabels: { enabled: false },
        xaxis: {
            categories: normalizeLabels(diagnosaPerTanggal.labels),
            labels: { style: { colors: chartTextColor } },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            min: 0,
            forceNiceScale: true,
            labels: {
                style: { colors: chartTextColor },
                formatter: (value) => Math.round(value)
            }
        },
        grid: {
            borderColor: gridColor,
            strokeDashArray: 5,
            xaxis: { lines: { show: true } }
        },
        legend: { position: 'top', horizontalAlign: 'right', labels: { colors: chartTextColor } },
        tooltip: { shared: true, intersect: false }
    }).render();

    new ApexCharts(document.querySelector('#chartPenyakitTerbanyak'), {
        chart: {
            type: 'bar',
            height: 335,
            toolbar: { show: false },
            fontFamily: 'Public Sans, sans-serif'
        },
        series: [
            {
                name: 'CBR',
                data: normalizeSeries(penyakitTerbanyak.cbr, penyakitTerbanyak.labels)
            },
            {
                name: 'CF',
                data: normalizeSeries(penyakitTerbanyak.cf, penyakitTerbanyak.labels)
            }
        ],
        colors: ['#71dd37', '#ffab00'],
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 5,
                barHeight: '62%'
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            min: 0,
            forceNiceScale: true,
            categories: normalizeLabels(penyakitTerbanyak.labels),
            labels: {
                style: { colors: chartTextColor },
                formatter: (value) => Math.round(value)
            }
        },
        yaxis: { labels: { style: { colors: chartTextColor } } },
        grid: { borderColor: gridColor, strokeDashArray: 5 },
        legend: { position: 'top', horizontalAlign: 'right', labels: { colors: chartTextColor } },
        tooltip: { shared: true, intersect: false }
    }).render();
});
</script>
@endsection
