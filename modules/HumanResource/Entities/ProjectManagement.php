<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HumanResource\Entities\ProjectClients;
use Modules\HumanResource\Entities\Employee;

class ProjectManagement extends Model
{
    use HasFactory,SoftDeletes;

    // Explicitly define the table name
    protected $table = 'pm_projects';

    protected $fillable =  [
        'id',
        'uuid',
        'first_parent_project_id', 
        'second_parent_project_id',
        'version_no',
        'project_name',
        'client_id',
        'project_lead',
        'approximate_tasks', 
        'complete_tasks',
        'start_date',
        'project_start_date',
        'close_date',
        'project_duration',
        'completed_days', 
        'project_summary',
        'is_completed',
        'project_reward_point',
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

    public function clientDetail()
    {
        return $this->belongsTo(ProjectClients::class,'client_id','id');
    }

    public function projectLead()
    {
        return $this->belongsTo(Employee::class,'project_lead','id');
    }

}
