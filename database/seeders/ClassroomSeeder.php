<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = [
            [
                'title' => 'Mathematics 101',
                'description' => 'Introduction to basic mathematics concepts',
                'teacher_id' => 1,
            ],
            [
                'title' => 'Physics Fundamentals',
                'description' => 'Basic principles of physics',
                'teacher_id' => 1,
            ],
            [
                'title' => 'Chemistry Lab',
                'description' => 'Hands-on chemistry experiments',
                'teacher_id' => 1,
            ],
            [
                'title' => 'History of World',
                'description' => 'Exploring world history',
                'teacher_id' => 1,
            ],
            [
                'title' => 'Computer Science Basics',
                'description' => 'Introduction to programming and algorithms',
                'teacher_id' => 1,
            ],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::create($classroom);
        }
    }
}
