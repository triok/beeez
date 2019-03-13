<?php

namespace App\Models\Escrow;

use App\Models\Jobs\Application;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $guarded = ['id'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id');
    }
}
