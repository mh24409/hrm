<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryGenerate extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'employee_id',
        'loan_id',
        'salary_advanced_id',
        'salary_month_year',
        'tin_no',
        'total_attendance',
        'total_count',
        'attendance_allowance',
        'gross',
        'basic',
        'transport',
        'total_deduction',
        'house_rent',
        'medical',
        'other_allowance',
        'gross_salary',      
        'income_tax',
        'soc_sec_npf_tax',
        'employer_contribution',
        'icf_amount',
        'loan_deduct',
        'salary_advance',
        'leave_without_pay',
        'provident_fund',
        'stamp',
        'net_salary',
        'medical_benefit',
        'family_benefit',
        'transportation_benefit',
        'other_benefit',
        'normal_working_hrs_month',
        'actual_working_hrs_month',
        'hourly_rate_basic',
        'hourly_rate_trasport_allowance',
        'basic_salary_pro_rated',
        'transport_allowance_pro_rated',
        'basic_transport_allowance',
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
        
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
