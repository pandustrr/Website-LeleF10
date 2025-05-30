<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pakan;

class PakanController extends Controller
{

    public function create()
    {
        return view('components.produksi.modals.pakan-edit');
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kuantitas' => 'required|integer|min:1',
            'tipe' => 'required|string|in:' . implode(',', array_keys(Pakan::HARGA_PAKAN)),
            'siklus_id' => 'required|exists:siklus,id'
        ]);

        Pakan::create($request->all());

        return redirect()->route('produksi', ['siklus_id' => $request->siklus_id])
            ->with('success', 'Data pakan berhasil disimpan!');
    }


    // Hapus data
    public function destroy($id)
    {
        $pakan = Pakan::findOrFail($id);
        $pakan->delete();

        return redirect()->route('produksi')
            ->with('success', 'Data pakan berhasil dihapus!');
    }

    // Edit data
    public function edit($id)
    {
        $pakan = Pakan::findOrFail($id);
        return view('components.produksi.modals.pakan-edit', compact('pakan'));
    }

    // Update data
public function update(Request $request, $id)
{
    $request->validate([
        'tanggal' => 'required|date',
        'kuantitas' => 'required|integer|min:1',
        'tipe' => 'required|string|in:' . implode(',', array_keys(Pakan::HARGA_PAKAN)),
        'siklus_id' => 'required|exists:siklus,id'
    ]);

    $pakan = Pakan::findOrFail($id);
    $updated = $pakan->update($request->all());

    if (!$updated) {
        return back()->with('error', 'Gagal memperbarui data pakan');
    }

    return redirect()->route('produksi', ['siklus_id' => $request->siklus_id])
        ->with('success', 'Data pakan berhasil diperbarui!');
}
}
