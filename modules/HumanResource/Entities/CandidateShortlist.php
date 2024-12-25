<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandidateShortlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'position_id',
        'shortlist_date',
        'interview_date',
    ];

    public function candidate(){
        return $this->belongsTo(CandidateInformation::class, 'candidate_id', 'id');
    }

    public function position(){
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }


    
}
