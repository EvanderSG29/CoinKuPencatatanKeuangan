@extends('layouts.guest')

@section('title', 'Daftar - Pencatatan Keuangan')

@section('content')
    <div class="auth-header">
        @php
            $logoPath = storage_path('app/public/img/Logo CoinKuPencatatanKeuangan.png');
            $logoUrl = file_exists($logoPath) ? asset('storage/img/Logo CoinKuPencatatanKeuangan.png') : route('logo');
        @endphp
        <img src="{{ $logoUrl }}" alt="Logo Pencatatan" style="max-height: 160px; margin-bottom: 1rem;" />
        
    </div>

    <h5 class="auth-title">Buat Akun Baru</h5>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle"></i> Ada Kesalahan</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nama --}}
        <div class="mb-3">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" 
                name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required />
            @error('name')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label" for="email">Email Address</label>
            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" required />
            @error('email')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Username --}}
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input type="text" id="username" class="form-control @error('username') is-invalid @enderror" 
                name="username" value="{{ old('username') }}" placeholder="Pilih username Anda" required />
            @error('username')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                name="password" placeholder="Buat password yang kuat" required />
            @error('password')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label class="form-label" for="password_confirmation">Ulangi Password</label>
            <input type="password" id="password_confirmation" class="form-control" 
                name="password_confirmation" placeholder="Ulangi password Anda" required />
        </div>

        <button class="btn btn-primary w-100 mb-3" type="submit">Daftar</button>
    </form>

    <div class="auth-footer">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </div>
@endsection
