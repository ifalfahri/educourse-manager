<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        $stats = [
            'totalCourses' => \App\Models\Course::count(),
            'activeCourses' => \App\Models\Course::where('status', true)->count(),
            'totalStudents' => \App\Models\User::where('role', 'student')->count(),
            'totalEnrollments' => \App\Models\Enrollment::count(),
        ];

        $recentEnrollments = \App\Models\Enrollment::with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    } else {
        $stats = [
            'totalCourses' => \App\Models\Course::where('status', true)->count(),
            'myCourses' => \App\Models\Enrollment::where('user_id', $user->id)->count(),
            'paidCourses' => \App\Models\Enrollment::where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->count(),
            'unpaidCourses' => \App\Models\Enrollment::where('user_id', $user->id)
                ->where('payment_status', 'unpaid')
                ->count(),
        ];

        $recentEnrollments = \App\Models\Enrollment::with(['course'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    return Inertia::render('Dashboard', [
        'stats' => $stats,
        'recentEnrollments' => $recentEnrollments,
        'isAdmin' => $user->role === 'admin'
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('courses/export', [CourseController::class, 'export'])->name('courses.export');
    Route::post('courses/import', [CourseController::class, 'import'])->name('courses.import');
    Route::get('courses/print', [CourseController::class, 'printPdf'])->name('courses.print');
    Route::get('/students', [UserController::class, 'students'])->name('students.index');
    Route::get('/students/{user}', [UserController::class, 'show'])->name('students.show');
    Route::resource('courses', CourseController::class);
    Route::resource('enrollments', EnrollmentController::class);
});

require __DIR__.'/auth.php';
