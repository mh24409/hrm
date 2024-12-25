<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CertificateType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
    
}
