@extends('layouts.app')

@section('title', 'Edit Profil Pengguna')
@section('page_heading', 'Edit Profil Pengguna')

@section('card_header')
    <h4 class="m-0">Edit Profil</h4>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
@stop

@section('card_body')
<div class="row justify-content-center">
    <div class="col-md-8">

        <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm border-0">
            @csrf
            @method('PUT')

            <div class="card-body">

{{-- Foto Profil (Drag & Drop) --}}
<div class="form-group mb-4">
    <label class="form-label"><strong>Foto Profil</strong></label>

    <div id="drop-area"
        class="border border-2 border-secondary rounded p-4 text-center mb-3"
        style="cursor: pointer; background: #f8f9fa;">
        
        <img id="preview-image"
            src="{{ $user->path_foto ? asset('storage/' . $user->path_foto) : asset('storage/users/profil.png') }}"
            alt="Preview"
            class="rounded-circle mb-3 shadow-sm"
            style="width:110px;height:110px;object-fit:cover;">

        <p class="text-muted mb-1">Drag & drop foto ke sini</p>
        <p class="text-muted">atau</p>

        <button type="button" class="btn btn-outline-primary btn-sm">
            Pilih Foto
        </button>

        <input type="file"
            id="path_foto"
            name="path_foto"
            accept="image/*"
            class="d-none">
    </div>

    <small class="text-muted d-block mt-2">
        • Rekomendasi ukuran: <strong>500 × 500 px</strong><br>
        • Format yang didukung: JPG, PNG<br>
        • Maksimal ukuran file: <strong>2 MB</strong><br>
        • Foto akan dipotong otomatis menjadi bentuk lingkaran
    </small>

    @error('path_foto')
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>


                {{-- Inputan --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label><strong>Nama Lengkap</strong></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label><strong>Username</strong></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username', $user->username) }}">
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label><strong>Email</strong></label>
                    <input type="email" class="form-control" value="{{ $user->email }}" >
                </div>

                <div class="mb-3">
                    <label><strong>Nomor Telepon</strong></label>
                    <input type="text" name="no_telp" class="form-control @error('no_telp') is-invalid @enderror"
                           value="{{ old('no_telp', $user->no_telp) }}">
                    @error('no_telp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label><strong>Lokasi</strong></label>
                    <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                           value="{{ old('lokasi', $user->lokasi) }}">
                    @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label><strong>Jenis Kelamin</strong></label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="laki-laki" {{ $user->jenis_kelamin == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ $user->jenis_kelamin == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label><strong>Tanggal Lahir</strong></label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                           value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                </div>

                <div class="mb-3">
                    <label><strong>Alamat</strong></label>
                    <textarea name="alamat" rows="3" class="form-control">{{ old('alamat', $user->alamat) }}</textarea>
                </div>

            </div>

            <div class="card-footer bg-light text-right">
                <button class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>

    </div>
</div>
@stop

@section('js')
<script>
    document.getElementById('path_foto').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => document.getElementById('preview-foto').src = e.target.result;
        reader.readAsDataURL(file);
    });
</script>

<script>
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('path_foto');
    const previewImg = document.getElementById('preview-image');

    // Klik area untuk membuka file explorer
    dropArea.addEventListener('click', () => fileInput.click());

    // Preview saat pilih file
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        previewFile(file);
    });

    // Drag over styling
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.style.background = "#e9ecef";
    });

    // Drag leave styling
    dropArea.addEventListener('dragleave', () => {
        dropArea.style.background = "#f8f9fa";
    });

    // Drop file
    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.style.background = "#f8f9fa";
        
        const file = e.dataTransfer.files[0];
        fileInput.files = e.dataTransfer.files; // Set ke input
        previewFile(file);
    });

    function previewFile(file) {
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (event) => {
            previewImg.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }
</script>

@endsection
