<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcurementPurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_received_id',
        'created_date',
        'quotation_id',
        'vendor_name',
        'location',
        'address',
        'total',
        'discount',
        'grand_total',
        'notes',
        'authorizer_name',
        'authorizer_title',
        'authorizer_signature',
        'authorizer_date',
        'pdf_link'
    ];

    protected static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            self::creating(function ($model) {
                $model->created_date = now()->format('Y-m-d');
                $model->created_by = Auth::id();
            });

            self::updating(function ($model) {
                $model->updated_by = Auth::id();
            });
        }
    }

    public function requestItemsOrders()
    {
        return $this->hasMany(ProcurementRequestItem::class, 'request_id', 'id')->where('item_type', 4);
    }

    
    
}
