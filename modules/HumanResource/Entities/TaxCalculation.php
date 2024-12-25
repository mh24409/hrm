<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxCalculation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'min',
        'max',
        'tax_percent',
        'add_amount',
    ];

    protected static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            static::created(function ($model) {
                $model->created_by = Auth::user()->id;
            });
            static::updated(function ($model) {
                $model->updated_by = Auth::user()->id;
            });
        }
    }
}
