<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\LeaveType;

class ApplyLeave extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'employee_id',
        'leave_type_id',
        'academic_year_id',
        'leave_apply_start_date',
        'leave_apply_end_date',
        'leave_apply_date',
        'total_apply_day',
        'leave_approved_start_date',
        'leave_approved_end_date',
        'total_approved_day',
        'is_approved_by_manager',
        'approved_by_manager',
        'manager_approved_date',
        'manager_approved_description',
        'leave_approved_date',
        'approved_by',
        'reason',
        'location',
        'is_approved',
    ];

    protected static function boot()
    {
    parent::boot();
    if (Auth::check()) {
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });


        self::deleted(function ($model) {
            $model->updated_by = Auth::id();
            $model->save();
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
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

}
