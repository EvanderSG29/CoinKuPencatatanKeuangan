@extends('layouts.guest')

@section('title', 'Masuk - CoinkuPencatatanKeuangan')

@section('content')
    <div class="auth-header">
        <img src="{{ asset('storage/img/Logo CoinKuPencatatanKeuangan.png') }}" alt="Logo Pencatatan" style="max-height: 160px; margin-bottom: 1rem;" />
    </div>

    <h5 class="auth-title">Masuk ke Akun Anda</h5>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle"></i> Gagal Masuk</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
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

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                name="password" placeholder="Masukkan password Anda" required />
            @error('password')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
            <label class="form-check-label" for="remember">Ingat Saya</label>
        </div>

        <button class="btn btn-primary w-100 mb-3" type="submit">Masuk</button>
    </form>

    <div class="auth-footer">
        @if (Route::has('password.request'))
            <div class="mb-2">
                <a href="{{ route('password.request') }}">Lupa Password?</a>
            </div>
        @endif
        <div>
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </div>
    </div>
@endsection
