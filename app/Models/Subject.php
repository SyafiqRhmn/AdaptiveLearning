<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $enumStatusModul = ['terbuka', 'terkunci'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function jalurPembelajaran()
    {
    return $this->hasMany(JalurPembelajaran::class, 'subject_id');
    }

}
