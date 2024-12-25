<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcurementRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'item_type',
        'company',
        'material_description',
        'unit_id',
        'quantity',
        'unit_price',
        'total_price',
        'choosing_reason',
        'remarks',
    ];  
    
}
