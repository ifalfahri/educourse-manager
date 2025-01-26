<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller 
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['user', 'course']);

        // Add filters
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id); 
        }

        return response()->json([
            'data' => $query->paginate(10)
        ]);
    }

    public function show(Enrollment $enrollment)
    {
        return response()->json([
            'data' => $enrollment->load(['user', 'course'])
        ]);
    }
}