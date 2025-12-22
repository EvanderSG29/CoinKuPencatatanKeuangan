@extends('layouts.app')

@section('title', 'Daftar Kategori')


@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-dark mb-1">Manajemen Kategori</h1>
            <p class="text-muted mb-0">Kelola kategori untuk pengeluaran dan pemasukan Anda.</p>
        </div>
        <div class="d-grid gap-2" style="grid-template-columns: 1fr;">
            <button class="btn btn-warning btn-sm">
                <i class="fas fa-trash mr-2"></i>Hapus Semua
            </button>

            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createKategoriModal">
                <i class="fas fa-plus mr-2"></i>Tambah Kategori
            </button>   

            <button id="add-defaults-btn" class="btn btn-secondary btn-sm">
                <i class="fas fa-list mr-2"></i>Tambah Default
            </button>
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
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:60px;">No</th>
                            <th>Nama Kategori</th>
                            <th style="width:80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategoris as $i => $kategori)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $kategori->nama_kategori }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="text-secondary" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item edit-kategori-link" href="#" data-id="{{ $kategori->id_kategori }}" data-nama="{{ $kategori->nama_kategori }}">Edit</a>
                                            <a class="dropdown-item text-danger delete-kategori-link" href="#" data-id="{{ $kategori->id_kategori }}">Hapus</a>
                                            <form id="delete-form-{{ $kategori->id_kategori }}" action="{{ route('kategori.destroy', $kategori) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
<script>
    (function(){
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
        document.querySelector('.btn-warning').addEventListener('click', function(){
            if (!confirm('Hapus semua kategori Anda? Tindakan ini tidak bisa dibatalkan.')) return;
            // create form and submit
            const f = document.createElement('form'); f.method='POST'; f.action='{{ route('kategori.clear') }}';
            const token = document.createElement('input'); token.type='hidden'; token.name='_token'; token.value='{{ csrf_token() }}';
            f.appendChild(token); document.body.appendChild(f); f.submit();
        });

        // delete single category via dropdown link
        document.querySelectorAll('.delete-kategori-link').forEach(function(link){
            link.addEventListener('click', function(e){
                e.preventDefault();
                if (!confirm('Hapus kategori ini?')) return;
                const id = this.getAttribute('data-id');
                document.getElementById('delete-form-' + id).submit();
            });
        });

        // edit category via dropdown link
        document.querySelectorAll('.edit-kategori-link').forEach(function(link){
            link.addEventListener('click', function(e){
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                document.getElementById('edit_nama_kategori').value = nama;
                document.getElementById('edit-kategori-form').action = '{{ url('kategori') }}/' + id;
                $('#editKategoriModal').modal('show');
            });
        });
    })();
</script>

@endpush