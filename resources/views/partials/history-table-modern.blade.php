@if($data->count())
<div class="table-responsive">
    <table class="table table-borderless table-hover mb-0">
        <thead class="thead-light">
            <tr>
                <th class="border-0">Nama Transaksi</th>
                <th class="border-0">Kategori</th>
                <th class="border-0">Tanggal</th>
                <th class="border-0 text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $t)
                <tr class="modern-table-row">
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="badge badge-{{ $type }} mr-2">
                                <i class="fas fa-{{ $type == 'success' ? 'arrow-up' : 'arrow-down' }}"></i>
                            </div>
                            <span class="font-weight-medium">{{ $t->nama_transaksi }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-light">{{ $t->kategori->nama_kategori ?? '-' }}</span>
                    </td>
                    <td class="text-muted">
                        {{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}
                    </td>
                    <td class="text-right font-weight-bold text-{{ $type }}">
                        Rp {{ number_format($t->total_nominal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-4">
    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
    <p class="text-muted">Belum ada transaksi {{ $type == 'success' ? 'pemasukan' : 'pengeluaran' }}.</p>
</div>
@endif