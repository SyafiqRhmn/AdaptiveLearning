<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnswerKuisioner extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'answers_kuisioners'; // Nama tabel sesuai dengan yang ada di database
    protected $fillable = [
        'kuisioners_id', // ID dari QuesionerPelajar
        'value', // Nilai jawaban
        'user_id'
    ];

    public function QuesionerPelajar()
    {
        return $this->belongsTo(Kuisioner::class, 'id');
    }
    public function ev()
    {
        return $this->belongsTo(Ev::class, 'kuisioners_id', 'kuisioners_id');
    }
}
