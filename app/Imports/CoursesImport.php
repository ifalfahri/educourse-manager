<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoursesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $status = strtolower($row['status'] ?? 'active');
        $isActive = $status === 'active';

        return new Course([
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'status' => $isActive,
        ]);
    }
}