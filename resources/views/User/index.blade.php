@extends('layouts.app')

@section('title', 'Profil Pengguna')
@section('page_heading', 'Profil Pengguna')

@section('card_header')
    <h4 class="m-0">Profil</h4>
@stop

@section('card_body')
<div class="row">

    {{-- Sidebar Profil --}}
    <div class="col-md-4 mb-3">
        <x-profile.sidebar :user="$user" />
    </div>

    {{-- Konten Informasi --}}
    <div class="col-md-8">
        <x-profile.info-list :user="$user" />
    </div>

</div>
@stop
