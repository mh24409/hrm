<?php

namespace Modules\Accounts\Entities;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Accounts\Entities\AccTransaction;
use Modules\Accounts\Entities\AccVoucher;

class AccCoa extends Model
{
    use HasFactory, SoftDeletes;

    // Define the fields that are fillable in the model
    protected $fillable = [
        'account_code',
        'account_name',
        'head_level',
        'parent_id',
        'acc_type_id',
        'is_cash_nature',
        'is_bank_nature',
        'is_budget',
        'is_depreciation',
        'depreciation_rate',
        'is_subtype',
        'subtype_id',
        'is_stock',
        'is_fixed_asset_schedule',
        'note_no',
        'asset_code',
        'dep_code',
        'is_active',
    ];


    //Boot method for auto UUID and created_by and updated_by field value
    protected static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            self::creating(function ($model) {
                $model->uuid = (string) Str::uuid();
                $model->created_by = Auth::id();
            });

            self::updating(function ($model) {
                $model->updated_by = Auth::id();
            });

            self::deleted(function ($model) {
                $model->updated_by = Auth::id();
                $model->save();
            });
        }

        self::created(function ($model) {
            $model->account_code = $model->generateAccCode($model->head_level, $model->parent_id);
            $model->save();
        });
    }

    private function generateAccCode(int $head_level, int $parent_id)
    {
        $key = AccCoa::where('parent_id', $parent_id)->withTrashed()->count();
        $p_acc_code = AccCoa::find($parent_id);
        $account_code = null;

        $p_acc_code = $p_acc_code->account_code;

        switch ($head_level) {
            case 2:
                $account_code = $p_acc_code . sprintf('%01d', ($key ?? 0 + 1));
                break;

            case 3:
                $account_code = $p_acc_code . sprintf('%02d', ($key ?? 0 + 1));
                break;

            case 4:
                $account_code = $p_acc_code . sprintf('%03d', ($key ?? 0 + 1));
                break;

            default:
                $account_code = null;
                break;
        }

        return $account_code;
    }

    //Relationship with AccTransaction: Each AccCoa has multiple AccTransactions
    public function accTransactions()
    {
        return $this->hasMany(AccTransaction::class);
    }

    //Relationship with AccVoucher: Each AccCoa has multiple AccVouchers
    public function accVouchers()
    {
        return $this->hasMany(AccVoucher::class);
    }

    // Relationship with AccSubtype: Each AccCoa belongs to one AccSubtype
    public function subtype(): BelongsTo
    {
        return $this->belongsTo(AccSubtype::class, 'subtype_id', 'id');
    }

    // Relationship with parent AccCoa: Each AccCoa belongs to a parent AccCoa (hierarchical structure)
    public function parentName(): BelongsTo
    {
        return $this->belongsTo(AccCoa::class, 'parent_id', 'id')->select('id', 'account_name');
    }

    // Relationship with child AccCoa (head_level = 2)
    public function secondChild()
    {
        return $this->hasMany(AccCoa::class, 'parent_id', 'id')->where('head_level', 2);
    }

    // Relationship with child AccCoa (head_level = 3)
    public function thirdChild()
    {
        return $this->hasMany(AccCoa::class, 'parent_id', 'id')->where('head_level', 3);
    }

    // Relationship with child AccCoa (head_level = 4)
    public function fourthChild()
    {
        return $this->hasMany(AccCoa::class, 'parent_id', 'id')->where('head_level', 4);
    }

    // Scope to filter parent AccCoa
    public function scopeParent($query)
    {
        return $query->where('parent_id', 0)->where('head_level', 1);
    }

    // Scope to filter active AccCoa
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
