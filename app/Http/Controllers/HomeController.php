<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $userId = auth()->id();

        // Cache key untuk user
        $cacheKey = "dashboard_data_user_{$userId}";
        $cacheTime = 30; // 30 detik untuk development

        $data = Cache::store('file')->remember($cacheKey, $cacheTime, function () use ($userId) {
            // ======================
            // 1. STATISTIK UTAMA
            // =======================

            $pemasukan = Transaksi::where('jenis_transaksi', 'pemasukan')
                ->where('id_user', $userId)
                ->sum('total_nominal') ?? 0;

            $pengeluaran = Transaksi::where('jenis_transaksi', 'pengeluaran')
                ->where('id_user', $userId)
                ->sum('total_nominal') ?? 0;

            $sisaSaldo = $pemasukan - $pengeluaran;

            $totalTransaksi = Transaksi::where('id_user', $userId)->count();

            $recentTransaksi = Transaksi::where('id_user', $userId)
                ->with('kategori')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $recentPemasukan = Transaksi::where('id_user', $userId)
                ->where('jenis_transaksi', 'pemasukan')
                ->with('kategori')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $recentPengeluaran = Transaksi::where('id_user', $userId)
                ->where('jenis_transaksi', 'pengeluaran')
                ->with('kategori')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // ======================
            // 2. TOP 3 KATEGORI
            // ======================

            $pengeluaranTop = DB::table('tb_transaksi')
                ->join('tb_kategori', 'tb_transaksi.id_kategori', '=', 'tb_kategori.id_kategori')
                ->where('tb_transaksi.jenis_transaksi', 'pengeluaran')
                ->where('tb_transaksi.id_user', $userId)
                ->select('tb_kategori.nama_kategori', DB::raw('SUM(tb_transaksi.total_nominal) as total_pengeluaran'))
                ->groupBy('tb_kategori.id_kategori', 'tb_kategori.nama_kategori')
                ->orderByDesc('total_pengeluaran')
                ->limit(3)
                ->get();

            $pemasukanTop = DB::table('tb_transaksi')
                ->join('tb_kategori', 'tb_transaksi.id_kategori', '=', 'tb_kategori.id_kategori')
                ->where('tb_transaksi.jenis_transaksi', 'pemasukan')
                ->where('tb_transaksi.id_user', $userId)
                ->select('tb_kategori.nama_kategori', DB::raw('SUM(tb_transaksi.total_nominal) as total_pemasukan'))
                ->groupBy('tb_kategori.id_kategori', 'tb_kategori.nama_kategori')
                ->orderByDesc('total_pemasukan')
                ->limit(3)
                ->get();

            // ======================
            // 3. LINE CHART (12 bulan atau filter manual)
            // ======================

            $start = request('start_date');
            $end   = request('end_date');

            $chartQuery = DB::table('tb_transaksi')
                ->select(
                    DB::raw("date(tanggal_transaksi) as tgl"),
                    DB::raw("SUM(CASE WHEN jenis_transaksi = 'pemasukan' THEN total_nominal ELSE 0 END) as pemasukan"),
                    DB::raw("SUM(CASE WHEN jenis_transaksi = 'pengeluaran' THEN total_nominal ELSE 0 END) as pengeluaran")
                )
                ->where('id_user', $userId);

            if ($start && $end) {
                $chartQuery->whereBetween('tanggal_transaksi', [$start, $end]);
            } else {
                // default: 12 bulan terakhir
                $chartQuery->where('tanggal_transaksi', '>=', Carbon::now()->subMonths(12));
            }

            $chartData = $chartQuery
                ->groupBy(DB::raw("date(tanggal_transaksi)"))
                ->orderBy('tgl', 'ASC')
                ->get();

            $labels = $chartData->pluck('tgl')->map(fn($d) => Carbon::parse($d)->format('d M'));
            $chartPemasukan   = $chartData->pluck('pemasukan');
            $chartPengeluaran = $chartData->pluck('pengeluaran');

            // ======================
            // 4. DONUT CHART – kategori pemasukan & pengeluaran
            // ======================

            // Label kategori user
            $kategoriLabels = DB::table('tb_kategori')
                ->where('id_user', $userId)
                ->pluck('nama_kategori', 'id_kategori');

            // Ambil data pemasukan per kategori (hanya yang ada transaksinya)
            $pemasukanKategoriRaw = DB::table('tb_transaksi')
                ->select('id_kategori', DB::raw('SUM(total_nominal) as total'))
                ->where('jenis_transaksi', 'pemasukan')
                ->where('id_user', $userId)
                ->groupBy('id_kategori')
                ->pluck('total', 'id_kategori');

            // Ambil data pengeluaran per kategori (hanya yang ada transaksinya)
            $pengeluaranKategoriRaw = DB::table('tb_transaksi')
                ->select('id_kategori', DB::raw('SUM(total_nominal) as total'))
                ->where('jenis_transaksi', 'pengeluaran')
                ->where('id_user', $userId)
                ->groupBy('id_kategori')
                ->pluck('total', 'id_kategori');

            // Buat array terstruktur untuk chart - pastikan urutan cocok dengan kategoriLabels
            $kategoriLabelsArray = [];
            $pemasukanKategoriArray = [];
            $pengeluaranKategoriArray = [];

            foreach ($kategoriLabels as $idKategori => $namaKategori) {
                $kategoriLabelsArray[] = $namaKategori;
                $pemasukanKategoriArray[] = $pemasukanKategoriRaw->get($idKategori, 0);
                $pengeluaranKategoriArray[] = $pengeluaranKategoriRaw->get($idKategori, 0);
            }
            
            return [
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'sisaSaldo' => $sisaSaldo,
                'pengeluaranTop' => $pengeluaranTop,
                'pemasukanTop' => $pemasukanTop,
                'totalTransaksi' => $totalTransaksi,
                'recentTransaksi' => $recentTransaksi,
                'recentPemasukan' => $recentPemasukan,
                'recentPengeluaran' => $recentPengeluaran,
                'labels' => $labels,
                'chartPemasukan' => $chartPemasukan,
                'chartPengeluaran' => $chartPengeluaran,
                'start' => $start,
                'end' => $end,
                'kategoriLabels' => $kategoriLabelsArray,
                'pemasukanKategori' => $pemasukanKategoriArray,
                'pengeluaranKategori' => $pengeluaranKategoriArray,
            ];
        });

        // =================================================================
        // RETURN KE VIEW
        // =================================================================

        return view('home', $data);
    }
}