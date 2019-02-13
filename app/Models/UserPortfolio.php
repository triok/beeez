<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserPortfolio extends Model
{
    protected $table = 'user_portfolio';

    protected $fillable = ['user_id', 'name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}