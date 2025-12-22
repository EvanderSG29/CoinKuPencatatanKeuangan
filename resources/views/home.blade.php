@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="font-weight-bold mb-0">Dashboard Keuangan</h4>
        <small class="text-muted">Ringkasan aktivitas finansial Anda</small>
    </div>

    {{-- ============================
        BARIS 1 - 4 KARTU UTAMA
    ============================ --}}
    <div class="row">

        {{-- PEMASUKAN --}}
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-arrow-up"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pemasukan</span>
                    <span class="info-box-number">Rp {{ number_format($pemasukan,0,',','.') }}</span>
                </div>
            </div>
        </div>

        {{-- PENGELUARAN --}}
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-arrow-down"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pengeluaran</span>
                    <span class="info-box-number">Rp {{ number_format($pengeluaran,0,',','.') }}</span>
                </div>
            </div>
        </div>

        {{-- fix for small devices only --}}
        <div class="clearfix hidden-md-up"></div>

        {{-- SISA SALDO --}}
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Sisa Saldo</span>
                    <span class="info-box-number">Rp {{ number_format($sisaSaldo,0,',','.') }}</span>
                </div>
            </div>
        </div>

        {{-- TOTAL TRANSAKSI --}}
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Transaksi</span>
                    <span class="info-box-number">{{ $totalTransaksi }}</span>
                </div>
            </div>
        </div>

    </div>





    {{-- ============================
        BARIS 2 - DONUT + KATEGORI + TRANSAKSI TERBARU
    ============================ --}}
    <div class="row">

        {{-- DONUT TOTAL PEMASUKAN VS PENGELUARAN --}}
        <div class="col-lg-4 col-12 mb-2">
            <div class="card shadow-sm border-0 bg-white h-100">
                <div class="card-header bg-light border-0 py-2">
                    <h6 class="mb-0 text-primary"><i class="fas fa-chart-pie mr-2"></i>Total Pemasukan vs Pengeluaran</h6>
                </div>
                <div class="card-body text-center py-2">
                    <canvas id="donutTotal" style="height: 60px;"></canvas>
                </div>
            </div>
        </div>

        {{-- DAFTAR KATEGORI --}}
        <div class="col-lg-4 col-12 mb-2">
            <div class="card shadow-sm border-0 bg-white h-100">
                <div class="card-header bg-light border-0 py-2">
                    <h6 class="mb-0 text-primary"><i class="fas fa-tags mr-2"></i>Daftar Kategori</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-tabs" id="kategoriTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pemasukan-tab" data-toggle="tab" href="#pemasukan" role="tab">Pemasukan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pengeluaran-tab" data-toggle="tab" href="#pengeluaran" role="tab">Pengeluaran</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="kategoriTabContent">
                        <div class="tab-pane fade show active" id="pemasukan" role="tabpanel" style="max-height: 200px; overflow-y:auto;">
                            <ul class="list-group list-group-flush">
                                @foreach($kategoriLabels as $idx => $kat)
                                    @if(($pemasukanKategori[$idx] ?? 0) > 0)
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>{{ $kat }}</span>
                                            <strong class="text-primary">Rp {{ number_format($pemasukanKategori[$idx] ?? 0,0,',','.') }}</strong>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="pengeluaran" role="tabpanel" style="max-height: 200px; overflow-y:auto;">
                            <ul class="list-group list-group-flush">
                                @foreach($kategoriLabels as $idx => $kat)
                                    @if(($pengeluaranKategori[$idx] ?? 0) > 0)
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>{{ $kat }}</span>
                                            <strong class="text-warning">Rp {{ number_format($pengeluaranKategori[$idx] ?? 0,0,',','.') }}</strong>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TRANSAKSI TERBARU --}}
        <div class="col-lg-4 col-12 mb-2">
            <div class="card shadow-sm border-0 bg-white h-100">
                <div class="card-header bg-light border-0 py-2">
                    <h6 class="mb-0 text-primary"><i class="fas fa-history mr-2"></i>Transaksi Terbaru</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-scroll">
                        @include('partials.history-table', ['data' => $recentTransaksi->take(5)])
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection



{{-- ============================
    CUSTOM CSS
============================ --}}
@push('styles')
<style>
body {
    background-color: #f8f9fa;
}

.list-group-item {
    padding: 8px 12px;
    background-color: #fff;
    border-color: #e9ecef;
}
.nav-tabs .nav-link {
    color: #007bff;
}

/* Table scroll wrapper */
.table-scroll {
    max-height: 250px;
    overflow-y: auto;
}

/* Sticky header for table */
.table-scroll thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 2;
    box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1);
}
    border-color: #e9ecef;
    padding: 0.5rem 1rem;
}
.nav-tabs .nav-link.active {
    background-color: #007bff;
    color: white;
}
.card-body {
    padding: 1rem;
}

/* Responsive adjustments */
/* @media (max-width: 768px) {
    .icon-box {
        width: 35px;
        height: 35px;
        font-size: 16px;
    }
    .card-body h4 {
        font-size: 1.2rem;
    }
    .card-body p {
        font-size: 0.9rem;
    }
    .table-responsive {
        font-size: 0.85rem;
    }
    .btn {
        font-size: 0.9rem;
        padding: 0.375rem 0.75rem;
    }
} */

/* Make charts responsive */
canvas {
    max-width: 100% !important;
}
</style>
@endpush



{{-- ============================
    CHART.JS
============================ --}}
@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    /* ===========================
        DATA DARI CONTROLLER
    =========================== */
    const kategoriLabels = {!! json_encode($kategoriLabels) !!};
    const pemasukanKategori = {!! json_encode($pemasukanKategori) !!};
    const pengeluaranKategori = {!! json_encode($pengeluaranKategori) !!};
    const totalPemasukan = {!! $pemasukan !!};
    const totalPengeluaran = {!! $pengeluaran !!}   ;


    /* ===========================
        DONUT TOTAL PEMASUKAN VS PENGELUARAN
    =========================== */
    new Chart(document.getElementById('donutTotal'), {
        type: 'doughnut',
        data: {
            labels: ['Pemasukan', 'Pengeluaran'],
            datasets: [{
                data: [totalPemasukan, totalPengeluaran],
                backgroundColor: ['#007bff', '#ffc107'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endpush