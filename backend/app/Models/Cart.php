<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    /**
     * Get the user who owns the cart item
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product in the cart
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get cart items by user
     */
    public static function getByUser($userId)
    {
        return static::where('user_id', $userId)
                    ->with('product.category')
                    ->get();
    }

    /**
     * Get cart items by session (for guest users)
     */
    public static function getBySession($sessionId)
    {
        return static::where('session_id', $sessionId)
                    ->with('product.category')
                    ->get();
    }

    /**
     * Get total price for this cart item
     */
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->product->effective_price;
    }
}
