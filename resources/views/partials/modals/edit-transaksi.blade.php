<!-- Modal Edit Transaksi -->
<div class="modal fade" id="editTransaksiModal" tabindex="-1" role="dialog" aria-labelledby="editTransaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editTransaksiModalLabel"><i class="fas fa-edit mr-2"></i>Edit Transaksi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="edit-transaksi-form">
                @csrf
                @method('PUT')
                <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
                    <!-- Row 1: Nama dan Nominal -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Nama Transaksi:</label>
                            <input type="text" class="form-control" name="nama_transaksi" required>
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
                                    id="edit_nominal_display"
                                    required
                                >
                                <input 
                                    type="hidden" 
                                    id="edit_nominal_hidden"
                                    name="nominal"
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
                                required
                            >
                        </div>
                        <div class="form-group col-md-3">
                            <label>Kategori:</label>
                            <select class="form-control" name="id_kategori" id="edit_id_kategori" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id_kategori }}">
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Jenis:</label>
                            <select class="form-control" name="jenis_transaksi" required>
                                <option value="Pemasukan">Pemasukan</option>
                                <option value="Pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Qty:</label>
                            <input 
                                type="number" 
                                class="form-control" 
                                name="qty" 
                                min="1" 
                                max="999" 
                                required
                            >
                        </div>
                    </div>
                    <p>input lebih dari satu? <a href="{{ route('transaksi.create') }}" class="tx-primary">klik di sini!</a></p>
                </div>
                <div class="modal-footer">
                    <x-adminlte-button type="button" theme="secondary" label="Batal" data-dismiss="modal"/>
                    <x-adminlte-button type="submit" theme="primary" label="Perbarui"/>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Rupiah formatter
    function formatRupiah(value) {
        value = value.replace(/\D/g, '');
        return value ? parseInt(value).toLocaleString('id-ID') : '';
    }

    $('#edit_nominal_display').on('input', function() {
        let raw = $(this).val().replace(/\D/g, '');
        $(this).val(formatRupiah($(this).val()));
        $('#edit_nominal_hidden').val(raw || '0');
    });
});
</script>
