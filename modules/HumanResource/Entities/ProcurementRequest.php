<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcurementRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial',
        'date', 
        'department_id', 
        'employee_id',
        'request_reason',
        'expected_start_date',
        'expected_end_date',
        'is_approve',
        'approval_reason',
        'is_quoted',
        'pdf_link'
    ];

    protected static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            self::creating(function ($model) {
                $model->date = now()->format('Y-m-d');
                $model->created_by = Auth::id();
            });

            self::updating(function ($model) {
                $model->updated_by = Auth::id();
            });
        }
    }

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function department(){
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function  requestItems(){
        return $this->hasMany(ProcurementRequestItem::class, 'request_id', 'id')->where('item_type', 1);
    }
    
}
