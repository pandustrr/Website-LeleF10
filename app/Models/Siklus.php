<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siklus extends Model
{
    use HasFactory;

    protected $table = 'siklus';
    protected $fillable = ['nama_siklus'];

    // Relasi ke Bibit
    public function bibits()
    {
        return $this->hasMany(Bibit::class);
    }

    // Relasi ke Pakan
    public function pakans()
    {
        return $this->hasMany(Pakan::class);
    }

    // Relasi ke Panen
    public function panens()
    {
        return $this->hasMany(Panen::class);
    }
}
