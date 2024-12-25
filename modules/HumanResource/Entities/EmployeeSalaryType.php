<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeSalaryType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['setup_rule_id', 'employee_id', 'type', 'amount', 'on_basic', 'on_gross', 'is_percent', 'is_active'];
    
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

    protected $casts = [
        'amount' => 'float',
    ];

    public function setup_rule() {
        return $this->belongsTo(SetupRule::class);
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    
    

}