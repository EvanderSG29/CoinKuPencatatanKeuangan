<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $userId = auth()->id();
        $kategoris = Kategori::where('id_user', $userId)->get();

        // Statistik kategori
        $totalKategori = $kategoris->count();
        $kategoriStats = Kategori::where('id_user', $userId)
            ->withCount(['transaksis' => function ($query) {
                $query->where('jenis_transaksi', 'pengeluaran');
            }])
            ->with(['transaksis' => function ($query) {
                $query->where('jenis_transaksi', 'pengeluaran')
                      ->selectRaw('id_kategori, SUM(total_nominal) as total_pengeluaran')
                      ->groupBy('id_kategori');
            }])
            ->get()
            ->map(function ($kategori) {
                $totalPengeluaran = $kategori->transaksis->sum('total_nominal');
                return [
                    'nama_kategori' => $kategori->nama_kategori,
                    'jumlah_transaksi' => $kategori->transaksis_count,
                    'total_pengeluaran' => $totalPengeluaran,
                ];
            });

        // Data untuk chart donut
        $chartData = [];
        foreach ($kategoriStats as $stat) {
            if ($stat['total_pengeluaran'] > 0) {
                $chartData[$stat['nama_kategori']] = $stat['total_pengeluaran'];
            }
        }

        return view('kategori.index', compact('kategoris', 'totalKategori', 'kategoriStats', 'chartData'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|array',
            'nama_kategori.*' => 'required|string|max:255',
        ]);

        $kategoriData = [];
        $userId = auth()->id();
        $now = now();

        // Fetch existing names normalized for server-side duplicate check
        $existing = Kategori::where('id_user', $userId)->get()->pluck('nama_kategori')->map(function($v){
            return mb_strtolower(trim($v));
        })->toArray();

        $duplicates = [];
        foreach ($request->nama_kategori as $nama) {
            $namaTrim = trim($nama);
            if ($namaTrim === '') continue;
            $norm = mb_strtolower($namaTrim);
            if (in_array($norm, $existing)) {
                $duplicates[] = $namaTrim;
                continue;
            }
            $kategoriData[] = [
                'nama_kategori' => $namaTrim,
                'id_user' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $existing[] = $norm; // avoid duplicates within the same batch
        }

        if (!empty($kategoriData)) {
            Kategori::insert($kategoriData);
        }

        $message = '';
        if (!empty($kategoriData)) {
            $message = count($kategoriData) . ' kategori berhasil ditambahkan.';
        }
        if (!empty($duplicates)) {
            $msgDup = ' Duplikat tidak ditambahkan: ' . implode(', ', $duplicates);
            $message = trim($message . ' ' . $msgDup);
        }

        return redirect()->route('kategori.index')->with('success', $message ?: 'Tidak ada kategori baru ditambahkan.');
    }

    /**
     * Preview default categories server-side to detect duplicates.
     */
    public function previewDefaults(Request $request)
    {
        $defaults = $request->input('defaults', []);
        $userId = auth()->id();

        $existing = Kategori::where('id_user', $userId)->get()->pluck('nama_kategori')->map(function($v){
            return mb_strtolower(trim($v));
        })->toArray();

        $result = ['to_add' => [], 'duplicates' => []];
        foreach ($defaults as $d) {
            $norm = mb_strtolower(trim($d));
            if ($norm === '') continue;
            if (in_array($norm, $existing)) {
                $result['duplicates'][] = $d;
            } else {
                $result['to_add'][] = $d;
            }
        }

        return response()->json($result);
    }

    /**
     * Clear all categories for the authenticated user.
     */
    public function clear(Request $request)
    {
        $userId = auth()->id();
        Kategori::where('id_user', $userId)->delete();
        return redirect()->route('kategori.index')->with('success', 'Semua kategori berhasil dihapus.');
    }

    public function show(Kategori $kategori)
    {
        $this->authorizeOwnership($kategori);

        return view('kategori.show', compact('kategori'));
    }

    public function edit(Kategori $kategori)
    {
        $this->authorizeOwnership($kategori);

        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $this->authorizeOwnership($kategori);

        $kategori->update($request->only('nama_kategori'));

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $this->authorizeOwnership($kategori);

        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Pastikan resource dimiliki oleh user yang sedang login.
     */
    private function authorizeOwnership(Kategori $kategori)
    {
        if ($kategori->id_user != auth()->id()) {
            abort(403);
        }
    }
}
