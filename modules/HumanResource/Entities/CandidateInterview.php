<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandidateInterview extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'interviewer',
        'position_id',
        'interview_date',
        'interview_marks',
        'written_marks',
        'mcq_marks',
        'total_marks',
        'recommandation',
        'selection',
        'details',
    ];

    public function candidate(){
        return $this->belongsTo(CandidateInformation::class, 'candidate_id', 'id');
    }

    public function position(){
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
    
}
