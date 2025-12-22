<!-- Modal Create Kategori -->
<div class="modal fade" id="createKategoriModal" tabindex="-1" role="dialog" aria-labelledby="createKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createKategoriModalLabel"><i class="fas fa-plus mr-2"></i>Tambah Kategori Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST" id="create-kategori-form">
                @csrf
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <div id="input-container-modal">
                        <div class="form-group">
                            <label for="nama_kategori">Nama Kategori:</label>
                            <input type="text" class="form-control" name="nama_kategori[]" required placeholder="Contoh: Tabungan, Hiburan & Rekreasi, dll.">
                        </div>
                    </div>
                                        <p>input lebih dari satu? <a href="{{ route('kategori.create') }}" class="tx-primary">klik di sini!</a></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#add-input-btn-modal').click(function() {
        var newInput = `
        <div class="form-group">
            <div class="d-flex align-items-center">
                <input type="text" class="form-control" name="nama_kategori[]" required placeholder="Kategori Baru">
                <button type="button" class="btn btn-danger btn-sm ml-2 remove-input-btn-modal">Hapus</button>
            </div>
        </div>`;
        $('#input-container-modal').append(newInput);
    });

    $('#input-container-modal').on('click', '.remove-input-btn-modal', function() {
        $(this).closest('.form-group').remove();
    });
});
</script>