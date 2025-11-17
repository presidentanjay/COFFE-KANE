<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = ['name', 'discount', 'is_active'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promo_product')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }
}
