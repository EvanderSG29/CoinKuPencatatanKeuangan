@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('page_heading', 'Tambah Transaksi')

@section('card_header')
    <h3>Tambah Transaksi Baru</h3>
@stop

@section('card_body')
    <form action="{{ route('transaksi.store') }}" method="POST" id="transaksi-form">
        @csrf
        <div id="input-container">
            <!-- Transaction entry template -->
            <div class="card p-4 mb-3 transaction-entry">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="entry-title mb-0">Transaksi 1</h5>
                    <button type="button" class="btn btn-sm btn-danger remove-input-btn" style="display: none;">Hapus</button>
                </div>
                
                <!-- Row 1: Nama (6) dan Nominal (4) -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nama_transaksi">Nama Transaksi:</label>
                        <input type="text" class="form-control" name="nama_transaksi[]" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="nominal">Nominal:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control nominal-input" name="nominal[]" placeholder="0" required>
                            <input type="hidden" class="nominal-hidden" name="nominal_hidden[]" value="0">
                        </div>
                    </div>
                </div>

                <!-- Row 2: Tanggal, Kategori, Jenis, Qty -->
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="tanggal_transaksi">Tanggal:</label>
                        <input type="date" class="form-control" name="tanggal_transaksi[]" required> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for="id_kategori">Kategori:</label>
                        <select class="form-control" name="id_kategori[]" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="jenis_transaksi">Jenis:</label>
                        <select class="form-control" name="jenis_transaksi[]" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Pemasukan">Pemasukan</option>
                            <option value="Pengeluaran">Pengeluaran</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="qty">Qty:</label>
                        <input type="number" class="form-control" name="qty[]" value="1" min="1" max="999" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <button type="button" class="btn btn-secondary" id="add-input-btn">+ Tambah Form Transaksi</button>
        </div>
        
        <div>
            <a href="{{ route('transaksi.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary">Input Transaksi</button>
        </div>
    </form>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Function to format number as Rupiah
        function formatRupiah(value) {
            value = value.replace(/\D/g, '');
            return value ? parseInt(value).toLocaleString('id-ID') : '';
        }

        // Function to update entry titles and button visibility
        function updateEntries() {
            const entries = $('.transaction-entry');
            entries.each(function(index) {
                $(this).find('.entry-title').text('Transaksi ' + (index + 1));
                $(this).find('.remove-input-btn').toggle(entries.length > 1);
            });
        }

        // Rupiah input formatting
        $(document).on('input', '.nominal-input', function() {
            let value = $(this).val();
            let formatted = formatRupiah(value);
            $(this).val(formatted);
            
            // Store actual numeric value in hidden field
            let numericValue = value.replace(/\D/g, '') || '0';
            $(this).closest('.input-group').find('.nominal-hidden').val(numericValue);
        });

        // Handle actual form submission - move numeric values back to nominal[]
        $('#transaksi-form').on('submit', function(e) {
            $('.nominal-hidden').each(function(index) {
                $('input[name="nominal[]"]').eq(index).val($(this).val());
            });
        });

        // Add new transaction form
        $('#add-input-btn').click(function() {
            const template = $('.transaction-entry').first().clone();
            template.find('input[type="text"]').val('');
            template.find('input[type="date"]').val('');
            template.find('input[type="number"]').val(function(i, val) {
                return val === '1' ? '1' : '';
            });
            template.find('select').prop('selectedIndex', 0);
            template.find('.nominal-hidden').val('0');
            
            $('#input-container').append(template);
            updateEntries();
        });

        // Remove transaction form
        $(document).on('click', '.remove-input-btn', function() {
            $(this).closest('.transaction-entry').remove();
            updateEntries();
        });

        // Initialize
        updateEntries();
    });
</script>
@endpush

