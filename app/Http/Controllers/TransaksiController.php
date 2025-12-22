<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();
        $transaksis = Transaksi::with('kategori')->where('id_user', $userId)->get();
        $kategoris = Kategori::where('id_user', $userId)->get();

        // Statistik transaksi
        $totalTransaksi = $transaksis->count();
        $totalPemasukan = $transaksis->where('jenis_transaksi', 'pemasukan')->sum('total_nominal');
        $totalPengeluaran = $transaksis->where('jenis_transaksi', 'pengeluaran')->sum('total_nominal');
        $sisaSaldo = $totalPemasukan - $totalPengeluaran;

        // Data untuk grafik tren bulanan
        $monthlyData = Transaksi::where('id_user', $userId)
            ->selectRaw("YEAR(tanggal_transaksi) as year, MONTH(tanggal_transaksi) as month, jenis_transaksi, SUM(total_nominal) as total")
            ->groupBy('year', 'month', 'jenis_transaksi')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->groupBy(['year', 'month']);

        $chartLabels = [];
        $pemasukanData = [];
        $pengeluaranData = [];

        foreach ($monthlyData as $year => $months) {
            foreach ($months as $month => $data) {
                $label = date('M Y', mktime(0, 0, 0, $month, 1, $year));
                if (!in_array($label, $chartLabels)) {
                    $chartLabels[] = $label;
                    $pemasukanData[] = $data->where('jenis_transaksi', 'pemasukan')->sum('total');
                    $pengeluaranData[] = $data->where('jenis_transaksi', 'pengeluaran')->sum('total');
                }
            }
        }

        return view('Transaksi.index', compact(
            'transaksis', 'totalTransaksi', 'totalPemasukan', 'totalPengeluaran', 'sisaSaldo',
            'chartLabels', 'pemasukanData', 'pengeluaranData', 'kategoris'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::where('id_user', auth()->id())->get();
        return view('Transaksi.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_transaksi' => 'required|array',
            'tanggal_transaksi.*' => 'required|date',
            'nama_transaksi' => 'required|array',
            'nama_transaksi.*' => 'required|string|max:255',
            'id_kategori' => 'required|array',
            'id_kategori.*' => 'required|exists:tb_kategori,id_kategori',
            'jenis_transaksi' => 'required|array',
            'jenis_transaksi.*' => 'required|string|max:255',
            'qty' => 'required|array',
            'qty.*' => 'required|integer|min:1',
            'nominal' => 'required|array',
            'nominal.*' => 'required|integer|min:0',
        ]);

        $userId = auth()->id();
        $count = count($request->nama_transaksi);

        for ($i = 0; $i < $count; $i++) {
            $qty = $request->qty[$i];
            $nominal = $request->nominal[$i];

            Transaksi::create([
                'id_user' => $userId,
                'tanggal_transaksi' => $request->tanggal_transaksi[$i],
                'nama_transaksi' => $request->nama_transaksi[$i],
                'id_kategori' => $request->id_kategori[$i],
                'jenis_transaksi' => strtolower($request->jenis_transaksi[$i]),
                'qty' => $qty,
                'nominal' => $nominal,
                'total_nominal' => $qty * $nominal,
            ]);
        }

        // Clear dashboard cache setelah transaksi ditambahkan
        Cache::store('file')->forget("dashboard_data_user_{$userId}");

        return redirect()->route('transaksi.index')
            ->with('success', $count . ' Transaksi berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        $this->authorizeOwnership($transaksi);

        return view('Transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        $this->authorizeOwnership($transaksi);

        $kategoris = Kategori::where('id_user', auth()->id())->get();
        return view('Transaksi.edit', compact('transaksi', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'nama_transaksi' => 'required|string|max:255',
            'id_kategori' => 'required|exists:tb_kategori,id_kategori',
            'jenis_transaksi' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'nominal' => 'required|integer|min:0',
        ]);

        $this->authorizeOwnership($transaksi);

        $qty = $request->qty;
        $nominal = $request->nominal;

        $transaksi->update([
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'nama_transaksi' => $request->nama_transaksi,
            'id_kategori' => $request->id_kategori,
            'jenis_transaksi' => strtolower($request->jenis_transaksi),
            'qty' => $qty,
            'nominal' => $nominal,
            'total_nominal' => $qty * $nominal,
        ]);

        // Clear dashboard cache setelah transaksi diperbarui
        $userId = auth()->id();
        Cache::store('file')->forget("dashboard_data_user_{$userId}");

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
            \Log::info('Attempting to delete transaksi', [
            'transaksi_id' => $transaksi->id_transaksi,
            'transaksi_user_id' => $transaksi->id_user,
            'auth_user_id' => auth()->id()
        ]);

        $this->authorizeOwnership($transaksi);

        $transaksi->delete();

        // Clear dashboard cache setelah transaksi dihapus
        $userId = auth()->id();
        Cache::store('file')->forget("dashboard_data_user_{$userId}");

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Pastikan transaksi dimiliki oleh user yang sedang login.
     */
    private function authorizeOwnership(Transaksi $transaksi)
    {
        if ($transaksi->id_user != auth()->id()) {
            abort(403);
        }
    }

    /**
     * Clear all transaksi for the authenticated user.
     */
    public function clear()
    {
        $userId = auth()->id();
        Transaksi::where('id_user', $userId)->delete();

        return redirect()->route('transaksi.index')->with('success', 'Semua transaksi berhasil dihapus.');
    }
}
