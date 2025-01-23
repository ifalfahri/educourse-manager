<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CoursesExport;
use App\Imports\CoursesImport;
use Barryvdh\DomPDF\Facade\Pdf;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Course::query();

        // Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter
        if ($request->status !== null) {
            $query->where('status', $request->status === 'true');
        }

        if ($request->price_range) {
            $range = explode('-', $request->price_range);
            $query->whereBetween('price', [$range[0], $range[1]]);
        }

        // Sort
        $sortField = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        return Inertia::render('Courses/Index', [
            'courses' => $query->paginate(5),
            'filters' => $request->all(['search', 'status', 'price_range', 'sort_by', 'sort_direction'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Courses/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'boolean'
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')
            ->with('message', 'Course created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return Inertia::render('Courses/Edit', [
            'course' => $course
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'boolean'
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')
            ->with('message', 'Course updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')
            ->with('message', 'Course deleted successfully');
    }

    public function export()
    {
        $fileName = 'courses_'.time().'.xlsx';
        Excel::store(new CoursesExport, $fileName, 'public');
        
        return response()->json([
            'file' => asset('storage/'.$fileName)
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new CoursesImport, $request->file('file'));

        return redirect()->route('courses.index')
            ->with('message', 'Courses imported successfully');
    }

    public function printPdf()
    {
        $courses = Course::all();
    
    $pdf = PDF::loadView('pdf.courses', [
        'courses' => $courses
    ]);
    
    return $pdf->download('courses.pdf');
    }
}
