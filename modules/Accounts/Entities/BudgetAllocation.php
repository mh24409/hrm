<?php

namespace Modules\Accounts\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HumanResource\Entities\Department;

class BudgetAllocation extends Model
{
    use HasFactory, SoftDeletes;

    // Define the fields that are fillable in the model
    protected $fillable = ['financial_year_id','branch_id','acc_quarter_id','department_id', 'acc_coa_id', 'budget_amount'];

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
    }

    // Relationship with FinancialYear: Each BudgetAllocation belongs to one FinancialYear
    public function financial_year() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id', 'id');
    }

    // Relationship with Department: Each BudgetAllocation belongs to one Department
    public function department() {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    // Relationship with AccQuarter: Each BudgetAllocation belongs to one AccQuarter
    public function acc_quarter() {
        return $this->belongsTo(AccQuarter::class, 'acc_quarter_id', 'id');
    }

    // Relationship with AccCoa: Each BudgetAllocation belongs to one AccCoa
    public function acc_coa() {
        return $this->belongsTo(AccCoa::class, 'acc_coa_id', 'id');
    }
}
