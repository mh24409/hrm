<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AccMonthlyBalance extends Model {
    use HasFactory, SoftDeletes;

    // Define the fields that are fillable in the model
    protected $fillable = [
        'financial_year_id',
        'acc_coa_id',
        'balance1',
        'balance2',
        'balance3',
        'balance4',
        'balance5',
        'balance6',
        'balance7',
        'balance8',
        'balance9',
        'balance10',
        'balance11',
        'balance12',
        'total_balance',
        'updated_date',
    ];

    //Boot method for auto UUID and created_by and updated_by field value
    protected static function boot() {
        parent::boot();

        if (Auth::check()) {
            self::creating(function ($model) {
                $model->uuid       = (string) Str::uuid();
                $model->created_by = Auth::id();
            });
            self::updating(function ($model) {
                $model->updated_by = Auth::id();
            });
            self::deleted(function ($model) {
                $model->updated_by = Auth::id();
                $model->save();
            });
        }

    }

}
