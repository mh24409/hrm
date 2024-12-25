<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcurementVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'mobile',
        'email',
        'address',
        'country_id',
        'city',
        'zip',
        'previous_balance'
    ];

    protected static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            self::creating(function ($model) {
                $model->created_by = Auth::id();
            });

            self::updating(function ($model) {
                $model->updated_by = Auth::id();
            });
        }
    }

    public function country(){
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
    
    
}
