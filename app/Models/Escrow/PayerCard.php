<?php

namespace App\Models\Escrow;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PayerCard extends Model
{
    protected $fillable = ['platform_payer_id', 'payer_payment_tool_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
