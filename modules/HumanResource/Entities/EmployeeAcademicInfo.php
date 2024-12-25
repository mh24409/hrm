<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeAcademicInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'exam_title',
        'institute_name',
        'result',
        'graduation_year',
        'academic_attachment',
    ];
    
}
