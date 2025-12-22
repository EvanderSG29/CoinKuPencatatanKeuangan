@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('page_heading', 'Edit Kategori')

@section('card_header')
    <h3>Edit Kategori</h3>
    <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
@stop

@section('card_body')
    <form action="{{ route('kategori.update', $kategori) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama_kategori">Nama Kategori:</label>
            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="{{ $kategori->nama_kategori }}" required placeholder="Contoh: Entertainment & Recreation, SAVINGS, dll.">
        </div>
        <button type="submit" class="btn btn-primary">Perbarui Kategori</button>
    </form>
@endsection
