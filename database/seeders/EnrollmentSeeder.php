<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all students and courses
        $students = User::where('role', 'student')->get();
        $courses = Course::all();

        // Create random enrollments
        foreach ($students as $student) {
            // Enroll each student in 1-3 random courses
            $numberOfCourses = rand(1, 3);
            $randomCourses = $courses->random($numberOfCourses);

            foreach ($randomCourses as $course) {
                Enrollment::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'payment_status' => rand(0, 1) ? 'paid' : 'unpaid'
                ]);

                // Update the enrolled students count
                $course->increment('students_count');
            }
        }
    }
}