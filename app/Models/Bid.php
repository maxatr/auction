<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $fillable = ['item_id', 'user_id', 'bid_amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
