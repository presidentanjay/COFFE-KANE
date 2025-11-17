<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',  // ID transaksi
        'product_id',      // ID produk
        'quantity',        // Jumlah produk yang dibeli
        'price',           // Harga produk
    ];

    /**
     * Relasi ke model Transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');  // Menghubungkan ke 'transactions'
    }

    /**
     * Relasi ke model Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');  // Menghubungkan ke 'products'
    }
}
