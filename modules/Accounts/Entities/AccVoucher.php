<?php

namespace Modules\Accounts\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Modules\Accounts\Entities\AccCoa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Accounts\Entities\AccSubtype;
use Modules\Accounts\Entities\FinancialYear;
use Modules\Accounts\Entities\AccVoucherType;
use Modules\Accounts\Entities\AccSubcode;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccVoucher extends Model
{
    use HasFactory, SoftDeletes;

    // Define the fields that are fillable in the model
    protected $fillable = [
        'acc_coa_id',
        'financial_year_id',
        'acc_subtype_id',
        'acc_subcode_id',
        'voucher_no',
        'voucher_type',
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
        }

        // Global scope to order records by latest ID in descending order
        static::addGlobalScope('sortByLatest', function (Builder $builder) {
            $builder->orderByDesc('id');
        });
    }

    // Relationship with FinancialYear: Each AccVoucher belongs to one FinancialYear
    public function financial_year() {
        return $this->belongsTo(FinancialYear::class);
    }

    // Relationship with AccCoa: Each AccVoucher belongs to one AccCoa
    public function acc_coa() {
        return $this->belongsTo(AccCoa::class);
    }

    // Relationship with AccCoa (reverse code): Each AccVoucher belongs to one AccCoa as reverse code
    public function acc_coa_reverse() {
        return $this->belongsTo(AccCoa::class, 'reverse_code', 'id');
    }

    // Relationship with AccSubtype: Each AccVoucher belongs to one AccSubtype
    public function subtype() {
        return $this->belongsTo(AccSubtype::class, 'acc_subtype_id', 'id');
    }

    // Relationship with AccSubcode: Each AccVoucher belongs to one AccSubcode
    public function subcode() {
        return $this->belongsTo(AccSubcode::class, 'acc_subcode_id', 'id');
    }

    // Relationship with AccVoucherType: Each AccVoucher belongs to one AccVoucherType
    public function accVoucherType() {
        return $this->belongsTo(AccVoucherType::class, 'voucher_type', 'id');
    }

    // Relationship with AccSubcode: Each AccVoucher belongs to one AccSubcode
    public function accSubcode() {
        return $this->belongsTo(AccSubcode::class);
    }
}
