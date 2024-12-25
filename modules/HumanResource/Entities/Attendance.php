<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'employee_id',
        'machine_id',
        'machine_state',
        'time',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
