<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // TAMBAHIN 'image' dan 'category_id' di sini
    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'stock', 
        'image',        // ← TAMBAH INI
        'category_id'   // ← TAMBAH INI
    ];

    // Relasi dengan tabel Review (bila ada)
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relasi dengan Promo (produk yang terkait dengan promo aktif)
    public function promos()
    {
        return $this->belongsToMany(Promo::class, 'promo_product')
                    ->withPivot('discount', 'is_active')
                    ->withTimestamps()
                    ->wherePivot('is_active', true);
    }

    // Opsional: Relasi dengan semua promo (termasuk yang tidak aktif)
    public function allPromos()
    {
        return $this->belongsToMany(Promo::class, 'promo_product')
                    ->withPivot('discount', 'is_active')
                    ->withTimestamps();
    }

    // Relasi dengan kategori produk
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi dengan transaksi melalui tabel pivot (misalnya transaksi yang melibatkan produk)
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_details', 'product_id', 'transaction_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}