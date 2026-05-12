<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]
class ZohoAccount extends Model {
    
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
