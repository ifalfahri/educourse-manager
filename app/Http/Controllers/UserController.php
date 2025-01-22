<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function students()
    {
        $students = User::where('role', 'student')
            ->withCount('enrollments')
            ->with(['enrollments' => function($query) {
                $query->with('course');
            }])
            ->get();

        return Inertia::render('Students/Index', [
            'students' => $students
        ]);
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'student', 404);

        return Inertia::render('Students/Show', [
            'student' => $user->load(['enrollments.course'])
                ->loadCount('enrollments')
        ]);
    }
}