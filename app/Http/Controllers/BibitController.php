<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bibit;
use App\Models\Siklus;

class BibitController extends Controller
{
    // Menampilkan form input
    public function create()
    {
        return view('components.produksi.modals.bibit-edit');
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kuantitas' => 'required|integer|min:1',
            'type' => 'required|string|max:255',
            'siklus_id' => 'required|exists:siklus,id'
        ]);

        Bibit::create($request->all());

        return redirect()->route('produksi', ['siklus_id' => $request->siklus_id])
            ->with('success', 'Data bibit berhasil disimpan!');
    }

    // Hapus data
    public function destroy($id)
    {
        $bibit = Bibit::findOrFail($id);
        $bibit->delete();

        return redirect()->route('produksi')
            ->with('success', 'Data bibit berhasil dihapus!');
    }

    // Edit

    public function edit($id)
    {
        $bibit = Bibit::findOrFail($id);
        return view('components.produksi.modals.bibit-edit', compact('bibit'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kuantitas' => 'required|integer|min:1',
            'type' => 'required|string|in:Bibit Premium,Bibit Standar',
        ]);

        $bibit = Bibit::findOrFail($id);
        $bibit->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data bibit berhasil diperbarui',
                'redirect' => route('produksi')
            ]);
        }

        return redirect()->route('produksi')
            ->with('success', 'Data bibit berhasil diperbarui!');
    }
}
