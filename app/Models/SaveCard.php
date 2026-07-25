<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaveCard extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_payment_method_id',
        'brand','last_four',
        'exp_month','exp_year','is_default'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
