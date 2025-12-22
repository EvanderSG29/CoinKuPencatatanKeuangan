<div class="card border-0 shadow-sm">
    <div class="card-body">

        <h5 class="mb-3"><i class="fas fa-id-card text-secondary"></i> Informasi Pengguna</h5>

        <ul class="list-group list-group-flush">

            <li class="list-group-item">
                <i class="fas fa-envelope text-secondary"></i> 
                {{ $user->email ?? '–' }}
            </li>

            <li class="list-group-item">
                <i class="fas fa-phone text-secondary"></i> 
                {{ $user->no_telp ?? '(Belum ditambahkan)' }}
            </li>

            <li class="list-group-item">
                <i class="fas fa-map-marker-alt text-secondary"></i> 
                {{ $user->lokasi ?? '(Belum ditambahkan)' }}
            </li>

            <li class="list-group-item">
                <i class="fas fa-user text-secondary"></i> 
                {{ ucfirst($user->jenis_kelamin ?? '(Belum ditambahkan)') }}
            </li>

            <li class="list-group-item">
                <i class="fas fa-birthday-cake text-secondary"></i> 
                {{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d M Y') : '(Belum ditambahkan)' }}
            </li>

            <li class="list-group-item">
                <i class="fas fa-home text-secondary"></i> 
                {{ $user->alamat ?? '(Belum ditambahkan)' }}
            </li>

        </ul>
    </div>
</div>
