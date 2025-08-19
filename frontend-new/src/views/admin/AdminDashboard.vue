<template>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0 mb-2">Admin Dashboard</h1>
            <p class="text-surface-600 dark:text-surface-400">Welcome to IndoPet Admin Panel</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="card p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-surface-900 dark:text-surface-0">{{ stats.products }}</div>
                        <div class="text-surface-600 dark:text-surface-400">Products</div>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <i class="pi pi-shopping-bag text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="card p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-surface-900 dark:text-surface-0">{{ stats.categories }}</div>
                        <div class="text-surface-600 dark:text-surface-400">Categories</div>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <i class="pi pi-tags text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="card p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-surface-900 dark:text-surface-0">{{ stats.articles }}</div>
                        <div class="text-surface-600 dark:text-surface-400">Articles</div>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                        <i class="pi pi-file-text text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="card p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-surface-900 dark:text-surface-0">{{ stats.orders }}</div>
                        <div class="text-surface-600 dark:text-surface-400">Orders</div>
                    </div>
                    <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                        <i class="pi pi-shopping-cart text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Products -->
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="font-semibold text-surface-900 dark:text-surface-0 m-0">Recent Products</h5>
                        <Button label="View All" text @click="$router.push('/admin/products')" />
                    </div>
                </div>
                <div class="space-y-3">
                    <div v-for="product in recentProducts" :key="product.id" class="flex items-center justify-between p-3 border border-surface-200 dark:border-surface-700 rounded-lg">
                        <div>
                            <div class="font-medium text-surface-900 dark:text-surface-0">{{ product.name }}</div>
                            <div class="text-sm text-surface-600 dark:text-surface-400">Rp {{ formatPrice(product.price) }}</div>
                        </div>
                        <Badge :value="product.status" :severity="product.status === 'active' ? 'success' : 'danger'" />
                    </div>
                </div>
            </div>

            <!-- Recent Articles -->
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="font-semibold text-surface-900 dark:text-surface-0 m-0">Recent Articles</h5>
                        <Button label="View All" text @click="$router.push('/admin/articles')" />
                    </div>
                </div>
                <div class="space-y-3">
                    <div v-for="article in recentArticles" :key="article.id" class="flex items-center justify-between p-3 border border-surface-200 dark:border-surface-700 rounded-lg">
                        <div>
                            <div class="font-medium text-surface-900 dark:text-surface-0">{{ article.title }}</div>
                            <div class="text-sm text-surface-600 dark:text-surface-400">{{ formatDate(article.created_at) }}</div>
                        </div>
                        <Badge v-if="article.is_featured" value="Featured" severity="success" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <h5 class="font-semibold text-surface-900 dark:text-surface-0 mb-4">Quick Actions</h5>
            <div class="flex flex-wrap gap-3">
                <Button 
                    icon="pi pi-plus" 
                    label="Add Product" 
                    @click="$router.push('/admin/products')" 
                />
                <Button 
                    icon="pi pi-plus" 
                    label="Add Article" 
                    severity="secondary"
                    @click="$router.push('/admin/articles')" 
                />
                <Button 
                    icon="pi pi-plus" 
                    label="Add Category" 
                    severity="success"
                    @click="$router.push('/admin/categories')" 
                />
                <Button 
                    icon="pi pi-chart-bar" 
                    label="View Analytics" 
                    severity="info"
                    @click="$router.push('/admin/analytics')" 
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import AdminProductService from '@/service/AdminProductService.js';
import AdminArticleService from '@/service/AdminArticleService.js';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import { onMounted, ref } from 'vue';

// Data
const stats = ref({
    products: 0,
    categories: 0,
    articles: 0,
    orders: 0
});

const recentProducts = ref([]);
const recentArticles = ref([]);

// Methods
const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const loadStats = async () => {
    try {
        // Load products
        const productsResponse = await AdminProductService.getProducts();
        stats.value.products = productsResponse.data?.total || productsResponse.data?.data?.length || 0;
        
        // Get recent products (first 5)
        recentProducts.value = (productsResponse.data?.data || []).slice(0, 5);

        // Load articles
        const articlesResponse = await AdminArticleService.getArticles();
        stats.value.articles = articlesResponse.data?.total || articlesResponse.data?.data?.length || 0;
        
        // Get recent articles (first 5)
        recentArticles.value = (articlesResponse.data?.data || []).slice(0, 5);

        // TODO: Load categories and orders stats
        stats.value.categories = 8; // Placeholder
        stats.value.orders = 12; // Placeholder

    } catch (error) {
        // Failed to load dashboard stats - will show default values
    }
};

onMounted(() => {
    loadStats();
});
</script>

<style scoped>
.card-header {
    border-bottom: 1px solid var(--surface-border);
    padding-bottom: 1rem;
}
</style>
