<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bibit;
use App\Models\Pakan;
use App\Models\Panen;
use App\Models\Siklus;

class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua siklus untuk dropdown
        $siklusList = Siklus::orderBy('created_at', 'desc')->get();

        // Ambil siklus aktif dari request atau default ke yang terbaru
        $siklusAktif = $request->siklus_id
            ? Siklus::find($request->siklus_id)
            : $siklusList->first();

        // Jika tidak ada siklus sama sekali, buat default
        if (!$siklusAktif && $siklusList->isEmpty()) {
            $siklusAktif = Siklus::create(['nama_siklus' => 'Siklus 1']);
            $siklusList = Siklus::all();
        }

        // Ambil data berdasarkan siklus aktif
        $dataBibit = $siklusAktif ? $siklusAktif->bibits()->latest()->get() : collect();
        $dataPakan = $siklusAktif ? $siklusAktif->pakans()->latest()->get() : collect();
        $dataPanen = $siklusAktif ? $siklusAktif->panens()->latest()->get() : collect();

        return view('produksi', [
            'dataBibit' => $dataBibit,
            'dataPakan' => $dataPakan,
            'dataPanen' => $dataPanen,
            'siklusList' => $siklusList,
            'siklusAktif' => $siklusAktif
        ]);
    }
}
