<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\PreTest;
use App\Models\PostTest;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        

        // user seeder
        User::factory()->create([
            'name' => 'admin',
            'nim' => '0',
            'email' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin'
        ]);
        User::factory()->create([
            'name' => 'Guru 1',
            'nim' => '19560701 198710 1001',
            'email' => 'g1@gmail.com',
            'password' => bcrypt('Guru1'),
            'role' => 'g1'
        ]);
        User::factory()->create([
            'name' => 'Guru 2',
            'nim' => '19560701 198710 1001',
            'email' => 'g2@gmail.com',
            'password' => bcrypt('Guru1'),
            'role' => 'g2'
        ]);
        User::factory()->create(['name' => 'Pelajar 1','nim' => '180411100001','email' => 'p1@gmail.com','password' => bcrypt('p1'),'role' => 'pelajar']);
        User::factory()->create(['name' => 'Pelajar 2','nim' => '180411100002','email' => 'p2@gmail.com','password' => bcrypt('p2'),'role' => 'pelajar']);
        User::factory()->create(['name' => 'Pelajar 3','nim' => '180411100003','email' => 'p3@gmail.com','password' => bcrypt('p3'),'role' => 'pelajar']);
        User::factory()->create(['name' => 'Pelajar 4','nim' => '180411100004','email' => 'p4@gmail.com','password' => bcrypt('p4'),'role' => 'pelajar']);
        User::factory()->create(['name' => 'Pelajar 5','nim' => '180411100005','email' => 'p5@gmail.com','password' => bcrypt('p5'),'role' => 'pelajar']);
        
        // classroom seeder
        Classroom::factory()->create(['name' => 'Struktur Data']);

        // subject seeder
        Subject::factory()->create(['name' => 'Review Algoritma', 'classroom_id' => 1]);
        Subject::factory()->create(['name' => 'Array dan String', 'classroom_id' => 1]);
        Subject::factory()->create(['name' => 'Stack dan Queue', 'classroom_id' => 1]);
        Subject::factory()->create(['name' => 'Function', 'classroom_id' => 1]);
        Subject::factory()->create(['name' => 'Hashing', 'classroom_id' => 1]);
        Subject::factory()->create(['name' => 'Sorting', 'classroom_id' => 1]);
        Subject::factory()->create(['name' => 'Searching', 'classroom_id' => 1]);
        Subject::factory()->create(['name' => 'Linked List', 'classroom_id' => 1]);

        // pre-test seeder
        PreTest::factory()->create(['name' => 'Pre Test Struktur Data', 'classroom_id' => 1]);
        
        // post-test seeder
        PostTest::factory()->create(['name' => 'Post Test Struktur Data', 'classroom_id' => 1]);
        
    }
}