<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandidateInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_rand_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'alternative_phone',
        'present_address',
        'permanent_address',
        'picture',
        'ssn',
        'country_id',
        'state',
        'city',
        'zip',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($candidate) {
            $candidate->candidate_rand_id = str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
        });
    }

    public function educations()
    {
        return $this->hasMany(CandidateEducation::class, 'candidate_id', 'id');
    }

    public function workExperiences()
    {
        return $this->hasMany(CandidateWorkExperience::class, 'candidate_id', 'id');
    }
  
}
