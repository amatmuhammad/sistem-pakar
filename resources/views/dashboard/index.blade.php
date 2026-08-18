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

        <!-- Total Gejala -->
        <div class="col-md-3 mb-2">
            <div class="card statistik-card interactive-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center position-relative overflow-hidden">
                    <div class="icon-box bg-label-primary me-3">
                        <i class="bx bx-list-ul"></i>
                    </div>
                    <div class="flex-grow-1">
                        <small class="text-muted">Total Gejala</small>
                        <h4 class="mb-0 fw-bold counter" data-target="{{ $totalGejala }}">0</h4>
                    </div>
                    <span class="card-shine"></span>
                </div>
            </div>
        </div>

        <!-- Penyakit & Hama -->
        <div class="col-md-3 mb-2">
            <div class="card statistik-card interactive-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center position-relative overflow-hidden">
                    <div class="icon-box bg-label-success me-3">
                        <i class="bx bx-bug"></i>
                    </div>
                    <div class="flex-grow-1">
                        <small class="text-muted">Penyakit & Hama</small>
                        <h4 class="mb-0 fw-bold counter" data-target="{{ $totalPenyakit }}">0</h4>
                    </div>
                    <span class="card-shine"></span>
                </div>
            </div>
        </div>

        <!-- Basis Kasus -->
        <div class="col-md-3 mb-2">
            <div class="card statistik-card interactive-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center position-relative overflow-hidden">
                    <div class="icon-box bg-label-warning me-3">
                        <i class="bx bx-folder"></i>
                    </div>
                    <div class="flex-grow-1">
                        <small class="text-muted">Basis Kasus</small>
                        <h4 class="mb-0 fw-bold counter" data-target="{{ $totalKasus }}">0</h4>
                    </div>
                    <span class="card-shine"></span>
                </div>
            </div>
        </div>

        <!-- Hasil Diagnosa -->
        <div class="col-md-3 mb-2">
            <div class="card statistik-card interactive-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center position-relative overflow-hidden">
                    <div class="icon-box bg-label-info me-3">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <small class="text-muted">Hasil Diagnosa</small>
                        <h4 class="mb-0 fw-bold counter" data-target="{{ $totalDiagnosa }}">0</h4>
                    </div>
                    <span class="card-shine"></span>
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
    /* ===== Kartu Statistik Interaktif ===== */
    .statistik-card {
        border-radius: 16px;
        transition: all .3s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        will-change: transform;
        border: 1px solid transparent;
    }

    .statistik-card:hover {
        box-shadow: 0 20px 35px rgba(0, 0, 0, .12);
        border-color: rgba(0, 0, 0, .05);
    }

    .interactive-card {
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    }

    /* Efek kilau saat hover */
    .card-shine {
        position: absolute;
        top: 0;
        left: -75%;
        width: 50%;
        height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255, 255, 255, .7), transparent);
        transform: skewX(-20deg);
        transition: left .6s ease;
        pointer-events: none;
    }

    .interactive-card:hover .card-shine {
        left: 125%;
    }

    .icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform .4s ease, box-shadow .3s ease;
    }

    .icon-box i {
        font-size: 1.6rem;
        transition: transform .4s ease;
    }

    .interactive-card:hover .icon-box {
        transform: scale(1.15) rotate(-5deg);
        box-shadow: 0 8px 16px rgba(0, 0, 0, .1);
    }

    .interactive-card:hover .icon-box i {
        transform: scale(1.2);
    }

    /* ===== Kartu Chart ===== */
    .dashboard-chart-card {
        border-radius: 16px;
        transition: transform .3s ease, box-shadow .3s ease;
        will-change: transform;
    }

    .dashboard-chart-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 30px rgba(0, 0, 0, .08);
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

        // ===== Efek Tilt 3D pada Kartu Statistik =====
        const cards = document.querySelectorAll('.interactive-card');

        cards.forEach(card => {
            card.addEventListener('mousemove', function (e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = ((y - centerY) / centerY) * -6;
                const rotateY = ((x - centerX) / centerX) * 6;

                card.style.transform = `perspective(600px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
            });

            card.addEventListener('mouseleave', function () {
                card.style.transform = '';
            });
        });

        // ===== Animasi Count-Up untuk Angka Statistik =====
        const counters = document.querySelectorAll('.counter');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = Number(el.getAttribute('data-target')) || 0;
                    let current = 0;
                    const increment = Math.ceil(target / 40);
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        el.textContent = current.toLocaleString('id-ID');
                    }, 30);
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));

        // ===== Script Chart ApexCharts =====
        const diagnosaPerTanggal = @json($diagnosaPerTanggal);
        const penyakitTerbanyak = @json($penyakitTerbanyak);

        const emptyLabel = ['Belum ada data'];
        const normalizeLabels = (labels) => labels.length ? labels : emptyLabel;
        const normalizeSeries = (series, labels) => labels.length ? series : [0];

        const chartTextColor = '#566a7f';
        const gridColor = '#eceef1';

        // Chart 1: Area - Hasil Diagnosa per Tanggal
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

        // Chart 2: Bar Horizontal - Penyakit Sering Didiagnosa
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