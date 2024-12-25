<?php

namespace Modules\HumanResource\Entities;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Modules\Branch\Entities\Branch;

class LeaveType extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'leave_type',
        'leave_days',
        'leave_code',
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

}
