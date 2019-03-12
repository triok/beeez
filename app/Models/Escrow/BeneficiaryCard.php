<?php

namespace App\Models\Escrow;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryCard extends Model
{
    protected $fillable = ['platform_beneficiary_id', 'beneficiary_payment_tool_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
