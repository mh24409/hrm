<?php

namespace Modules\Accounts\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccPredefineAccount extends Model
{
    use HasFactory, SoftDeletes;

    // Define the fields that are fillable in the model
    protected $fillable = [
        'cash_code',
        'bank_code',
        'advance',
        'fixed_asset',
        'purchase_code',
        'purchase_discount',
        'sales_code',
        'customer_code',
        'employee_salary_expense',
        'supplier_code',
        'costs_of_good_solds',
        'vat',
        'tax',
        'inventory_code',
        'current_year_profit_loss_code',
        'last_year_profit_loss_code',
        'salary_code',
        'prov_state_tax',
        'prov_npf_code',
        'emp_npf_contribution',
        'empr_npf_contribution',
        'emp_icf_contribution',
        'empr_icf_contribution',
        'state_tax',
        'sales_discount',
        'shipping_cost1',
        'shipping_cost2'
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
}
