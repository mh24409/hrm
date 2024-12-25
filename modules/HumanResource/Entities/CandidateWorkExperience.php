<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandidateWorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'company_name',
        'working_period',
        'duties',
        'supervisor',
        'sequence',
    ];
    
}
