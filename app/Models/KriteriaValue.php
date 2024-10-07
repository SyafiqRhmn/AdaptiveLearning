<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KriteriaValue extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // Tentukan nama tabel jika berbeda dengan default Laravel
    protected $table = 'kuisioners_values'; // Sesuaikan dengan nama tabel yang ada di database

    public function kriteriavalue()
    {
        return $this->belongsTo(kuisioners_values::class);
    }

}
