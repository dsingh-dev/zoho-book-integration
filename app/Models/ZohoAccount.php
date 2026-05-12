<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded(['id'])]
class ZohoAccount extends Model
{
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
