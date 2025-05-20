<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siklus;
use App\Models\Panen;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $siklusList = Siklus::with(['bibits', 'pakans', 'panens'])->get();
        $siklusAktif = $request->siklus_id
            ? Siklus::with(['bibits', 'pakans', 'panens'])->find($request->siklus_id)
            : $siklusList->first();

        // Get or create panen data for the active siklus
        $panen = $siklusAktif && $siklusAktif->panens->isNotEmpty()
            ? $siklusAktif->panens->first()
            : new Panen(['siklus_id' => $siklusAktif->id ?? null]);

        // Prepare transactions data
        $transaksi = collect();

        if ($siklusAktif) {
            $transaksi = $transaksi->merge(
                $siklusAktif->bibits->map(function($item) {
                    return [
                        'tanggal' => $item->tanggal,
                        'kategori' => 'Bibit',
                        'tipe' => $item->type,
                        'kuantitas' => $item->kuantitas,
                        'harga' => $item->total_harga,
                        'jenis' => 'pengeluaran'
                    ];
                })
            )->merge(
                $siklusAktif->pakans->map(function($item) {
                    return [
                        'tanggal' => $item->tanggal,
                        'kategori' => 'Pakan',
                        'tipe' => $item->tipe,
                        'kuantitas' => $item->kuantitas,
                        'harga' => $item->total_harga,
                        'jenis' => 'pengeluaran'
                    ];
                })
            )->merge(
                $siklusAktif->panens->map(function($item) {
                    return [
                        'tanggal' => $item->tanggal,
                        'kategori' => 'Panen',
                        'tipe' => '-',
                        'kuantitas' => $item->kuantitas,
                        'harga' => $item->total_harga,
                        'jenis' => 'pemasukan'
                    ];
                })
            )->sortBy('tanggal');
        }

        return view('keuangan', [
            'siklusList' => $siklusList,
            'siklusAktif' => $siklusAktif,
            'transaksi' => $transaksi,
            'panen' => $panen,
            'totalPengeluaran' => $siklusAktif ? ($siklusAktif->bibits->sum('total_harga') + $siklusAktif->pakans->sum('total_harga')) : 0,
            'totalPemasukan' => $panen->total_harga ?? 0
        ]);
    }
}
