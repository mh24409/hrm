<?php

namespace Modules\Accounts\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccOpeningBalance extends Model
{
    use HasFactory, SoftDeletes;

    // Define the fields that are fillable in the model
    protected $fillable = [
        'financial_year_id',
        'acc_coa_id',
        'acc_subtype_id',
        'acc_subcode_id',
        'debit',
        'credit',
        'open_date'
    ];

    /**
     * Boot method for auto UUID and created_by and updated_by field value
     */
    protected static function boot() {
        parent::boot();

        // Check if user is authenticated before performing operations
        if (Auth::check()) {
            // Event when a new record is being created
            self::creating(function ($model) {
                $model->uuid       = (string) Str::uuid(); // Generate a UUID for the model
                $model->created_by = Auth::id(); // Set the created_by field to the authenticated user's ID
            });

            // Event when an existing record is being updated
            self::updating(function ($model) {
                $model->updated_by = Auth::id(); // Set the updated_by field to the authenticated user's ID
            });

            // Event when a record is being deleted (soft delete)
            self::deleted(function ($model) {
                $model->updated_by = Auth::id(); // Set the updated_by field to the authenticated user's ID
                $model->save(); // Save the model to update the changes
            });
        }
    }

    // Relationship with FinancialYear: Each AccOpeningBalance belongs to one FinancialYear
    public function financial_year() {
        return $this->belongsTo(FinancialYear::class);
    }

    // Relationship with AccCoa: Each AccOpeningBalance belongs to one AccCoa
    public function acc_coa() {
        return $this->belongsTo(AccCoa::class);
    }

    // Relationship with AccSubtype: Each AccOpeningBalance belongs to one AccSubtype
    public function subtype() {
        return $this->belongsTo(AccSubtype::class, 'acc_subtype_id', 'id');
    }

    public function subCode() {
        return $this->belongsTo(AccSubcode::class, 'acc_subcode_id', 'id');
    }
}
