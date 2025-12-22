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
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori:</label>
                <input type="text" class="form-control" name="nama_kategori[]" required placeholder="Contoh: Tabungan, Hiburan & Rekreasi, dll.">
            </div>
        </div>
        
        <button type="button" id="add-input-btn" class="btn btn-secondary mb-3">+ Tambah Input</button>
        <br>
        <button type="submit" class="btn btn-primary">Tambah Kategori</button>
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
