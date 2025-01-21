<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'students_count'
    ];

    protected $casts = [
        'price' => 'integer',
        'status' => 'boolean',
        'students_count' => 'integer'
    ];
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }
}

