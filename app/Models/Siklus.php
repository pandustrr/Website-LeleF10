<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siklus extends Model
{
    use HasFactory;

    protected $table = 'siklus';
    protected $fillable = ['nama_siklus'];

    public function bibits()
    {
        return $this->hasMany(Bibit::class);
    }

    public function pakans()
    {
        return $this->hasMany(Pakan::class);
    }

    public function panens()
    {
        return $this->hasMany(Panen::class);
    }

    public function getTotalPengeluaranAttribute()
    {
        return $this->bibits->sum('total_harga') + $this->pakans->sum('total_harga');
    }

    public function getTotalPemasukanAttribute()
    {
        return $this->panens->sum('total_harga');
    }

    public function getLabaAttribute()
    {
        return $this->total_pemasukan - $this->total_pengeluaran;
    }

    public function getDailyTransactions()
    {
        $transactions = collect();

        // Gabungkan semua transaksi dengan tipe yang benar
        $transactions = $transactions->merge(
            $this->bibits->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'kategori' => 'Bibit',
                    'tipe' => $item->type,
                    'kuantitas' => $item->kuantitas,
                    'harga' => $item->total_harga,
                    'jenis' => 'pengeluaran'
                ];
            })
        )->merge(
            $this->pakans->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'kategori' => 'Pakan',
                    'tipe' => $item->tipe,
                    'kuantitas' => $item->kuantitas,
                    'harga' => $item->total_harga,
                    'jenis' => 'pengeluaran'
                ];
            })
        )->merge(
            $this->panens->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'kategori' => 'Panen',
                    'tipe' => '-',
                    'kuantitas' => $item->kuantitas,
                    'harga' => $item->total_harga,
                    'jenis' => 'pemasukan'
                ];
            })
        );

        return $transactions->sortBy('tanggal');
    }
}
