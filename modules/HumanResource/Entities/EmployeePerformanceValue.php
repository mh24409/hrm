<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeePerformanceValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['performance_id', 'performance_type_id', 'performance_criteria_id', 'emp_perform_eval', 'score', 'comments'];
    
    protected static function boot()
    {
        parent::boot();
        if(Auth::check()){
            self::creating(function($model) {
                $model->uuid = (string) Str::uuid();
                $model->created_by = Auth::id();
            });

            self::updating(function($model) {
                $model->updated_by = Auth::id();
            });
        }        
    }

    public function employee_performance() {
        return $this->belongsTo(EmployeePerformance::class, 'performance_id', 'id');
    }

    public function employee_performance_type() {
        return $this->belongsTo(EmployeePerformanceType::class, 'performance_type_id', 'id');
    }

    public function employee_performance_criteria() {
        return $this->belongsTo(EmployeePerformanceCriteria::class, 'performance_criteria_id', 'id');
    }
    
}
