<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class AssociationRule extends Model
{
    protected $table = 'association_rules';

    protected $fillable = [
        'itemset_antecedent',
        'itemset_consequent',
        'support',
        'confidence',
        'lift',
        'is_active',
        'discount_percent',
        'promo_price',
        'view_count',
        'order_count',
        'like_count',
    ];

    public function items()
    {
        return $this->belongsToMany(Product::class, 'association_rule_product', 'rule_id', 'product_id');
    }
}
