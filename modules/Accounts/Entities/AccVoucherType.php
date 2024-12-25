<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Accounts\Entities\AccTransaction;
use Modules\Accounts\Entities\AccVoucher;

class AccVoucherType extends Model
{
    use HasFactory;

    // Define the fields that are fillable in the model
    protected $fillable = [];

    /**
     * Relationship with AccVoucher: Each AccVoucherType has multiple AccVouchers
     */
    public function accVouchers()
    {
        return $this->hasMany(AccVoucher::class);
    }

    /**
     * Relationship with AccTransaction: Each AccVoucherType has multiple AccTransactions
     */
    public function accTransactions()
    {
        return $this->hasMany(AccTransaction::class);
    }
}
