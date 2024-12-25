<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeePerformanceCriteria extends Model
{
    use HasFactory;

    protected $fillable = ['performance_type_id', 'evaluation_type_id', 'title', 'description'];
    
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

    public function performance_type() {
        return $this->belongsTo(EmployeePerformanceType::class, 'performance_type_id', 'id');
    }

    public function evaluation_type() {
        return $this->belongsTo(EmployeePerformanceEvaluationType::class, 'evaluation_type_id', 'id');
    }
}
