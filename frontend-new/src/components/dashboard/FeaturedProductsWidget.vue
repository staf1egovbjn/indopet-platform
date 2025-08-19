<template>
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h5 class="text-xl font-semibold text-surface-900 dark:text-surface-0">Featured Products</h5>
            <router-link to="/shop" class="text-primary-500 hover:text-primary-600 font-medium"> View All Products </router-link>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" v-if="!loading && products.length > 0">
            <div v-for="product in products" :key="product.id" class="border border-surface-200 dark:border-surface-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="mb-4">
                    <div class="w-full h-32 bg-surface-100 dark:bg-surface-800 rounded-lg mb-3 flex items-center justify-center">
                        <i class="pi pi-image text-3xl text-surface-400"></i>
                    </div>
                    <h6 class="text-lg font-medium text-surface-900 dark:text-surface-0 mb-2">{{ product.name }}</h6>
                    <p class="text-surface-600 dark:text-surface-400 text-sm mb-3 line-clamp-2">{{ product.description }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-bold text-primary-600">Rp {{ formatPrice(product.price) }}</span>
                        <Button icon="pi pi-shopping-cart" size="small" @click="addToCart(product)" />
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="loading" class="flex justify-center items-center py-8">
            <ProgressSpinner />
        </div>
        <div v-else class="text-center py-8">
            <i class="pi pi-inbox text-4xl text-surface-400 mb-4"></i>
            <p class="text-surface-600 dark:text-surface-400">No featured products available</p>
        </div>
    </div>
</template>

<script setup>
import IndoPetProductService from '@/service/IndoPetProductService.js';
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';
import { onMounted, ref } from 'vue';

const products = ref([]);
const loading = ref(true);

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
};

const addToCart = (product) => {
    // Cart functionality to be implemented later
};

const loadFeaturedProducts = async () => {
    try {
        loading.value = true;
        const response = await IndoPetProductService.getFeaturedProducts();
        products.value = response.data || [];
    } catch (error) {
        console.error('Failed to load featured products:', error);
        products.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadFeaturedProducts();
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
