@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('page_heading', 'Tambah Transaksi')



@section('card_body')
    @php
        $kategoriOptions = [];
        foreach($kategoris as $kategori) {
            $kategoriOptions[$kategori->id_kategori] = $kategori->nama_kategori;
        }
    @endphp
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
                    <div class="col-md-6">
                        <x-adminlte-input name="nama_transaksi[]" label="Nama Transaksi" required>
                        </x-adminlte-input>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="nominal[]" label="Nominal" placeholder="0" required class="nominal-input">
                            <x-slot name="prependSlot">
                                <div class="input-group-text">Rp</div>
                            </x-slot>
                        </x-adminlte-input>
                        <input type="hidden" class="nominal-hidden" name="nominal_hidden[]" value="0">
                    </div>
                </div>

                <!-- Row 2: Tanggal, Kategori, Jenis, Qty -->
                <div class="form-row">
                    <div class="col-md-3">
                        <x-adminlte-input name="tanggal_transaksi[]" label="Tanggal" type="date" required>
                        </x-adminlte-input>
                    </div>
                    <div class="col-md-3">
                        <x-adminlte-select name="id_kategori[]" label="Kategori" required>
                            <x-adminlte-options :options="$kategoriOptions" empty-option="Pilih Kategori"/>
                        </x-adminlte-select>
                    </div>
                    <div class="col-md-3">
                        <x-adminlte-select name="jenis_transaksi[]" label="Jenis" required>
                            <x-adminlte-options :options="['Pemasukan' => 'Pemasukan', 'Pengeluaran' => 'Pengeluaran']" empty-option="Pilih Jenis"/>
                        </x-adminlte-select>
                    </div>
                    <div class="col-md-3">
                        <x-adminlte-input name="qty[]" label="Qty" type="number" value="1" min="1" max="999" required>
                        </x-adminlte-input>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <x-adminlte-button type="button" theme="secondary" label="+ Tambah Form Transaksi" id="add-input-btn"/>
        </div>
        
        <div>
            <x-adminlte-button type="button" theme="light" label="Batal" onclick="window.location.href='{{ route('transaksi.index') }}'"/>
            <x-adminlte-button type="submit" theme="primary" label="Input Transaksi"/>
        </div>
    </form>
@endsection

@section('js')
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
            template.find('input[type="checkbox"]').prop('checked', false);
            
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
@endsection

