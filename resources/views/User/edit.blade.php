@extends('layouts.app')

@section('title', 'Edit Profil Pengguna')
@section('page_heading', 'Edit Profil Pengguna')

@section('card_header')
    <h4 class="m-0">Edit Profil</h4>
    <x-adminlte-button theme="secondary" size="sm" label="Kembali" onclick="window.location.href='{{ route('users.index') }}'"/>
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
                        <x-adminlte-input name="name" label="Nama Lengkap" value="{{ old('name', $user->name) }}">
                        </x-adminlte-input>
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-adminlte-input name="username" label="Username" value="{{ old('username', $user->username) }}">
                        </x-adminlte-input>
                    </div>
                </div>

                <x-adminlte-input name="email" label="Email" value="{{ $user->email }}" readonly>
                </x-adminlte-input>

                <x-adminlte-input name="no_telp" label="Nomor Telepon" value="{{ old('no_telp', $user->no_telp) }}">
                </x-adminlte-input>

                <x-adminlte-input name="lokasi" label="Lokasi" value="{{ old('lokasi', $user->lokasi) }}">
                </x-adminlte-input>

                <x-adminlte-select name="jenis_kelamin" label="Jenis Kelamin">
                    <x-adminlte-options :options="['laki-laki' => 'Laki-laki', 'perempuan' => 'Perempuan']" empty-option="-- Pilih --" :selected="$user->jenis_kelamin"/>
                </x-adminlte-select>

                <x-adminlte-input name="tanggal_lahir" label="Tanggal Lahir" type="date" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                </x-adminlte-input>

                <x-adminlte-textarea name="alamat" label="Alamat" rows="3" value="{{ old('alamat', $user->alamat) }}">
                </x-adminlte-textarea>

            </div>

            <div class="card-footer bg-light text-right">
                <x-adminlte-button type="submit" theme="primary" label="Simpan Perubahan"/>
                <x-adminlte-button type="button" theme="secondary" label="Batal" onclick="window.location.href='{{ route('users.index') }}'"/>
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
