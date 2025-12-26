@extends('layouts.guest')

@section('title', 'Verifikasi Email - Pencatatan Keuangan')

@section('content')
    <div class="auth-header">
        <img src="{{ asset('storage/img/Logo CoinKuPencatatanKeuangan.png') }}" alt="Logo Pencatatan" style="max-height: 160px; margin-bottom: 1rem;" />
    </div>

    <h5 class="auth-title">Verifikasi Email Anda</h5>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle"></i> Error</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-4">
        <p class="text-muted">
            Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi. Jika Anda tidak menerima email, kami akan dengan senang hati mengirim ulang.
        </p>
    </div>

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf

        <button class="btn btn-primary w-100 mb-3" type="submit">Kirim Ulang Email Verifikasi</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-secondary w-100" type="submit">Logout</button>
    </form>

    <div class="auth-footer">
        <div>
            Sudah verifikasi? <a href="{{ route('login') }}">Coba login lagi</a>
        </div>
    </div>
@endsection