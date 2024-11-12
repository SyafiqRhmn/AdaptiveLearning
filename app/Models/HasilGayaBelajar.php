<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilGayaBelajar extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'hasil_gaya_belajars';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}

