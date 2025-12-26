<!-- Modal Create Transaksi -->
<div class="modal fade" id="createTransaksiModal" tabindex="-1" role="dialog" aria-labelledby="createTransaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="createTransaksiModalLabel"><i class="fas fa-plus mr-2"></i>Tambah Transaksi Baru</h5>
                <button type="button" class="close text-black" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('transaksi.store') }}" method="POST" id="create-transaksi-form">
                @csrf
                <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
                    <div class="form-row">
                        <div class="col-md-6">
                            <x-adminlte-input name="nama_transaksi" label="Nama Transaksi" placeholder="Contoh: Beli Makanan, Gaji Bulanan, dll." required>
                            </x-adminlte-input>
                        </div>
                        <div class="col-md-6">
                            <x-adminlte-input name="nominal_display" label="Nominal" placeholder="0" required class="nominal-input" id="nominal_display">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">Rp</div>
                                </x-slot>
                            </x-adminlte-input>
                            <input type="hidden" id="nominal_hidden" name="nominal" value="0">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3">
                            <x-adminlte-input name="tanggal_transaksi" label="Tanggal" type="date" required>
                            </x-adminlte-input>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-select name="id_kategori" label="Kategori" required>
                                <x-adminlte-options :options="$kategoris->pluck('nama_kategori', 'id_kategori')->toArray()" empty-option="Pilih Kategori"/>
                            </x-adminlte-select>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-select name="jenis_transaksi" label="Jenis Transaksi" required>
                                <x-adminlte-options :options="['Pemasukan' => 'Pemasukan', 'Pengeluaran' => 'Pengeluaran']" />
                            </x-adminlte-select>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-input name="qty" label="Qty" type="number" value="1" min="1" required>
                            </x-adminlte-input>
                        </div>
                    </div>
                                        <p>input lebih dari satu? <a href="{{ route('transaksi.create') }}" class="tx-primary">klik di sini!</a></p>
                </div>
                <div class="modal-footer">
                    <x-adminlte-button type="button" theme="secondary" label="Batal" data-dismiss="modal"/>
                    <x-adminlte-button type="submit" theme="primary" label="Simpan"/>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initial format
    var value = $('#nominal_hidden').val();
    $('#nominal_display').val(formatRupiah(value));

    // Format nominal input
    $('#nominal_display').on('input', function() {
        var value = $(this).val().replace(/[^\d]/g, '');
        $('#nominal_hidden').val(value);
    });

    $('#nominal_display').on('focus', function() {
        var value = $('#nominal_hidden').val();
        $(this).val(value);
    });

    $('#nominal_display').on('blur', function() {
        var value = $(this).val().replace(/[^\d]/g, '');
        $(this).val(formatRupiah(value));
        $('#nominal_hidden').val(value);
    });

    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }
});
</script>