<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandidateSelection extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'employee_id',
        'position_id',
        'selection_terms',
    ];
    
    public function candidate(){
        return $this->belongsTo(CandidateInformation::class, 'candidate_id', 'id');
    }

    public function position(){
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
