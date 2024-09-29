<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuisioner extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function kuisioner()
    {
        return $this->belongsTo(kuisioner::class);
    }

}
