<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * Get user's cart items
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $cartItems = Cart::getByUser($user->id);

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->effective_price;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cartItems,
                'total' => $total,
                'count' => $cartItems->sum('quantity')
            ]
        ]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user();
        $product = Product::findOrFail($validated['product_id']);

        // Check if product is in stock
        if (!$product->in_stock || $product->stock_quantity < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock or insufficient quantity'
            ], 400);
        }

        // Check if item already exists in cart
        $existingCartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingCartItem) {
            // Update quantity
            $newQuantity = $existingCartItem->quantity + $validated['quantity'];
            
            if ($newQuantity > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more than available stock'
                ], 400);
            }

            $existingCartItem->update(['quantity' => $newQuantity]);
            $cartItem = $existingCartItem;
        } else {
            // Create new cart item
            $cartItem = Cart::create([
                'user_id' => $user->id,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity']
            ]);
        }

        $cartItem->load('product.category');

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'data' => $cartItem
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user();
        $cartItem = Cart::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $product = $cartItem->product;

        if ($validated['quantity'] > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update to more than available stock'
            ], 400);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);
        $cartItem->load('product.category');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'data' => $cartItem
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $cartItem = Cart::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ]);
    }

    /**
     * Clear all cart items
     */
    public function clear(Request $request): JsonResponse
    {
        $user = $request->user();
        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }
}
