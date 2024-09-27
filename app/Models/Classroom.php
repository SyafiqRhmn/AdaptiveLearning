<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }

    public function preTests()
    {
    return $this->hasMany(PreTest::class);
    }
    
    public function postTests()
    {
        return $this->hasMany(PostTest::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($classroom) {
            $classroom->subjects->each(function ($subject) {
                if ($subject->path !== null) {
                    Storage::delete($subject->path);
                }
                $subject->delete();
            });
        });

        static::deleting(function ($classroom) {

            // memasukkan id testable sebagai identifier untuk penghapusan di tabel selanjutnya
            $preTestIds = $classroom->preTests->pluck('id')->toArray();
            $postTestIds = $classroom->postTests->pluck('id')->toArray();
        
            // Menghapus pertanyaan (questions) yang memiliki testable_type pre-test dan testable_id pre-test tertentu
            Question::whereIn('testable_type', ['pre-test'])
                ->whereIn('testable_id', $preTestIds)
                ->each(function ($question) {
                    $question->answers()->delete();
                    $question->delete();
                });
        
            // Menghapus pertanyaan (questions) yang memiliki testable_type post-test dan testable_id post-test tertentu
            Question::whereIn('testable_type', ['post-test'])
                ->whereIn('testable_id', $postTestIds)
                ->each(function ($question) {
                    $question->answers()->delete();
                    $question->delete();
                });
        
            // Menghapus PreTest
            $classroom->preTests()->delete();
        
            // Menghapus PostTest
            $classroom->postTests()->delete();
        });        
    }
}
