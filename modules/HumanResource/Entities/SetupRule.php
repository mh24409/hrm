<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SetupRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'type', 'amount', 'start_time', 'end_time', 'on_basic', 'on_gross', 'is_percent', 'is_active'];

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
        }

        static::addGlobalScope('sortByLatest', function (Builder $builder) {
            $builder->orderByDesc('id');
        });
    }

    public function employee_salary_types()
    {
        return $this->hasMany(EmployeeSalaryType::class, 'setup_rule_id', 'id');
    }

}
