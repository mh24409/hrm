<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkillType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
    
}
