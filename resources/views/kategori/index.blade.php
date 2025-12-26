@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-dark mb-1">Manajemen Kategori</h1>
            <p class="text-muted mb-0">Kelola kategori untuk pengeluaran dan pemasukan Anda.</p>
        </div>
        <div class="d-grid gap-2" style="grid-template-columns: 1fr;">
            <x-adminlte-button theme="warning" size="sm" label="Hapus Semua" icon="fas fa-trash" id="clear-kategori-btn"/>
            <x-adminlte-button theme="primary" size="sm" label="Tambah Kategori" icon="fas fa-plus" id="add-kategori-btn"/>
            <x-adminlte-button theme="secondary" size="sm" label="Tambah Default" icon="fas fa-list" id="add-defaults-btn"/>
        </div>
    </div>
    {{-- ISI HALAMAN --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="default-categories-form" action="{{ route('kategori.store') }}" method="POST" style="display:none;">
        @csrf
        <!-- hidden inputs will be injected by JS -->
    </form>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Preview Kategori Default</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" id="preview-list">
                        <!-- items injected here -->
                    </div>
                    <div class="alert alert-warning mt-3" id="preview-note" style="display:none;">Beberapa kategori terlihat duplikat (kuning) dan tidak akan disubmit.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirm-add-defaults">Tambahkan Kategori</button>
                </div>
            </div>
        </div>
    </div>

    @include('partials.modals.create-kategori')
    @include('partials.modals.edit-kategori')

    {{-- TABEL KATEGORI --}}
    <div class="card shadow modern-card">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori</h6>
        </div>
        <div class="card-body">
            @php
            $heads = [
                ['label' => 'No', 'width' => 10],
                'Nama Kategori',
                ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
            ];

            $config = [
                'data' => [],
                'order' => [[0, 'asc']],
                'columns' => [null, null, ['orderable' => false]],
                'pageLength' => 10,
                'lengthMenu' => [10, 25, 50, 100],
            ];

            foreach($kategoris as $i => $kategori) {
                $btnEdit = '<a href="#" class="btn btn-xs btn-default text-primary mx-1 shadow edit-kategori-link" data-id="' . $kategori->id_kategori . '" data-nama="' . $kategori->nama_kategori . '" title="Edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>';
                $btnDelete = '<a href="#" class="btn btn-xs btn-default text-danger mx-1 shadow delete-kategori-link" data-id="' . $kategori->id_kategori . '" title="Hapus"><i class="fa fa-lg fa-fw fa-trash"></i></a>';
                $config['data'][] = [
                    $i + 1,
                    $kategori->nama_kategori,
                    '<nobr>' . $btnEdit . $btnDelete . '</nobr>',
                ];
            }
            @endphp

            <x-adminlte-datatable id="kategori-table" :heads="$heads" :config="$config"
                head-theme="" theme="" striped hoverable with-buttons beautify />
        </div>
    </div>

</div>
@endsection

@push('js')
<style>
@media (min-width: 768px) {
    .d-grid {
        grid-template-columns: auto auto auto !important;
        justify-content: flex-end;
    }
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<script>
    (function(){
        // Add kategori button
        document.getElementById('add-kategori-btn').addEventListener('click', function() {
            $('#createKategoriModal').modal('show');
        });

        const defaults = [
            'Hiburan & Rekreasi',
            'Tabungan',
            'Kesehatan',
            'Perawatan Diri',
            'Pendidikan',
            'Pengembangan Diri',
            'Elektronik',
            'Pakaian & Penampilan',
            'Langganan',
            'Cicilan',
            'Kartu Kredit',
            'Gaji',
            'Uang Jajan'
        ];

        function normalize(s){ return String(s||'').trim().toLowerCase(); }

        // Show preview modal and fetch server-side duplicate check
        document.getElementById('add-defaults-btn').addEventListener('click', function(){
            fetch("{{ route('kategori.previewDefaults') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ defaults: defaults })
            })
            .then(r => r.json())
            .then(data => {
                const container = document.getElementById('preview-list');
                container.innerHTML = '';
                const dupSet = new Set((data.duplicates||[]).map(d=>normalize(d)));
                (defaults).forEach(function(name){
                    const col = document.createElement('div'); col.className='col-md-4 mb-2';
                    const card = document.createElement('div'); card.className='p-2 border rounded';
                    const title = document.createElement('div'); title.textContent = name;
                    if (dupSet.has(normalize(name))) {
                        card.style.background = '#fff7e6'; // light yellow
                        const note = document.createElement('small'); note.className='text-warning d-block'; note.textContent='Duplikat - tidak akan ditambahkan';
                        card.appendChild(title); card.appendChild(note);
                    } else {
                        card.style.background = '#e9f7ef'; // light green
                        const note = document.createElement('small'); note.className='text-success d-block'; note.textContent='Akan ditambahkan';
                        card.appendChild(title); card.appendChild(note);
                    }
                    col.appendChild(card); container.appendChild(col);
                });
                // Show note if there are duplicates
                document.getElementById('preview-note').style.display = (data.duplicates && data.duplicates.length) ? 'block' : 'none';
                $('#previewModal').modal('show');
            });
        });

        // Confirm add defaults - submit only non-duplicates
        document.getElementById('confirm-add-defaults').addEventListener('click', function(){
            // determine which to add from preview list (green cards)
            const container = document.getElementById('preview-list');
            const toAdd = [];
            container.querySelectorAll('.p-2.border').forEach(function(card){
                const txt = card.querySelector('div').textContent || '';
                if (!card.querySelector('.text-warning')) { toAdd.push(txt.trim()); }
            });
            if (!toAdd.length) { alert('Tidak ada kategori baru untuk ditambahkan.'); return; }
            const form = document.getElementById('default-categories-form');
            form.querySelectorAll('input[name="nama_kategori[]"]').forEach(i=>i.remove());
            toAdd.forEach(function(name){
                const input = document.createElement('input'); input.type='hidden'; input.name='nama_kategori[]'; input.value = name; form.appendChild(input);
            });
            $('#previewModal').modal('hide');
            form.submit();
        });

        // delete all categories (atur ke awal)
        document.getElementById('clear-kategori-btn').addEventListener('click', function(){
            if (!confirm('Hapus semua kategori Anda? Tindakan ini tidak bisa dibatalkan.')) return;
            // create form and submit
            const f = document.createElement('form'); f.method='POST'; f.action='{{ route('kategori.clear') }}';
            const token = document.createElement('input'); token.type='hidden'; token.name='_token'; token.value='{{ csrf_token() }}';
            f.appendChild(token); document.body.appendChild(f); f.submit();
        });

        // delete single category via link
        $('#kategori-table').on('click', '.delete-kategori-link', function(e){
            e.preventDefault();
            const id = $(this).data('id');
            if (confirm('Hapus kategori ini?')) {
                const form = $('<form>', {
                    method: 'POST',
                    action: '{{ url("kategori") }}/' + id
                });
                form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
                form.append($('<input>', { type: 'hidden', name: '_method', value: 'DELETE' }));
                $('body').append(form);
                form.submit();
            }
        });

        // edit category via link
        $(document).on('click', '.edit-kategori-link', function(e){
            e.preventDefault();
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const modal = $('#editKategoriModal');
            modal.find('input[name="nama_kategori"]').val(nama);
            modal.find('#edit-kategori-form').attr('action', '{{ url('kategori') }}/' + id);
            modal.modal('show');
        });
    })();
</script>

@endpush