<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttnCheckinCheckout extends Model
{
    use HasFactory;

    protected $fillable = ['uid', 'user_id', 'state', 'timestamp', 'type'];

    public function attn_user()
    {
        return $this->belongsTo(AttnUserInfo::class, 'user_id', 'user_id');
    }
}
