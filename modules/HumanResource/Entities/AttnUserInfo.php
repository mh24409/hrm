<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttnUserInfo extends Model
{
    use HasFactory;

    protected $fillable = ['uid', 'user_id', 'role', 'name', 'password', 'employee_device_id'];

    public function attendance_times()
    {
        return $this->hasMany(AttnCheckinCheckout::class, 'user_id', 'user_id');
    }
}
