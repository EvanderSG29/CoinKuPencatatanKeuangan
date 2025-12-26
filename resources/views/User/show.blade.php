@extends('adminlte::page')

@section('title', 'Profil Pengguna')

@section('content_header')
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
            <h1 class="h3 font-weight-bold text-dark mb-1">Profil Pengguna</h1>
            <p class="text-muted mb-0">Kelola informasi profil dan akun Anda</p>
            </div>
        </div>
@stop

@section('content')

<div class="row">
    <div class="col-md-4">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{ $user->adminlte_image() }}"
                         alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $user->name }}</h3>

                <p class="text-muted text-center">{{ $user->adminlte_desc() }}</p>

                <ul class="list-group list-group-unbordered mb-3">
            
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>No. Telp</b> <a class="float-right">{{ $user->no_telp ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Lokasi</b> <a class="float-right">{{ $user->lokasi ?? '-' }}</a>
                    </li>
                </ul>

                @if(auth()->check() && auth()->user()->id_user == $user->id_user)
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-block"><b>Edit Profil</b></a>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Lengkap</h3>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nama Lengkap</dt>
                    <dd class="col-sm-9">{{ $user->name }}</dd>

                    <dt class="col-sm-3">Username</dt>
                    <dd class="col-sm-9">{{ $user->username }}</dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9">{{ $user->email }}</dd>

                    <dt class="col-sm-3">No. Telepon</dt>
                    <dd class="col-sm-9">{{ $user->no_telp ?? '-' }}</dd>

                    <dt class="col-sm-3">Alamat</dt>
                    <dd class="col-sm-9">{{ $user->alamat ?? '-' }}</dd>

                    <dt class="col-sm-3">Tanggal Lahir</dt>
                    <dd class="col-sm-9">{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d M Y') : '-' }}</dd>

                    <dt class="col-sm-3">Jenis Kelamin</dt>
                    <dd class="col-sm-9">{{ $user->jenis_kelamin ?? '-' }}</dd>

                    <dt class="col-sm-3">Lokasi</dt>
                    <dd class="col-sm-9">{{ $user->lokasi ?? '-' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@stop