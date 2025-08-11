<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="card p-6 text-center">
            <div class="w-12 h-12 mx-auto mb-4 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                <i class="pi pi-shopping-bag text-xl text-blue-600 dark:text-blue-400"></i>
            </div>
            <h6 class="text-2xl font-bold text-surface-900 dark:text-surface-0 mb-1">{{ stats.totalProducts }}</h6>
            <p class="text-sm text-surface-600 dark:text-surface-400">Total Products</p>
        </div>

        <div class="card p-6 text-center">
            <div class="w-12 h-12 mx-auto mb-4 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                <i class="pi pi-tag text-xl text-green-600 dark:text-green-400"></i>
            </div>
            <h6 class="text-2xl font-bold text-surface-900 dark:text-surface-0 mb-1">{{ stats.totalCategories }}</h6>
            <p class="text-sm text-surface-600 dark:text-surface-400">Categories</p>
        </div>

        <div class="card p-6 text-center">
            <div class="w-12 h-12 mx-auto mb-4 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                <i class="pi pi-file-text text-xl text-purple-600 dark:text-purple-400"></i>
            </div>
            <h6 class="text-2xl font-bold text-surface-900 dark:text-surface-0 mb-1">{{ stats.totalArticles }}</h6>
            <p class="text-sm text-surface-600 dark:text-surface-400">Articles</p>
        </div>

        <div class="card p-6 text-center">
            <div class="w-12 h-12 mx-auto mb-4 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                <i class="pi pi-users text-xl text-orange-600 dark:text-orange-400"></i>
            </div>
            <h6 class="text-2xl font-bold text-surface-900 dark:text-surface-0 mb-1">{{ stats.totalUsers || 'N/A' }}</h6>
            <p class="text-sm text-surface-600 dark:text-surface-400">Active Users</p>
        </div>
    </div>
</template>

<script setup>
import IndoPetArticleService from '@/service/IndoPetArticleService.js';
import IndoPetCategoryService from '@/service/IndoPetCategoryService.js';
import IndoPetProductService from '@/service/IndoPetProductService.js';
import { onMounted, ref } from 'vue';

const stats = ref({
    totalProducts: 0,
    totalCategories: 0,
    totalArticles: 0,
    totalUsers: 0
});

const loadStats = async () => {
    try {
        // Load products count
        const productsResponse = await IndoPetProductService.getProducts();
        stats.value.totalProducts = productsResponse.data?.length || 0;

        // Load categories count
        const categoriesResponse = await IndoPetCategoryService.getCategories();
        stats.value.totalCategories = categoriesResponse.data?.length || 0;

        // Load articles count
        const articlesResponse = await IndoPetArticleService.getArticles();
        stats.value.totalArticles = articlesResponse.data?.length || 0;

        // TODO: Add users endpoint when available
    } catch (error) {
        console.error('Failed to load stats:', error);
    }
};

onMounted(() => {
    loadStats();
});
</script>
