<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Relasi: Item milik satu transaksi
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relasi: Item terkait satu produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
