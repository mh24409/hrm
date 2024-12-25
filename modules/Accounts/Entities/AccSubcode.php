<?php

namespace Modules\Accounts\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounts\Entities\AccTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CustomerSupplier\Entities\Customer;
use Modules\CustomerSupplier\Entities\Supplier;
use Modules\HumanResource\Entities\Employee;

class AccSubcode extends Model
{
    use HasFactory, SoftDeletes;

    // Define the fields that are fillable in the model
    protected $fillable = [
        'acc_subtype_id',
        'name',
        'reference_no',
        'status'
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

    // Relationship with AccSubtype: Each AccSubcode belongs to one AccSubtype
    public function subtype() {
        return $this->belongsTo(AccSubtype::class, 'acc_subtype_id', 'id');
    }

    // Relationship with AccTransaction: Each AccSubcode has multiple AccTransactions
    public function transactions() {
        return $this->hasMany(AccTransaction::class, 'acc_subcode_id', 'id');
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'reference_no', 'id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'reference_no', 'id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'reference_no', 'id');
    }

}