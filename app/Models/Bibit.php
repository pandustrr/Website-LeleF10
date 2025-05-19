<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bibit extends Model
{
    use HasFactory;

    // Tambahkan kolom harga dan total ke fillable
    protected $fillable = [
        'tanggal',
        'kuantitas',
        'type',
        'siklus_id'
    ];

    protected $dates = ['tanggal'];
}
