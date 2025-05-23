<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siklus;
use App\AI\ARIMAService;
use Illuminate\Support\Facades\Log;

class KeuanganController extends Controller
{
    protected $arimaService;

    public function __construct(ARIMAService $arimaService)
    {
        $this->arimaService = $arimaService;
    }

    public function index(Request $request)
    {
        try {
            $siklusList = Siklus::with(['bibits', 'pakans', 'panens'])->get();
            $siklusAktif = $request->siklus_id
                ? Siklus::with(['bibits', 'pakans', 'panens'])->find($request->siklus_id)
                : $siklusList->first();

            if (!$siklusAktif) {
                throw new \Exception("No active cycle found");
            }

            $panens = $siklusAktif->panens ?? collect();
            $selectedPanen = $request->panen_id
                ? $panens->firstWhere('id', $request->panen_id)
                : $panens->first();

            $transaksi = $siklusAktif->getDailyTransactions() ?? collect();

            return view('keuangan', [
                'siklusList' => $siklusList,
                'siklusAktif' => $siklusAktif,
                'transaksi' => $transaksi,
                'panens' => $panens,
                'selectedPanen' => $selectedPanen,
                'totalPengeluaran' => $siklusAktif->total_pengeluaran ?? 0,
                'totalPemasukan' => $selectedPanen->total_harga ?? 0,
                'predictions' => $this->getPredictions($siklusAktif->id)
            ]);

        } catch (\Exception $e) {
            Log::error('KeuanganController Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors('Error loading financial data: '.$e->getMessage());
        }
    }

// Dalam KeuanganController.php
protected function getPredictions($siklusId)
{
    try {
        $predictions = $this->arimaService->predict($siklusId);

        // Validasi data sebelum dikirim ke view
        if (isset($predictions['error'])) {
            return $predictions;
        }

        // Pastikan semua field yang diperlukan ada
        $requiredFields = ['dates', 'future_dates', 'pengeluaran', 'pemasukan', 'profit'];
        foreach ($requiredFields as $field) {
            if (!isset($predictions[$field])) {
                throw new \Exception("Field $field tidak ada dalam response prediksi");
            }
        }

        // Format data untuk chart
        $predictions['dates'] = array_map(function($date) {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('d/m/Y');
        }, $predictions['dates'] ?? []);

        $predictions['future_dates'] = array_map(function($date) {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('d/m/Y');
        }, $predictions['future_dates'] ?? []);

        return $predictions;

    } catch (\Exception $e) {
        Log::error('Error memproses prediksi: '.$e->getMessage());
        return ['error' => 'Gagal memproses data prediksi'];
    }
}

}
