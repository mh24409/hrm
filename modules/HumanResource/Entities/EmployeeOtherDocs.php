<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class EmployeeOtherDocs extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'document_title',
        'document_attachment',
        'document_expire',
        'is_notify',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

}
