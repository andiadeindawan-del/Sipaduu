<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kbli;

class KbliController extends Controller
{
    /**
     * Get unique categories
     */
    public function categories()
    {
        $categories = Kbli::where('aktif', true)
            ->whereNotNull('kategori_kode')
            ->select('kategori_kode', 'kategori_nama')
            ->distinct()
            ->orderBy('kategori_kode')
            ->get();
            
        return response()->json($categories);
    }

    /**
     * Get unique golongans by category
     */
    public function golongans(Request $request)
    {
        $kategori = $request->get('kategori');
        
        if (!$kategori) {
            return response()->json([]);
        }

        $golongans = Kbli::where('aktif', true)
            ->where('kategori_kode', $kategori)
            ->whereNotNull('golongan_pokok_kode')
            ->select('golongan_pokok_kode', 'golongan_pokok_nama')
            ->distinct()
            ->orderBy('golongan_pokok_kode')
            ->get();
            
        return response()->json($golongans);
    }

    /**
     * Search KBLI
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        $kategori = $request->get('kategori');
        $golongan = $request->get('golongan');

        $query = Kbli::where('aktif', true);

        // Strict filtering by Kategori & Golongan
        if ($kategori) {
            $query->where('kategori_kode', $kategori);
        }
        
        if ($golongan) {
            $query->where('golongan_pokok_kode', $golongan);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhere('uraian', 'like', "%{$search}%");
            });

            // Urutan prioritas pencarian:
            // 1. Kode sama persis
            // 2. Kode diawali keyword
            // 3. Judul sama persis
            // 4. Judul mengandung keyword
            $query->orderByRaw("CASE 
                WHEN kode = ? THEN 1
                WHEN kode LIKE ? THEN 2
                WHEN judul = ? THEN 3
                WHEN judul LIKE ? THEN 4
                ELSE 5
            END ASC", [$search, "{$search}%", $search, "%{$search}%"]);
        }

        $kblis = $query->limit(50)->get();

        $formatted = $kblis->map(function ($kbli) {
            return [
                'id' => $kbli->id,
                'text' => $kbli->kode . ' - ' . $kbli->judul,
                'judul' => $kbli->judul,
                'kode' => $kbli->kode,
                'uraian' => $kbli->uraian,
                'versi' => $kbli->versi,
                'kategori' => $kbli->kategori_kode ? $kbli->kategori_kode . ' - ' . $kbli->kategori_nama : $kbli->kategori,
                'golongan_pokok' => $kbli->golongan_pokok_kode ? $kbli->golongan_pokok_kode . ' - ' . $kbli->golongan_pokok_nama : null,
                'golongan' => $kbli->golongan_kode ? $kbli->golongan_kode . ' - ' . $kbli->golongan_nama : null,
                'subgolongan' => $kbli->subgolongan_kode ? $kbli->subgolongan_kode . ' - ' . $kbli->subgolongan_nama : null,
                'kelompok' => $kbli->kelompok_kode ? $kbli->kelompok_kode . ' - ' . $kbli->kelompok_nama : null,
            ];
        });

        return response()->json($formatted);
    }
}

