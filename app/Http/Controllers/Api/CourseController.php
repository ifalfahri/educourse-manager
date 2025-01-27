<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        // Add search filter
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Add status filter  
        if ($request->has('status')) {
            $query->where('status', $request->status === 'true');
        }

        // Add price range filter
        if ($request->price_range) {
            $range = explode('-', $request->price_range);
            $query->whereBetween('price', [$range[0], $range[1]]);
        }

        // Add sorting
        $sortField = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        return response()->json([
            'data' => $query->paginate(10)
        ]);
    }

    public function show(Course $course)
    {
        return response()->json([
            'data' => $course->load('students')
        ]);
    }
}