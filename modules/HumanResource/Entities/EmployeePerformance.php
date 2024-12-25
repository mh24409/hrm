<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeePerformance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'performance_code',
        'position_of_supervisor',
        'review_period',
        'note',
        'date',
        'note_by',
        'score',
        'total_score',
        'number_of_star',
        'employee_comments',
        'is_teacher',
        'class_name'
    ];

    protected static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            self::creating(function ($model) {
                $model->uuid = (string) Str::uuid();
                $model->created_by = Auth::id();
            });

            self::created(function ($model) {
                $model->performance_code = str_pad($model->id, 6, 0, STR_PAD_LEFT);
                $model->save();
            });

            self::updating(function ($model) {
                $model->updated_by = Auth::id();
            });

            static::deleting(function ($model) {
                $model->performance_value()->delete();
                $model->development_plan()->delete();
                $model->key_goal()->delete();
            });
        }

        static::addGlobalScope('sortByLatest', function (Builder $builder) {
            $builder->orderByDesc('id');
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function performance_value()
    {
        return $this->hasMany(EmployeePerformanceValue::class, 'performance_id', 'id');
    }

    public function development_plan()
    {
        return $this->hasMany(EmployeePerformanceDevelopmentPlan::class, 'performance_id', 'id');
    }

    public function key_goal()
    {
        return $this->hasMany(EmployeePerformanceKeyGoal::class, 'performance_id', 'id');
    }
}
