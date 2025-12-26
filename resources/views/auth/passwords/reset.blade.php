@extends('layouts.guest')

@section('title', 'Reset Password - Pencatatan Keuangan')

@section('content')
    <div class="auth-header">
        <img src="{{ asset('storage/img/Logo CoinKuPencatatanKeuangan.png') }}" alt="Logo Pencatatan" style="max-height: 160px; margin-bottom: 1rem;" />
    </div>

    <h5 class="auth-title">Reset Password Anda</h5>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        {{-- Password Reset Token --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label" for="email">Email Address</label>
            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ old('email', $request->email) }}" placeholder="Masukkan email Anda" required autofocus />
            @error('email')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                name="password" placeholder="Masukkan password baru" required />
            @error('password')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                name="password_confirmation" placeholder="Konfirmasi password baru" required />
            @error('password_confirmation')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <button class="btn btn-primary w-100 mb-3" type="submit">Reset Password</button>
    </form>

    <div class="auth-footer">
        <div>
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </div>
    </div>
@endsection