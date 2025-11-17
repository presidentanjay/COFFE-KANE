<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'transaction_id',
        'transaction_item_id', // ✅ Tambahkan ini
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // ✅ Tambahkan relasi ke item transaksi
    public function transactionItem()
    {
        return $this->belongsTo(\App\Models\TransactionItem::class, 'transaction_item_id');
    }
}
