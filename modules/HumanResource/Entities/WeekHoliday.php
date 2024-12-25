<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeekHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'uuid',
        'dayname',
    ];


}
