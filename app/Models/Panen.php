<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panen extends Model
{
    use HasFactory;

    protected $table = 'panens';
    protected $fillable = ['tanggal', 'kuantitas', 'siklus_id', 'harga_jual'];
    protected $dates = ['tanggal'];

    public function getTotalHargaAttribute()
    {
        return $this->harga_jual ? $this->kuantitas * $this->harga_jual : 0;
    }

    public function siklus()
    {
        return $this->belongsTo(Siklus::class);
    }
}
