<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuleItem extends Model
{
    protected $fillable = [
        'association_rule_id',
        'product_id',
    ];

    public function rule()
    {
        return $this->belongsTo(AssociationRule::class, 'association_rule_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
