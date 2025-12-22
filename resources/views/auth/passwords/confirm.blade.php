@extends('layouts.guest')

@section('title', 'Konfirmasi Password - Pencatatan Keuangan')

@section('content')
    <div class="auth-header">
        @php
            $logoPath = storage_path('app/public/img/Logo CoinKuPencatatanKeuangan.png');
            $logoUrl = file_exists($logoPath) ? asset('storage/img/Logo CoinKuPencatatanKeuangan.png') : route('logo');
        @endphp
        <img src="{{ $logoUrl }}" alt="Logo Pencatatan" style="max-height: 160px; margin-bottom: 1rem;" />
    </div>

    <h5 class="auth-title">Konfirmasi Password Anda</h5>

    <div class="mb-4">
        <p class="text-muted">
            Silakan konfirmasi password Anda sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                name="password" placeholder="Masukkan password Anda" required autofocus />
            @error('password')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <button class="btn btn-primary w-100 mb-3" type="submit">Konfirmasi</button>
    </form>

    <div class="auth-footer">
        @if (Route::has('password.request'))
            <div class="mb-2">
                <a href="{{ route('password.request') }}">Lupa Password?</a>
            </div>
        @endif
        <div>
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </div>
    </div>
@endsection