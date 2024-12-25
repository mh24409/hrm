<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcurementBidAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'create_date',
        'sba_no',
        'location',
        'attachment',
        'total',
        'pdf_link'
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

    public function requestItemsBids()
    {
        return $this->hasMany(ProcurementRequestItem::class, 'request_id', 'id')->where('item_type', 3);
    }

    public function bidCommittees()
    {
        return $this->hasMany(ProcurementCommittee::class, 'bid_id', 'id');
    }

}
