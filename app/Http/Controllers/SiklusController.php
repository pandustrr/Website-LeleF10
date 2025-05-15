<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siklus;

class SiklusController extends Controller
{
    // Menyimpan siklus baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_siklus' => 'required|string|max:255|unique:siklus',
        ]);

        $siklus = Siklus::create($request->only('nama_siklus'));

        return redirect()->route('produksi', ['siklus_id' => $siklus->id])
            ->with('success', 'Siklus baru berhasil dibuat!');
    }

    // Menghapus siklus
    public function destroy($id)
    {
        $siklus = Siklus::findOrFail($id);
        $siklus->delete();

        return redirect()->route('produksi')
            ->with('success', 'Siklus berhasil dihapus!');
    }
}
