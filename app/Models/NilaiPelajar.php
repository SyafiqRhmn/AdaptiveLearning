<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiPelajar extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
