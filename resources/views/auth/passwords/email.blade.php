@extends('layouts.guest')

@section('title', 'Reset Password - Pencatatan Keuangan')

@section('content')
    <div class="auth-header">
        @php
            $logoPath = storage_path('app/public/img/Logo CoinKuPencatatanKeuangan.png');
            $logoUrl = file_exists($logoPath) ? asset('storage/img/Logo CoinKuPencatatanKeuangan.png') : route('logo');
        @endphp
        <img src="{{ $logoUrl }}" alt="Logo Pencatatan" style="max-height: 160px; margin-bottom: 1rem;" />
    </div>

    <h5 class="auth-title">Reset Password Anda</h5>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-check-circle"></i> Berhasil</strong>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label" for="email">Email Address</label>
            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" required autofocus />
            @error('email')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <button class="btn btn-primary w-100 mb-3" type="submit">Kirim Link Reset Password</button>
    </form>

    <div class="auth-footer">
        <div>
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </div>
    </div>
@endsection