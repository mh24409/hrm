<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcurementCommittee extends Model
{
    use HasFactory;

    protected $fillable = [
        'bid_id',
        'bid_committee_id',
        'type',
        'name',
        'signature',
        'date'
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

    public function bid()
    {
        return $this->belongsTo(ProcurementBidAnalysis::class, 'bid_id', 'id');
    }
}
