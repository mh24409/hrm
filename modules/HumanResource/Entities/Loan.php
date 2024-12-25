<?php

namespace Modules\HumanResource\Entities;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['employee_id', 'permission_by_id','amount','interest_rate','installment','installment_period','installment_cleared','repayment_amount','released_amount','release','approved_date','repayment_start_date', 'loan_details', 'is_active'];
    
    protected static function boot()
    {
        parent::boot();
        if(Auth::check()){
            self::creating(function($model) {
                $model->uuid = (string) Str::uuid();
                $model->created_by = Auth::id();
            });

            self::created(function($model) {
                $model->loan_no = str_pad($model->id,6,0,STR_PAD_LEFT);
                $model->save();
            });

            self::updating(function($model) {
                $model->updated_by = Auth::id();
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

    public function permission_by()
    {
        return $this->belongsTo(Employee::class, 'permission_by_id', 'id');
    }
}
