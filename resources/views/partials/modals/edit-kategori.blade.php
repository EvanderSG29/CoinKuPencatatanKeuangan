<!-- Modal Edit Kategori -->
<div class="modal fade" id="editKategoriModal" tabindex="-1" role="dialog" aria-labelledby="editKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editKategoriModalLabel"><i class="fas fa-edit mr-2"></i>Edit Kategori</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-kategori-form" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <x-adminlte-input name="nama_kategori" label="Nama Kategori" placeholder="Masukkan nama kategori baru" required>
                    </x-adminlte-input>
                </div>
                <div class="modal-footer">
                    <x-adminlte-button type="button" theme="secondary" label="Batal" data-dismiss="modal"/>
                    <x-adminlte-button type="submit" theme="primary" label="Perbarui"/>
                </div>
            </form>
        </div>
    </div>
</div>