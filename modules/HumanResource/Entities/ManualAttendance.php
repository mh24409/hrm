<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\HumanResource\Entities\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManualAttendance extends Model
{
    use HasFactory;

    // Explicitly define the table name
    protected $table = 'attendances';

    protected $fillable = [
        'employee_id',
        'time',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
