<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandidateEducation extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'degree',
        'university',
        'cgpa',
        'comments',
        'sequence',
    ];
    
}
