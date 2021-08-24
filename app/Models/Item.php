<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = ['category_id', 'name', 'min_price'];

    public function scopeOrderedByMinPrice(Builder $builder, $direction = 'asc')
    {
        $builder->orderBy('min_price', $direction);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
}
