<template>
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h5 class="text-xl font-semibold text-surface-900 dark:text-surface-0">Categories</h5>
            <router-link to="/categories" class="text-primary-500 hover:text-primary-600 font-medium"> Browse All </router-link>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4" v-if="!loading && categories.length > 0">
            <div
                v-for="category in categories"
                :key="category.id"
                class="p-4 border border-surface-200 dark:border-surface-700 rounded-lg text-center cursor-pointer hover:shadow-md hover:border-primary-500 transition-all"
                @click="goToCategory(category)"
            >
                <div class="w-12 h-12 mx-auto mb-3 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                    <i :class="getCategoryIcon(category.name)" class="text-xl text-primary-600 dark:text-primary-400"></i>
                </div>
                <h6 class="font-medium text-surface-900 dark:text-surface-0 mb-1">{{ category.name }}</h6>
                <p class="text-sm text-surface-600 dark:text-surface-400">{{ category.products_count || 0 }} items</p>
            </div>
        </div>
        <div v-else-if="loading" class="flex justify-center items-center py-8">
            <ProgressSpinner />
        </div>
        <div v-else class="text-center py-8">
            <i class="pi pi-tag text-4xl text-surface-400 mb-4"></i>
            <p class="text-surface-600 dark:text-surface-400">No categories available</p>
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
