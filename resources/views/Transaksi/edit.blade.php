@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('page_heading', )

@section('card_header')
    <h3>Edit Transaksi</h3>
    {{-- <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali ke Daftar Transaksi</a> --}}
@stop

@section('card_body')
    <form action="{{ route('transaksi.update', $transaksi) }}" method="POST" id="transaksi-edit-form">
        @csrf
        @method('PUT')

        <div class="card p-4 mb-3">
     

            <!-- Row 1: Nama dan Nominal -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nama Transaksi:</label>
                    <input type="text" class="form-control" name="nama_transaksi" value="{{ $transaksi->nama_transaksi }}" required>
                </div>

                <div class="form-group col-md-6">
                    <label>Nominal:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input 
                            type="text" 
                            class="form-control nominal-input" 
                            id="nominal_display"
                            value="{{ number_format($transaksi->nominal, 0, ',', '.') }}"
                            required
                        >
                        <input 
                            type="hidden" 
                            name="nominal" 
                            id="nominal_hidden"
                            value="{{ $transaksi->nominal }}"
                        >
                    </div>
                </div>
            </div>

            <!-- Row 2: Tanggal, Kategori, Jenis, Qty -->
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Tanggal:</label>
                    <input 
                        type="date" 
                        class="form-control" 
                        name="tanggal_transaksi" 
                        value="{{ $transaksi->tanggal_transaksi }}" 
                        required
                    >
                </div>

                <div class="form-group col-md-3">
                    <label>Kategori:</label>
                    <select class="form-control" name="id_kategori" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}" 
                                {{ $transaksi->id_kategori == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label>Jenis:</label>
                    <select class="form-control" name="jenis_transaksi" required>
                        <option value="Pemasukan" {{ $transaksi->jenis_transaksi == 'Pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="Pengeluaran" {{ $transaksi->jenis_transaksi == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label>Qty:</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        name="qty" 
                        value="{{ $transaksi->qty }}" 
                        min="1" 
                        max="999" 
                        required
                    >
                </div>
            </div>
        </div>

        <div>
            <a href="{{ route('transaksi.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui Transaksi</button>
        </div>

    </form>
@endsection

@section('js')
<script>
    // Rupiah formatter
    function formatRupiah(value) {
        value = value.replace(/\D/g, '');
        return value ? parseInt(value).toLocaleString('id-ID') : '';
    }

    $(document).ready(function() {
        $('#nominal_display').on('input', function() {
            let raw = $(this).val().replace(/\D/g, '');
            $(this).val(formatRupiah($(this).val()));
            $('#nominal_hidden').val(raw || '0');
        });
    });
</script>
@endsection
