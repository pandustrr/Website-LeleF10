<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pakan extends Model
{
    use HasFactory;

    protected $fillable = ['tanggal', 'kuantitas', 'tipe', 'siklus_id'];
    protected $dates = ['tanggal'];

    // Daftar harga tetap per tipe pakan (dalam rupiah per kg)
    public const HARGA_PAKAN = [
        'standar' => 15000,
        'premium' => 18000,
    ];

    // Relasi ke siklus
    public function siklus()
    {
        return $this->belongsTo(Siklus::class);
    }

    // Accessor untuk menghitung total harga
    public function getTotalHargaAttribute()
    {
        return $this->kuantitas * self::HARGA_PAKAN[$this->tipe];
    }

    // Accessor untuk mendapatkan harga satuan
    public function getHargaSatuanAttribute()
    {
        return self::HARGA_PAKAN[$this->tipe];
    }
}
