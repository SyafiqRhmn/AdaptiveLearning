<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuesionerPelajar extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // Tentukan nama tabel jika berbeda dengan default Laravel
    protected $table = 'kuisioners'; // Sesuaikan dengan nama tabel yang ada di database
    protected $primaryKey = 'id'; // Pastikan primary key yang digunakan benar

    public function quesioner()
    {
        return $this->belongsTo(kuisioner::class);
    }

}
