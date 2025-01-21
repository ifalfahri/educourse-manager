<?php

namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CoursesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Course::all()->map(function ($course) {
            return [
                'name' => $course->name,
                'description' => $course->description,
                'price' => $course->price,
                'status' => $course->status ? 'Active' : 'Inactive',
                'students_count' => $course->students_count
            ];
        });
      }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Price',
            'Status',
            'Students Count'
        ];
    }
}