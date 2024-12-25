<?php

namespace Modules\HumanResource\Entities;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalarySheetGenerate extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['name','generate_date','generate_by_id','start_date','end_date','is_approved', 'approved_by', 'approved_date'];

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

    public function generate_by() {
        return $this->belongsTo(User::class, 'generate_by_id', 'id');
    }

    public function approve_by() {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

}
