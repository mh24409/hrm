<?php

namespace Modules\HumanResource\Entities;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Holiday extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'id',
        'uuid',
        'holiday_name',
        'start_date',
        'end_date',
        'total_day',
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
