<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Display a listing of published articles
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::with('author')
            ->published();

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                  ->orWhere('excerpt', 'ILIKE', "%{$search}%")
                  ->orWhere('content', 'ILIKE', "%{$search}%");
            });
        }

        // Filter by tags
        if ($request->has('tag')) {
            $tag = $request->get('tag');
            $query->whereJsonContains('tags', $tag);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'published_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $articles = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    /**
     * Get featured articles
     */
    public function featured(): JsonResponse
    {
        $articles = Article::with('author')
            ->published()
            ->featured()
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    /**
     * Store a newly created article
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:articles',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'gallery' => 'nullable|array',
            'status' => 'in:draft,published,archived',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|array',
            'is_featured' => 'boolean'
        ]);

        $validated['user_id'] = $request->user()->id;

        $article = Article::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Article created successfully',
            'data' => $article->load('author')
        ], 201);
    }

    /**
     * Display the specified article and increment views
     */
    public function show(Article $article): JsonResponse
    {
        // Only show published articles to public
        if ($article->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Article not found'
            ], 404);
        }

        $article->load('author');
        $article->incrementViews();

        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }

    /**
     * Update the specified article
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:articles,slug,' . $article->id,
            'excerpt' => 'nullable|string',
            'content' => 'sometimes|string',
            'featured_image' => 'nullable|string',
            'gallery' => 'nullable|array',
            'status' => 'sometimes|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|array',
            'is_featured' => 'boolean'
        ]);

        $article->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully',
            'data' => $article->load('author')
        ]);
    }

    /**
     * Remove the specified article
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully'
        ]);
    }
}
