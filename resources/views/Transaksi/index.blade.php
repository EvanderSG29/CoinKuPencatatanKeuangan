@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-dark mb-1">Manajemen Transaksi</h1>
            <p class="text-muted mb-0">Pantau semua aktivitas finansial Anda dalam satu tempat.</p>
        </div>
        <div class="d-flex align-items-center">
            <button class="btn btn-warning btn-sm mr-2" id="clear-transaksi-btn">
                <i class="fas fa-trash"></i> Hapus Semua
            </button>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createTransaksiModal">
                <i class="fas fa-plus"></i> Tambah Transaksi
            </button>
        </div>
    </div>

    @include('partials.modals.create-transaksi')

    {{-- BARIS 1 - TABEL TRANSAKSI --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow modern-card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Transaksi</th>
                                    <th>Kategori</th>
                                    <th>Jenis</th>
                                    <th>Qty</th>
                                    <th>Nominal</th>
                                    <th>Total</th>
                                    <th class="text-center" style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksis as $transaksi)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
                                    <td>{{ $transaksi->nama_transaksi }}</td>
                                    <td>{{ $transaksi->kategori->nama_kategori ?? 'N/A' }}</td>
                                    <td>
                                        @if(strtolower($transaksi->jenis_transaksi) === 'pemasukan')
                                            <span class="badge badge-success">Pemasukan</span>
                                        @else
                                            <span class="badge badge-danger">Pengeluaran</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaksi->qty }}</td>
                                    <td>Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($transaksi->qty * $transaksi->nominal, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a href="#" class="text-secondary" data-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v fa-lg"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{ route('transaksi.edit', $transaksi) }}">
                                                    <i class="fas fa-edit mr-2"></i> Edit
                                                </a>
                                                <a class="dropdown-item text-danger delete-transaksi-link" href="#" data-id="{{ $transaksi->id_transaksi }}">
                                                    <i class="fas fa-trash-alt mr-2"></i> Hapus
                                                </a>
                                                <form id="delete-form-{{ $transaksi->id_transaksi }}"
                                                    action="{{ route('transaksi.destroy', $transaksi) }}"
                                                    method="POST" style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    // Delete functionality
    document.addEventListener('DOMContentLoaded', function() {
        const deleteLinks = document.querySelectorAll('.delete-transaksi-link');

        deleteLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent dropdown from closing

                const id = this.getAttribute('data-id');
                console.log('Delete link clicked, id:', id);

                const confirmDelete = confirm('Apakah Anda yakin ingin menghapus transaksi ini?');

                if (confirmDelete) {
                    console.log('Confirmed, submitting form for id:', id);
                    document.getElementById('delete-form-' + id).submit();
                } else {
                    console.log('Delete cancelled');
                }
            });
        });

        // Clear all transaksi
        document.getElementById('clear-transaksi-btn').addEventListener('click', function() {
            if (!confirm('Hapus semua transaksi Anda? Tindakan ini tidak bisa dibatalkan.')) return;
            // create form and submit
            const f = document.createElement('form'); f.method='POST'; f.action='{{ route('transaksi.clear') }}';
            const token = document.createElement('input'); token.type='hidden'; token.name='_token'; token.value='{{ csrf_token() }}';
            f.appendChild(token); document.body.appendChild(f); f.submit();
        });
    });
</script>
@endpush
