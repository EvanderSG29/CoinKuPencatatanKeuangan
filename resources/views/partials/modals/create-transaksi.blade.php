<!-- Modal Create Transaksi -->
<div class="modal fade" id="createTransaksiModal" tabindex="-1" role="dialog" aria-labelledby="createTransaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createTransaksiModalLabel"><i class="fas fa-plus mr-2"></i>Tambah Transaksi Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('transaksi.store') }}" method="POST" id="create-transaksi-form">
                @csrf
                <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nama_transaksi">Nama Transaksi:</label>
                            <input type="text" class="form-control" name="nama_transaksi[]" required placeholder="Contoh: Beli Makanan, Gaji Bulanan, dll.">
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
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="qty">Qty:</label>
                            <input type="number" class="form-control" name="qty[]" value="1" min="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <p>input lebih dari satu? <a href="{{ route('transaksi.create') }}" class="tx-primary">klik di sini!</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Format nominal input
    $('.nominal-input').on('input', function() {
        var value = $(this).val().replace(/[^\d]/g, '');
        $(this).val(formatRupiah(value));
        $(this).siblings('.nominal-hidden').val(value);
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