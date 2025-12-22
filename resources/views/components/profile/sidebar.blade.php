<div class="card border-0 shadow-sm">
    <div class="card-body text-center">

        {{-- Foto Profil --}}
        @php 
            $photo = $user->path_foto 
                ? asset('storage/' . $user->path_foto) 
                : asset('storage/users/profil.png');
        @endphp

        <img src="{{ $photo }}" 
             class="rounded-circle shadow-sm mb-3"
             style="width:120px;height:120px;object-fit:cover;">

        <h5 class="mb-0">{{ $user->name }}</h5>
        <p class="text-muted mb-2">{{ '@'.$user->username }}</p>

        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm w-100 mb-2">
            <i class="fas fa-edit"></i> Edit Profil
        </a>

        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm w-100">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
