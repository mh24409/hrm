<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'gift',
        'date',
        'employee_id',
        'awarded_by',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
