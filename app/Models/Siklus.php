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

    // Menghitung total pengeluaran (bibit + pakan)
    public function getTotalPengeluaranAttribute()
    {
        return $this->bibits->sum('total_harga') + $this->pakans->sum('total_harga');
    }

    // Menghitung total pemasukan (panen)
    public function getTotalPemasukanAttribute()
    {
        return $this->panens->sum('total_harga');
    }

    // Menghitung laba/rugi
    public function getLabaAttribute()
    {
        return $this->total_pemasukan - $this->total_pengeluaran;
    }
}
