<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'total',             // total sebelum diskon
        'total_price',       // total setelah diskon
        'discount_amount',   // jumlah potongan
        'status',            // status transaksi (pending, completed, dll)
        'customer_name',
    ];

    /**
     * Relasi: Transaksi milik User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Transaksi punya banyak item (detail)
     */
    public function items()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    /**
     * Alias: details → items
     */
    public function details()
    {
        return $this->items();
    }

    /**
     * Fungsi untuk mengurangi stok produk dari transaksi ini
     */
    public function reduceProductStock()
    {
        foreach ($this->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->decrement('stock', $item->quantity);
            }
        }
    }
}
