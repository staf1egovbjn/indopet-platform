<template>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0 mb-2">Products</h1>
                    <p class="text-surface-600 dark:text-surface-400">Browse our complete collection of pet products</p>
                </div>
                <div class="flex gap-2">
                    <Button icon="pi pi-filter" label="Filter" severity="secondary" outlined @click="showFilterDialog = true" />
                    <Button :icon="viewMode === 'grid' ? 'pi pi-list' : 'pi pi-th-large'" :label="viewMode === 'grid' ? 'List View' : 'Grid View'" severity="secondary" @click="toggleViewMode" />
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-2">
                    <IconField iconPosition="left">
                        <InputIcon class="pi pi-search" />
                        <InputText v-model="searchQuery" placeholder="Search products..." class="w-full" @input="handleSearch" />
                    </IconField>
                </div>
                <div>
                    <Dropdown v-model="selectedCategory" :options="categories" optionLabel="name" optionValue="id" placeholder="All Categories" class="w-full" @change="handleCategoryFilter" showClear />
                </div>
            </div>
        </div>

        <!-- Products Grid/List -->
        <div class="card">
            <div v-if="loading" class="flex justify-center items-center py-8">
                <ProgressSpinner />
            </div>
            
            <div v-else>
                <!-- Grid View -->
                <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div v-for="(product, index) in filteredProducts" :key="product.id || index" class="border border-surface-200 dark:border-surface-700 rounded-lg p-4 hover:shadow-lg transition-all cursor-pointer" @click="goToProduct(product)">
                    <div class="mb-4">
                        <div class="w-full h-48 bg-surface-100 dark:bg-surface-800 rounded-lg mb-3 flex items-center justify-center">
                            <i class="pi pi-image text-4xl text-surface-400"></i>
                        </div>
                        <Badge v-if="product.is_featured" value="Featured" severity="success" class="mb-2" />
                        <h6 class="text-lg font-medium text-surface-900 dark:text-surface-0 mb-2">{{ product.name }}</h6>
                        <p class="text-surface-600 dark:text-surface-400 text-sm mb-3 line-clamp-2">{{ product.description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-primary-600">Rp {{ formatPrice(product.price) }}</span>
                            <div class="flex gap-2">
                                <Button icon="pi pi-heart" severity="secondary" outlined size="small" />
                                <Button icon="pi pi-shopping-cart" size="small" @click.stop="addToCart(product)" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- List View -->
                <div v-else-if="viewMode === 'list'" class="space-y-4">
                    <div v-for="(product, index) in filteredProducts" :key="product.id || index" class="flex gap-4 p-4 border border-surface-200 dark:border-surface-700 rounded-lg hover:shadow-md transition-all cursor-pointer" @click="goToProduct(product)">
                    <div class="w-24 h-24 bg-surface-100 dark:bg-surface-800 rounded-lg flex-shrink-0 flex items-center justify-center">
                        <i class="pi pi-image text-2xl text-surface-400"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <h6 class="text-lg font-medium text-surface-900 dark:text-surface-0">{{ product.name }}</h6>
                                    <Badge v-if="product.is_featured" value="Featured" severity="success" />
                                </div>
                                <p class="text-surface-600 dark:text-surface-400 mb-3 line-clamp-2">{{ product.description }}</p>
                                <div class="flex items-center gap-4">
                                    <span class="text-xl font-bold text-primary-600">Rp {{ formatPrice(product.price) }}</span>
                                    <span class="text-sm text-surface-500">Stock: {{ product.stock || 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <Button icon="pi pi-heart" severity="secondary" outlined size="small" />
                                <Button icon="pi pi-shopping-cart" size="small" @click.stop="addToCart(product)" />
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <!-- Empty State -->
                <div v-if="filteredProducts.length === 0" class="text-center py-8">
                    <i class="pi pi-shopping-bag text-6xl text-surface-400 mb-4"></i>
                    <h3 class="text-xl font-medium text-surface-900 dark:text-surface-0 mb-2">No Products Found</h3>
                    <p class="text-surface-600 dark:text-surface-400">Try adjusting your search or filter criteria</p>
                </div>
            </div>
        </div>

        <!-- Filter Dialog -->
        <Dialog v-model:visible="showFilterDialog" modal header="Filter Products" :style="{ width: '450px' }">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Price Range</label>
                    <div class="grid grid-cols-2 gap-2">
                        <InputNumber v-model="priceFilter.min" placeholder="Min Price" :useGrouping="false" />
                        <InputNumber v-model="priceFilter.max" placeholder="Max Price" :useGrouping="false" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Featured Products Only</label>
                    <Checkbox v-model="featuredFilter" :binary="true" />
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <Button label="Reset" severity="secondary" outlined @click="resetFilters" />
                    <Button label="Apply Filter" @click="applyFilters" />
                </div>
            </div>
        </Dialog>
    </div>
</template>

<script setup>
import IndoPetCategoryService from '@/service/IndoPetCategoryService.js';
import IndoPetProductService from '@/service/IndoPetProductService.js';
import CartService from '@/service/CartService.js';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import ProgressSpinner from 'primevue/progressspinner';
import { useToast } from 'primevue/usetoast';
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const toast = useToast();
const products = ref([]);
const categories = ref([]);
const loading = ref(true);
const viewMode = ref('grid');
const searchQuery = ref('');
const selectedCategory = ref(null);
const showFilterDialog = ref(false);

// Filter states
const priceFilter = ref({ min: null, max: null });
const featuredFilter = ref(false);

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
};

const toggleViewMode = () => {
    viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
};

const goToProduct = (product) => {
    router.push(`/shop/products/${product.id}`);
};

const addToCart = (product) => {
    toast.add({
        severity: 'success',
        summary: 'Added to Cart',
        detail: `${product.name} added to cart`,
        life: 3000
    });
};

const handleSearch = () => {
    // Search is handled by computed filteredProducts
};

const handleCategoryFilter = () => {
    // Filter is handled by computed filteredProducts
};

const filteredProducts = computed(() => {
    let filtered = products.value;

    // Search filter
    if (searchQuery.value) {
        filtered = filtered.filter((product) => product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || product.description.toLowerCase().includes(searchQuery.value.toLowerCase()));
    }

    // Category filter
    if (selectedCategory.value) {
        filtered = filtered.filter((product) => product.category_id === selectedCategory.value);
    }

    // Price filter
    if (priceFilter.value.min !== null) {
        filtered = filtered.filter((product) => product.price >= priceFilter.value.min);
    }
    if (priceFilter.value.max !== null) {
        filtered = filtered.filter((product) => product.price <= priceFilter.value.max);
    }

    // Featured filter
    if (featuredFilter.value) {
        filtered = filtered.filter((product) => product.is_featured);
    }

    return filtered;
});

const resetFilters = () => {
    priceFilter.value = { min: null, max: null };
    featuredFilter.value = false;
    selectedCategory.value = null;
    searchQuery.value = '';
};

const applyFilters = () => {
    showFilterDialog.value = false;
};

const loadProducts = async () => {
    try {
        loading.value = true;
        const response = await IndoPetProductService.getProducts();
        // Use response.data.data for paginated results
        products.value = response.data?.data || [];
    } catch (error) {
        console.error('Failed to load products:', error);
        products.value = [];
    } finally {
        loading.value = false;
    }
};

const loadCategories = async () => {
    try {
        const response = await IndoPetCategoryService.getCategories();
        categories.value = response.data || [];
    } catch (error) {
        console.error('Failed to load categories:', error);
        categories.value = [];
    }
};

onMounted(() => {
    loadProducts();
    loadCategories();
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
