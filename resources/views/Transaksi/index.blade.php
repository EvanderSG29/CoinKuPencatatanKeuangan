@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)


@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-primary mb-1">Manajemen Transaksi</h1>
            <p class="text-muted mb-0">Pantau semua aktivitas finansial Anda dalam satu tempat.</p>
        </div>
        <div class="d-flex align-items-center">
            <x-adminlte-button theme="warning" size="sm" label="Hapus Semua" icon="fas fa-trash" id="clear-transaksi-btn" class="mr-2"/>
            <x-adminlte-button theme="primary" size="sm" label="Tambah Transaksi" icon="fas fa-plus" id="add-transaksi-btn"/>
        </div>
    </div>

    @include('partials.modals.create-transaksi')
    @include('partials.modals.edit-transaksi')

    {{-- BARIS 1 - TABEL TRANSAKSI --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card modern-card border-left-primary shadow h-100 py-2">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">Riwayat Transaksi</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @php
                    $heads = [
                        'Tanggal',
                        'Nama Transaksi',
                        'Kategori',
                        'Jenis',
                        'Qty',
                        'Nominal',
                        'Total',
                        ['label' => 'Aksi', 'no-export' => true, 'width' => 10],
                    ];

                    $config = [
                        'data' => [],
                        'order' => [[0, 'desc']],
                        'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
                        'pageLength' => 10,
                        'lengthMenu' => [10, 25, 50, 100],
                    ];

                    foreach($transaksis as $transaksi) {
                        // Handle legacy data: if id_kategori is not array, try to decode or wrap in array
                        if (!is_array($transaksi->id_kategori)) {
                            $decoded = json_decode($transaksi->id_kategori, true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $decoded = null;
                            }
                            $transaksi->id_kategori = is_array($decoded) ? $decoded : (is_numeric($transaksi->id_kategori) ? [$transaksi->id_kategori] : []);
                        }
                        $kategoriNames = [];
                        if (is_array($transaksi->id_kategori)) {
                            foreach ($transaksi->id_kategori as $id) {
                                $kategori = $kategoris->find($id);
                                if ($kategori) {
                                    $kategoriNames[] = $kategori->nama_kategori;
                                }
                            }
                        }
                        $kategoriDisplay = implode(', ', $kategoriNames) ?: 'N/A';
                        $btnEdit = '<button type="button" class="btn btn-xs btn-default text-primary mx-1 shadow edit-transaksi-btn" title="Edit" data-id="' . $transaksi->id_transaksi . '" data-nama="' . $transaksi->nama_transaksi . '" data-nominal="' . $transaksi->nominal . '" data-tanggal="' . \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d') . '" data-kategori="' . json_encode($transaksi->id_kategori) . '" data-jenis="' . $transaksi->jenis_transaksi . '" data-qty="' . $transaksi->qty . '"><i class="fa fa-lg fa-fw fa-pen"></i></button>';
                        $btnDelete = '<a href="#" class="btn btn-xs btn-default text-danger mx-1 shadow delete-transaksi-link" data-id="' . $transaksi->id_transaksi . '" title="Hapus"><i class="fa fa-lg fa-fw fa-trash"></i></a>';
                        $config['data'][] = [
                            \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y'),
                            $transaksi->nama_transaksi,
                            $kategoriDisplay,
                            strtolower($transaksi->jenis_transaksi) === 'pemasukan' ? '<span class="badge badge-success">Pemasukan</span>' : '<span class="badge badge-danger">Pengeluaran</span>',
                            $transaksi->qty,
                            'Rp ' . number_format($transaksi->nominal, 0, ',', '.'),
                            'Rp ' . number_format($transaksi->qty * $transaksi->nominal, 0, ',', '.'),
                            '<nobr>' . $btnEdit . $btnDelete . '</nobr>',
                        ];
                    }
                    @endphp

                    <x-adminlte-datatable id="transaksi-table" :heads="$heads" :config="$config"
                        head-theme="" theme="" striped hoverable with-buttons beautify />
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<script>
    $(document).ready(function() {
        // Add transaksi button
        $('#add-transaksi-btn').on('click', function() {
            $('#createTransaksiModal').modal('show');
        });

        // Delete functionality
        $('#transaksi-table').on('click', '.delete-transaksi-link', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
                const form = $('<form>', {
                    method: 'POST',
                    action: '{{ url("transaksi") }}/' + id
                });
                form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
                form.append($('<input>', { type: 'hidden', name: '_method', value: 'DELETE' }));
                $('body').append(form);
                form.submit();
            }
        });

        // Clear all transaksi
        $('#clear-transaksi-btn').click(function() {
            if (confirm('Hapus semua transaksi Anda? Tindakan ini tidak bisa dibatalkan.')) {
                const form = $('<form>', {
                    method: 'POST',
                    action: '{{ route("transaksi.clear") }}'
                });
                form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
                $('body').append(form);
                form.submit();
            }
        });

        // Edit transaksi modal
        $('#transaksi-table').on('click', '.edit-transaksi-btn', function() {
            var button = $(this);
            var id = button.data('id');
            var nama = button.data('nama');
            var nominal = button.data('nominal');
            var tanggal = button.data('tanggal');
            var kategori = button.data('kategori');
            var jenis = button.data('jenis');
            var qty = button.data('qty');

            var modal = $('#editTransaksiModal');
            modal.find('#edit-transaksi-form').attr('action', '{{ url("transaksi") }}/' + id);
            modal.find('input[name="nama_transaksi"]').val(nama);
            modal.find('#edit_nominal_hidden').val(nominal);
            modal.find('#edit_nominal_display').val(formatRupiah(nominal));
            modal.find('input[name="tanggal_transaksi"]').val(tanggal);
            modal.find('input[name="id_kategori[]"]').prop('checked', false);
            try {
                var kategoriArray = JSON.parse(kategori);
                if (!Array.isArray(kategoriArray)) kategoriArray = [];
            } catch (e) {
                var kategoriArray = [];
            }
            kategoriArray.forEach(function(id) {
                modal.find('input[name="id_kategori[]"][value="' + id + '"]').prop('checked', true);
            });
            modal.find('input[name="qty"]').val(qty);
            
            modal.on('shown.bs.modal', function() {
                modal.find('#edit_id_kategori option[value="' + kategoriArray[0] + '"]').prop('selected', true);
            });
            
            modal.modal('show');
        });

        // Format nominal input for both modals
        $(document).on('input', '.nominal-input', function() {
            var value = $(this).val().replace(/[^\d]/g, '');
            $(this).siblings('.nominal-hidden').val(value);
        });

        $(document).on('focus', '.nominal-input', function() {
            var value = $(this).siblings('.nominal-hidden').val();
            $(this).val(value);
        });

        $(document).on('blur', '.nominal-input', function() {
            var value = $(this).val().replace(/[^\d]/g, '');
            $(this).val(formatRupiah(value));
            $(this).siblings('.nominal-hidden').val(value);
        });

        function formatRupiah(angka) {
            var number_string = angka.toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                number_string = split[0].substr(0, sisa) + separator + ribuan.join('.');
            }

            number_string = split[1] != undefined ? number_string + ',' + split[1] : number_string;
            return number_string;
        }
    });
</script>
@endpush
