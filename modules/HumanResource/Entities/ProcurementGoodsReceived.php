<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Support\Facades\Auth;
use Modules\Accounts\Entities\AccCoa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcurementGoodsReceived extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'created_date',
        'vendor_name',
        'vendor_id',
        'invoice_number',
        'total_quantity',
        'total',
        'discount',
        'grand_total',
        'acc_coa_id',
        'received_by_signature',
        'received_by_name',
        'received_by_title',
        'voucher_id',
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

    public function requestItemsReceives()
    {
        return $this->hasMany(ProcurementRequestItem::class, 'request_id', 'id')->where('item_type', 5);
    }

    public function accCoa()
    {
        return $this->belongsTo(AccCoa::class, 'acc_coa_id', 'id');
    }
}
