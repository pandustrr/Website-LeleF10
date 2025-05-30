<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siklus;

class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua siklus untuk dropdown
        $semuaSiklus = Siklus::orderBy('created_at', 'desc')->get();

        // Tentukan siklus aktif
        $siklusAktif = $this->tentukanSiklusAktif($request, $semuaSiklus);

        // Ambil data produksi berdasarkan siklus aktif
        $dataProduksi = $this->ambilDataProduksi($siklusAktif);

        return view('produksi', array_merge([
            'semuaSiklus' => $semuaSiklus,
            'siklusAktif' => $siklusAktif
        ], $dataProduksi));
    }

    protected function tentukanSiklusAktif(Request $request, $semuaSiklus)
    {
        // Prioritas 1: Parameter request siklus_id
        if ($request->has('siklus_id')) {
            $siklus = Siklus::find($request->siklus_id);
            if ($siklus) {
                $request->session()->put('siklus_aktif', $siklus->id);
                return $siklus;
            }
        }

        // Prioritas 2: Session siklus_aktif
        if ($request->session()->has('siklus_aktif')) {
            $siklus = Siklus::find($request->session()->get('siklus_aktif'));
            if ($siklus) {
                return $siklus;
            }
        }

        // Prioritas 3: Siklus terbaru
        $siklusTerbaru = $semuaSiklus->first();

        // Jika tidak ada siklus sama sekali, buat default
        if (!$siklusTerbaru) {
            $siklusTerbaru = Siklus::create(['nama_siklus' => 'Siklus 1']);
            $request->session()->put('siklus_aktif', $siklusTerbaru->id);
        }

        return $siklusTerbaru;
    }

    protected function ambilDataProduksi($siklusAktif)
    {
        if (!$siklusAktif) {
            return [
                'dataBibit' => collect(),
                'dataPakan' => collect(),
                'dataPanen' => collect()
            ];
        }

        return [
            'dataBibit' => $siklusAktif->bibits()->latest()->get(),
            'dataPakan' => $siklusAktif->pakans()->latest()->get(),
            'dataPanen' => $siklusAktif->panens()->latest()->get()
        ];
    }
}
