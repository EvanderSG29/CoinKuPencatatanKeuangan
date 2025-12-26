@extends('adminlte::page')

@section('sidebar')
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ asset('storage/img/Logo.png') }}" class="img-circle elevation-2" alt="User Image" style="width: 40px; height: 40px;">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{ Auth::user()->name ?? 'User' }}</a>
        </div>
    </div>
    @parent
@endsection

@section('content_header')
    <h3 class="text-dark">@yield('page_heading')</h3>
@stop

@section('content')
<link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <div class="container">
        <div class="card shadow-sm border-0 rounded">
            <div class="card-header bg-white border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    @yield('card_header')
                </div>
            </div>

            <div class="card-body">
                @yield('card_body')
            </div>
        </div>
    </div>
@stop

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Loading</h4>
@stop

@stack('js')
