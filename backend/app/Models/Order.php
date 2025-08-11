<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'payment_status',
        'payment_method',
        'billing_address',
        'shipping_address',
        'notes',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'billing_address' => 'json',
        'shipping_address' => 'json',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];

    /**
     * Get the user who placed the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $timestamp = now()->format('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . $timestamp . $random;
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Get total items count
     */
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }
}
