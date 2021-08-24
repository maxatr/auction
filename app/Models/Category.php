<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    public function scopeOrderedByName(Builder $builder, $direction = 'asc')
    {
        $builder->orderBy('name', $direction);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
