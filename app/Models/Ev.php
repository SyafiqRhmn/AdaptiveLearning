<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ev extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'ev';
    protected $fillable = ['kuisioners_id', 'kriteria', 'ev_value'];

    public function quesionerGuru()
    {
        return $this->belongsTo(QuesionerGuru::class);
    }
    public function answers()
    {
        return $this->hasMany(AnswerKuisioner::class, 'kuisioners_id', 'kuisioners_id');
    }
}

