<?php

namespace App\Http\Controllers;

use App\Models\Bibit;
use App\Models\Pakan;
use App\Models\Panen;

class DashboardController extends Controller
{
    public function index()
    {
        $panens = Panen::latest()->get();
        $totalBibit = Bibit::sum('kuantitas');
        $totalPakan = Pakan::sum('kuantitas');
        $totalPanen = Panen::sum('kuantitas');

        return view('dashboard', compact(
            'panens',
            'totalBibit',
            'totalPakan',
            'totalPanen'
        ));
    }
}
