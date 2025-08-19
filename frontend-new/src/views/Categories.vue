<template>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0 mb-2">Categories</h1>
                    <p class="text-surface-600 dark:text-surface-400">Browse products by category</p>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="card">
            <div v-if="loading" class="flex justify-center items-center py-8">
                <ProgressSpinner />
            </div>

            <div v-else-if="categories.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="p-6 border border-surface-200 dark:border-surface-700 rounded-lg text-center cursor-pointer hover:shadow-lg hover:border-primary-500 transition-all group"
                    @click="goToCategory(category)"
                >
                    <div class="w-16 h-16 mx-auto mb-4 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center group-hover:bg-primary-200 dark:group-hover:bg-primary-800 transition-colors">
                        <i :class="getCategoryIcon(category.name)" class="text-2xl text-primary-600 dark:text-primary-400"></i>
                    </div>
                    <h6 class="text-xl font-medium text-surface-900 dark:text-surface-0 mb-2">{{ category.name }}</h6>
                    <p class="text-surface-600 dark:text-surface-400 mb-3">{{ category.description }}</p>
                    <div class="text-primary-600 font-medium">
                        {{ category.products_count || 0 }} Products
                        <i class="pi pi-arrow-right ml-2"></i>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-8">
                <i class="pi pi-tag text-6xl text-surface-400 mb-4"></i>
                <h3 class="text-xl font-medium text-surface-900 dark:text-surface-0 mb-2">No Categories Found</h3>
                <p class="text-surface-600 dark:text-surface-400">Categories will appear here when available</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import IndoPetCategoryService from '@/service/IndoPetCategoryService.js';
import ProgressSpinner from 'primevue/progressspinner';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const categories = ref([]);
const loading = ref(true);

const getCategoryIcon = (categoryName) => {
    const iconMap = {
        Makanan: 'pi pi-shopping-bag',
        'Obat & Vitamin': 'pi pi-heart',
        Grooming: 'pi pi-sparkles',
        Aksesoris: 'pi pi-star',
        Kandang: 'pi pi-home'
    };
    return iconMap[categoryName] || 'pi pi-tag';
};

const goToCategory = (category) => {
    router.push(`/shop?category=${category.id}`);
};

const loadCategories = async () => {
    try {
        loading.value = true;
        const response = await IndoPetCategoryService.getCategories();
        categories.value = response.data || [];
    } catch (error) {
        console.error('Failed to load categories:', error);
        categories.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadCategories();
});
</script>
