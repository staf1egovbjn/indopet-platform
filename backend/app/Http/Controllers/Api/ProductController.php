<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category')
            ->where('status', 'active');

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('description', 'ILIKE', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->get('min_price'));
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->get('max_price'));
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get featured products
     */
    public function featured(): JsonResponse
    {
        $products = Product::with('category')
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get products by category
     */
    public function byCategory(Category $category): JsonResponse
    {
        $products = Product::with('category')
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products',
            'stock_quantity' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
            'is_featured' => 'boolean',
            'attributes' => 'nullable|array',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string'
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product->load('category')
        ], 201);
    }

    /**
     * Display the specified product
     */
    public function show(Product $product): JsonResponse
    {
        $product->load('category');

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:products,slug,' . $product->id,
            'description' => 'sometimes|string',
            'short_description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'sometimes|string|unique:products,sku,' . $product->id,
            'stock_quantity' => 'sometimes|integer|min:0',
            'images' => 'nullable|array',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
            'is_featured' => 'boolean',
            'attributes' => 'nullable|array',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string'
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product->load('category')
        ]);
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}
