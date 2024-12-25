<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HumanResource\Entities\Employee;

class PointCollaborative extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable =  [
        'id',
        'uuid',
        'point_shared_by',
        'point_shared_with',
        'reason',
        'point',
        'point_date',
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

    public function pointShareEmployee()
    {
        return $this->belongsTo(Employee::class,'point_shared_by','id');
    }

    public function pointReceiveEmployee()
    {
        return $this->belongsTo(Employee::class,'point_shared_with','id');
    }
}
