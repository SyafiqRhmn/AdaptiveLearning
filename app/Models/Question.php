<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function testable()
    {
        return $this->morphTo();
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($question) {
            $question->answers()->delete();
        });
    }
}
