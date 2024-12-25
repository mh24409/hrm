<?php

namespace Modules\HumanResource\Entities;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Modules\HumanResource\Entities\ProcurementVendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HumanResource\Entities\ProcurementRequest;
use Modules\HumanResource\Entities\ProcurementBidAnalysis;
use Modules\HumanResource\Entities\ProcurementRequestItem;
use Modules\HumanResource\Entities\ProcurementPurchaseOrder;

class ProcurementQuotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'bid_analysis_id',
        'purchase_order_id',
        'company_name',
        'vendor_id',
        'date',
        'address',
        'pin_or_equivalent',
        'expected_delivery_date',
        'delivery_place',
        'signature',
        'total',
        'pdf_link',
    ];

    protected static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            self::creating(function ($model) {
                $model->date = now()->format('Y-m-d');
                $model->created_by = Auth::id();
            });

            self::updating(function ($model) {
                $model->updated_by = Auth::id();
            });
        }
    }

    public function requestItems()
    {
        return $this->hasMany(ProcurementRequestItem::class, 'request_id', 'id')->where('item_type', 2);
    }

    public function request()
    {
        return $this->belongsTo(ProcurementRequest::class, 'request_id', 'id');
    }

    public function bidAnalysis()
    {
        return $this->belongsTo(ProcurementBidAnalysis::class, 'bid_analysis_id', 'id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(ProcurementPurchaseOrder::class, 'purchase_order_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(ProcurementVendor::class, 'vendor_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
