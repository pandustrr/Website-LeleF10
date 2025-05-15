<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panen extends Model
{
    use HasFactory;

    protected $table = 'panens';
    protected $fillable = ['tanggal', 'kuantitas', 'siklus_id'];
    protected $dates = ['tanggal'];
}
