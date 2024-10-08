<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Kuisioner extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function kuisioner()
    {
        return $this->belongsTo(kuisioner::class);
    }

    // Relasi dengan model Answer
    public function answers()
    {
        return $this->hasMany(AnswerKuisioner::class, 'kuisioners_id');
    }
}
