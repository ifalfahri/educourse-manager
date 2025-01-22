<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Introduction to Programming',
                'description' => 'Learn the basics of programming with this comprehensive course.',
                'price' => 1500000,
                'status' => true,
            ],
            [
                'name' => 'Web Development Fundamentals',
                'description' => 'Master HTML, CSS, and JavaScript basics.',
                'price' => 2000000,
                'status' => true,
            ],
            [
                'name' => 'Database Design',
                'description' => 'Learn how to design and implement efficient databases.',
                'price' => 1800000,
                'status' => true,
            ],
            [
                'name' => 'Advanced Python Programming',
                'description' => 'Take your Python skills to the next level.',
                'price' => 2500000,
                'status' => true,
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Create mobile applications for iOS and Android.',
                'price' => 3000000,
                'status' => true,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}