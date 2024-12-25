<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeePerformanceType extends Model
{
    use HasFactory;

    protected $fillable = []; 


    public function performance_criterias() {
        return $this->hasMany(EmployeePerformanceCriteria::class, 'performance_type_id', 'id');
    }
   
}
