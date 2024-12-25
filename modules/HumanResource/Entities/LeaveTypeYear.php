<?php

namespace Modules\HumanResource\Entities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Modules\Accounts\Entities\FinancialYear;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\LeaveType;


class LeaveTypeYear extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'employee_id',
        'leave_type_id',
        'academic_year_id',
        'entitled',
        'taken',

    ];

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


            self::deleted(function($model){
                $model->updated_by = Auth::id();
                $model->save();
            });


        }


	static::addGlobalScope('sortByLatest', function (Builder $builder) {
            $builder->orderByDesc('id');
        });
    }

    public function academicYear()
    {
        return $this->belongsTo(FinancialYear::class);
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
