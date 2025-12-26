@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('page_heading', 'Tambah Kategori')

@section('card_header')
    <h3>Tambah Kategori Baru</h3>
    <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
@stop

@section('card_body')
    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf
        <div id="input-container">
            <x-adminlte-input name="nama_kategori[]" label="Nama Kategori" placeholder="Contoh: Tabungan, Hiburan & Rekreasi, dll." required>
            </x-adminlte-input>
        </div>
        
        <x-adminlte-button type="button" theme="secondary" label="+ Tambah Input" id="add-input-btn" class="mb-3"/>
        <br>
        <x-adminlte-button type="submit" theme="primary" label="Tambah Kategori"/>
    </form>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#add-input-btn').click(function() {
            var newInput = `
            <div class="form-group">
                <div class="d-flex align-items-center">
                    <input type="text" class="form-control" name="nama_kategori[]" required placeholder="Kategori Baru">
                    <button type="button" class="btn btn-danger btn-sm ml-2 remove-input-btn">Hapus</button>
                </div>
            </div>`;
            $('#input-container').append(newInput);
        });

        // Gunakan event delegation untuk tombol hapus
        $('#input-container').on('click', '.remove-input-btn', function() {
            $(this).closest('.form-group').remove();
        });
    });
</script>
@endsection
