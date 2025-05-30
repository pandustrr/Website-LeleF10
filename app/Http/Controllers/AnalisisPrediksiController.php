<?php

namespace App\Http\Controllers;

use App\Models\Panen;
use App\Models\Pakan;
use App\AI\FuzzyService;

class AnalisisPrediksiController extends Controller
{
    protected $fuzzyService;

    public function __construct(FuzzyService $fuzzyService)
    {
        $this->fuzzyService = $fuzzyService;
    }

    public function index()
    {
        try {
            // Ambil data historis untuk semua siklus
            $historisData = $this->getHistorisData();

            // Hitung total dan rata-rata
            $totalPakan = array_sum($historisData['pakan']);
            $totalPanen = array_sum($historisData['panen']);
            $averageFcr = count($historisData['fcr']) > 0
                ? array_sum($historisData['fcr']) / count($historisData['fcr'])
                : 0;

            // Analisis menggunakan fuzzy logic dengan FCR rata-rata
            $analysis = $this->fuzzyService->analyze(0, $averageFcr);
            $currentFcr = count($historisData['fcr']) > 0 ? end($historisData['fcr']) : 0;

            // Hitung jumlah data
            $jumlahDataPakan = count($historisData['pakan']);
            $jumlahDataPanen = count(array_filter($historisData['panen'], function ($value) {
                return $value > 0;
            }));

            return view('analisisPrediksi', [
                'analysis' => $analysis,
                'historisData' => $historisData,
                'totalPakan' => $totalPakan,
                'totalPanen' => $totalPanen,
                'totalPakanKuantitas' => $totalPakan,
                'totalPanenKuantitas' => $totalPanen,
                'jumlahDataPakan' => $jumlahDataPakan,
                'jumlahDataPanen' => $jumlahDataPanen,
                'currentFcr' => $currentFcr,

                'statusColors' => [
                    'baik' => 'bg-green-100 text-green-800',
                    'sedang' => 'bg-yellow-100 text-yellow-800',
                    'buruk' => 'bg-red-100 text-red-800',
                    'unknown' => 'bg-gray-100 text-gray-800',
                    'tidak-ada-panen' => 'bg-gray-100 text-gray-800'
                ],
                'fcrStandar' => [
                    'baik' => 1.0,
                    'sedang' => 1.5,
                    'buruk' => 2.0
                ],
                'averageFcr' => $averageFcr
            ]);
        } catch (\Exception $e) {
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

// Ganti bagian getHistorisData() dengan ini:
protected function getHistorisData()
{
    // Ambil semua data panen dan pakan dari semua siklus dengan join ke tabel siklus
    $panenData = Panen::with('siklus')
        ->orderBy('tanggal')
        ->get();
    $pakanData = Pakan::with('siklus')
        ->orderBy('tanggal')
        ->get();

    // Kelompokkan data per siklus
    $siklusGroups = [];

    // Proses data pakan per siklus
    foreach ($pakanData as $pakan) {
        $siklusId = $pakan->siklus_id;
        if (!isset($siklusGroups[$siklusId])) {
            $siklusGroups[$siklusId] = [
                'nama_siklus' => $pakan->siklus->nama_siklus ?? 'Siklus Tidak Diketahui',
                'total_pakan' => 0,
                'total_panen' => 0,
                'tanggal_mulai' => $pakan->tanggal,
                'tanggal_akhir' => $pakan->tanggal
            ];
        }
        $siklusGroups[$siklusId]['total_pakan'] += $pakan->kuantitas;
        $siklusGroups[$siklusId]['tanggal_akhir'] = max($siklusGroups[$siklusId]['tanggal_akhir'], $pakan->tanggal);
    }

    // Proses data panen per siklus
    foreach ($panenData as $panen) {
        $siklusId = $panen->siklus_id;
        if (!isset($siklusGroups[$siklusId])) {
            $siklusGroups[$siklusId] = [
                'nama_siklus' => $panen->siklus->nama_siklus ?? 'Siklus Tidak Diketahui',
                'total_pakan' => 0,
                'total_panen' => 0,
                'tanggal_mulai' => $panen->tanggal,
                'tanggal_akhir' => $panen->tanggal
            ];
        }
        $siklusGroups[$siklusId]['total_panen'] += $panen->kuantitas;
        $siklusGroups[$siklusId]['tanggal_akhir'] = max($siklusGroups[$siklusId]['tanggal_akhir'], $panen->tanggal);
    }

    // Siapkan data untuk chart
    $labels = [];
    $fcrValues = [];
    $pakanValues = [];
    $panenValues = [];
    $siklusNames = [];
    $siklusIds = [];
    $dataDetails = [];

    foreach ($siklusGroups as $siklusId => $group) {
        $labels[] = $group['nama_siklus'] . ' (' . $group['tanggal_mulai'] . ' - ' . $group['tanggal_akhir'] . ')';
        $pakanValues[] = $group['total_pakan'];
        $panenValues[] = $group['total_panen'];
        $siklusNames[] = $group['nama_siklus'];
        $siklusIds[] = $siklusId;

        // Hitung FCR per siklus
        $fcr = $group['total_panen'] > 0 ? $group['total_pakan'] / $group['total_panen'] : 0;
        $fcrValues[] = $fcr;

        $dataDetails[] = [
            'siklus_id' => $siklusId,
            'siklus_name' => $group['nama_siklus'],
            'pakan' => $group['total_pakan'],
            'panen' => $group['total_panen'],
            'fcr' => $fcr,
            'tanggal_mulai' => $group['tanggal_mulai'],
            'tanggal_akhir' => $group['tanggal_akhir']
        ];
    }

    // Hitung total keseluruhan
    $totalPakan = array_sum($pakanValues);
    $totalPanen = array_sum($panenValues);
    $averageFcr = $totalPanen > 0 ? $totalPakan / $totalPanen : 0;

    return [
        'labels' => $labels,
        'fcr' => $fcrValues,
        'pakan' => $pakanValues,
        'panen' => $panenValues,
        'siklus_names' => $siklusNames,
        'siklus_ids' => $siklusIds,
        'data_details' => $dataDetails,
        'total_pakan' => $totalPakan,
        'total_panen' => $totalPanen,
        'average_fcr' => $averageFcr,
        'siklus_groups' => $siklusGroups
    ];
}
}
