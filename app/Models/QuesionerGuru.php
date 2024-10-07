<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuesionerGuru extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // Tentukan nama tabel jika berbeda dengan default Laravel
    protected $table = 'kuisioners'; // Sesuaikan dengan nama tabel yang ada di database

    public function quesioner()
    {
        return $this->belongsTo(kuisioner::class);
    }

    public function ev()
    {
        return $this->hasMany(Ev::class);
    }
}
