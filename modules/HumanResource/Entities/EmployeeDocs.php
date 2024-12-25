<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Modules\HumanResource\Entities\Employee;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeDocs extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['doc_title', 'employee_id', 'file_path', 'file_name', 'expiry_date', 'issue_date', 'is_returned'];
    
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
