<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panen;

class PanenController extends Controller
{
    public function create()
    {
        return view('components.produksi.modals.panen-edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kuantitas' => 'required|integer|min:1',
            'siklus_id' => 'required|exists:siklus,id'

        ]);

        Panen::create($request->all());

        return redirect()->route('produksi', ['siklus_id' => $request->siklus_id])
            ->with('success', 'Data panen berhasil disimpan!');
    }

    public function destroy($id)
    {
        $panen = Panen::findOrFail($id);
        $panen->delete();

        return redirect()->route('produksi')
            ->with('success', 'Data panen berhasil dihapus!');
    }

    public function edit($id)
    {
        $panen = Panen::findOrFail($id);
        return view('components.produksi.modals.panen-edit', compact('panen'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kuantitas' => 'required|integer|min:1',
        ]);

        $panen = Panen::findOrFail($id);
        $panen->update($request->all());

        // Kembalikan response sesuai kebutuhan (AJAX atau bukan)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data panen berhasil diperbarui',
                'redirect' => route('produksi')
            ]);
        }

        return redirect()->route('produksi')
            ->with('success', 'Data panen berhasil diperbarui');
    }

public function simulasi(Request $request)
{
    $request->validate([
        'harga_jual' => 'required|numeric|min:1000',
        'siklus_id' => 'required|exists:siklus,id',
        'panen_id' => 'required|exists:panens,id'
    ]);

    $panen = Panen::findOrFail($request->panen_id);
    $panen->update(['harga_jual' => $request->harga_jual]);

    return redirect()
        ->route('keuangan', [
            'siklus_id' => $request->siklus_id,
            'panen_id' => $request->panen_id
        ])
        ->with('success', 'Harga jual panen berhasil diperbarui!');
}
}
