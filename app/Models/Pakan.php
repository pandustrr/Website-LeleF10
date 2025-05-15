<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pakan extends Model
{
    use HasFactory;

    protected $fillable = ['tanggal', 'kuantitas', 'tipe', 'siklus_id'];
    protected $dates = ['tanggal'];
}
