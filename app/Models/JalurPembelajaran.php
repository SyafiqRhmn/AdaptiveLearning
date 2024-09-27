<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JalurPembelajaran extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $enumStatus = ['terbuka', 'terkunci'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
