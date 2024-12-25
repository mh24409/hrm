<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'tin_no', 
        'gross_salary', 
        'basic', 
        'transport', 
        'house_rent', 
        'medical', 
        'other_allowance', 
        'state_income_tax', 
        'soc_sec_npf_tax', 
        'loan_deduct', 
        'salary_advance', 
        'lwp', 
        'pf', 
        'stamp', 
        'medical_benefit', 
        'family_benefit', 
        'transportation_benefit', 
        'other_benefit', 
        'is_active'
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
        }

        static::addGlobalScope('sortByLatest', function (Builder $builder) {
            $builder->orderByDesc('id');
        });
    }
}
