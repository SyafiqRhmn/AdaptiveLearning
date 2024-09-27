<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreTest extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function questions()
    {
        return $this->morphMany(Question::class, 'testable');
    }

    public function hasil_test_pelajars()
    {
        return $this->morphMany(HasilTestPelajar::class, 'testable');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($questions) {
            $questions->questions()->delete();
        });
    }
}
