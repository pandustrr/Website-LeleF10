<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siklus;
use Illuminate\Support\Facades\DB;

class SiklusController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_siklus' => 'required|string|max:255|unique:siklus,nama_siklus',
        ]);

        DB::beginTransaction();

        try {
            $siklus = Siklus::create([
                'nama_siklus' => $request->nama_siklus
            ]);

            DB::commit();

            return redirect()->route('produksi', ['siklus_id' => $siklus->id])
                ->with('success', 'Siklus baru berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal membuat siklus baru: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_siklus' => 'required|string|max:255|unique:siklus,nama_siklus,'.$id,
        ]);

        $siklus = Siklus::findOrFail($id);
        $siklus->update(['nama_siklus' => $request->nama_siklus]);

        return redirect()->route('produksi', ['siklus_id' => $siklus->id])
            ->with('success', 'Nama siklus berhasil diperbarui!');
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $siklus = Siklus::findOrFail($id);
            $siklusLain = Siklus::where('id', '!=', $id)->first();

            $siklus->delete();

            DB::commit();

            return redirect()->route('produksi', ['siklus_id' => $siklusLain?->id])
                ->with('success', 'Siklus berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus siklus: ' . $e->getMessage());
        }
    }
}
