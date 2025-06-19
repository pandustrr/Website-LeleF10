<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bibit extends Model
{
    use HasFactory;

    protected $fillable = ['tanggal', 'kuantitas', 'type', 'siklus_id'];
    protected $dates = ['tanggal'];

    public const HARGA_BIBIT = [
        'Bibit Standar' => 43000,
        'Bibit Premium' => 65000,
    ];

    public function siklus()
    {
        return $this->belongsTo(Siklus::class);
    }

    // Accessor untuk menghitung total harga
    public function getTotalHargaAttribute()
    {
        return $this->kuantitas * self::HARGA_BIBIT[$this->type];
    }

    // Accessor untuk mendapatkan harga satuan
    public function getHargaSatuanAttribute()
    {
        return self::HARGA_BIBIT[$this->type];
    }
}
