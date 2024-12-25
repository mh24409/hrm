<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccSubtype;
use Modules\Accounts\Entities\AccVoucherType;

class AccTransaction extends Model
{
    use HasFactory, SoftDeletes;

    // Define the fields that are fillable in the model
    protected $fillable = [
        'id',
        'uuid',
        'auto_create',
        'acc_coa_id',
        'financial_year_id',
        'acc_subtype_id',
        'acc_subcode_id',
        'voucher_no',
        'voucher_type_id',
        'reference_no',
        'voucher_date',
        'narration',
        'cheque_no',
        'cheque_date',
        'is_honour',
        'ledger_comment',
        'debit',
        'credit',
        'reverse_code',
        'is_approved',
        'status',
        'is_year_closed',
        'created_by',
        'updated_by',
        'approved_by',
        'approved_at',
    ];

    /**
     * Boot method for auto UUID and created_by and updated_by field value
     */
    protected static function boot()
    {
        parent::boot();

        // Check if user is authenticated before performing operations
        if (Auth::check()) {
            // Event when a new record is being created
            self::creating(function($model) {
                $model->uuid = (string) Str::uuid(); // Generate a UUID for the model
                $model->created_by = Auth::id(); // Set the created_by field to the authenticated user's ID
            });

            // Event when an existing record is being updated
            self::updating(function($model) {
                $model->updated_by = Auth::id(); // Set the updated_by field to the authenticated user's ID
            });

            // Event when a record is being deleted (soft delete)
            self::deleted(function($model) {
                $model->updated_by = Auth::id(); // Set the updated_by field to the authenticated user's ID
                $model->save(); // Save the model to update the changes
            });
        }

        // Global scope to order records by latest ID in descending order
        static::addGlobalScope('sortByLatest', function (Builder $builder) {
            $builder->orderByDesc('id');
        });
    }

    // Relationship with AccCoa: Each AccTransaction belongs to one AccCoa
    public function accCoa()
    {
        return $this->belongsTo(AccCoa::class);
    }

    // Relationship with AccSubtype: Each AccTransaction belongs to one AccSubtype
    public function accSubtype()
    {
        return $this->belongsTo(AccSubtype::class);
    }

    // Relationship with AccCoa (reverse code): Each AccTransaction belongs to one AccCoa as reverse code
    public function accReverseCode()
    {
        return $this->belongsTo(AccCoa::class, 'reverse_code', 'id');
    }

    // Relationship with AccVoucherType: Each AccTransaction belongs to one AccVoucherType
    public function accVoucherType()
    {
        return $this->belongsTo(AccVoucherType::class, 'reverse_code', 'id');
    }

    // Relationship with AccVoucherType (voucher_type_id): Each AccTransaction belongs to one AccVoucherType
    public function voucherType()
    {
        return $this->belongsTo(AccVoucherType::class, 'voucher_type_id', 'id');
    }

    // Relationship with AccSubcode: Each AccTransaction belongs to one AccSubcode
    public function accSubcode()
    {
        return $this->belongsTo(AccSubcode::class, 'acc_subcode_id', 'id');
    }
}
