<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'images',
        'weight',
        'dimensions',
        'status',
        'is_featured',
        'attributes',
        'meta_description',
        'meta_title'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'stock_quantity' => 'integer',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'images' => 'json',
        'attributes' => 'json'
    ];

    /**
     * Get the category that owns the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get cart items for this product
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get order items for this product
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the effective price (sale price if available, otherwise regular price)
     */
    public function getEffectivePriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Check if product is on sale
     */
    public function getIsOnSaleAttribute()
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    /**
     * Get discount percentage if on sale
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->is_on_sale) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }
}
