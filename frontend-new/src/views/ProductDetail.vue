<template>
    <div class="space-y-6">
        <div v-if="loading" class="flex justify-center items-center py-12">
            <ProgressSpinner />
        </div>

        <div v-else-if="product" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div class="card">
                <div class="w-full h-96 bg-surface-100 dark:bg-surface-800 rounded-lg flex items-center justify-center mb-4">
                    <i class="pi pi-image text-6xl text-surface-400"></i>
                </div>
                <div class="grid grid-cols-4 gap-2">
                    <div v-for="i in 4" :key="i" class="aspect-square bg-surface-100 dark:bg-surface-800 rounded-lg flex items-center justify-center cursor-pointer">
                        <i class="pi pi-image text-surface-400"></i>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Breadcrumb -->
                <Breadcrumb :home="breadcrumbHome" :model="breadcrumbItems" />

                <!-- Product Details -->
                <div class="card">
                    <div class="mb-4">
                        <Badge v-if="product.is_featured" value="Featured" severity="success" class="mb-3" />
                        <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0 mb-3">{{ product.name }}</h1>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center">
                                <Rating :modelValue="4" readonly :cancel="false" />
                                <span class="ml-2 text-sm text-surface-600">(24 reviews)</span>
                            </div>
                            <span class="text-sm text-surface-500">SKU: {{ product.id }}</span>
                        </div>
                        <div class="text-3xl font-bold text-primary-600 mb-4">Rp {{ formatPrice(product.price) }}</div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">Description</h3>
                        <p class="text-surface-600 dark:text-surface-400 leading-relaxed">{{ product.description }}</p>
                    </div>

                    <!-- Quantity and Add to Cart -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Quantity</label>
                            <div class="flex items-center gap-3">
                                <Button icon="pi pi-minus" severity="secondary" outlined @click="quantity > 1 && quantity--" :disabled="quantity <= 1" />
                                <InputNumber v-model="quantity" :min="1" :max="10" showButtons buttonLayout="horizontal" class="w-24" />
                                <Button icon="pi pi-plus" severity="secondary" outlined @click="quantity < 10 && quantity++" :disabled="quantity >= 10" />
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <Button label="Add to Cart" icon="pi pi-shopping-cart" class="flex-1" @click="addToCart" />
                            <Button icon="pi pi-heart" severity="secondary" outlined @click="addToWishlist" />
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="mt-6 pt-6 border-t border-surface-200 dark:border-surface-700">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium">Category:</span>
                                <span class="ml-2 text-surface-600">{{ product.category?.name || 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Stock:</span>
                                <span class="ml-2 text-surface-600">{{ product.stock || 'Available' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div v-else class="text-center py-12">
            <i class="pi pi-exclamation-triangle text-6xl text-surface-400 mb-4"></i>
            <h2 class="text-2xl font-semibold text-surface-900 dark:text-surface-0 mb-2">Product Not Found</h2>
            <p class="text-surface-600 dark:text-surface-400 mb-6">The product you're looking for doesn't exist or has been removed.</p>
            <Button label="Back to Products" icon="pi pi-arrow-left" @click="$router.push('/products')" />
        </div>

        <!-- Related Products -->
        <div v-if="relatedProducts.length > 0" class="card">
            <h3 class="text-xl font-semibold text-surface-900 dark:text-surface-0 mb-6">Related Products</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div
                    v-for="relatedProduct in relatedProducts"
                    :key="relatedProduct.id"
                    class="border border-surface-200 dark:border-surface-700 rounded-lg p-4 cursor-pointer hover:shadow-md transition-shadow"
                    @click="$router.push(`/products/${relatedProduct.id}`)"
                >
                    <div class="w-full h-32 bg-surface-100 dark:bg-surface-800 rounded-lg mb-3 flex items-center justify-center">
                        <i class="pi pi-image text-2xl text-surface-400"></i>
                    </div>
                    <h6 class="font-medium text-surface-900 dark:text-surface-0 mb-1 line-clamp-1">{{ relatedProduct.name }}</h6>
                    <p class="text-primary-600 font-semibold">Rp {{ formatPrice(relatedProduct.price) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import IndoPetProductService from '@/service/IndoPetProductService.js';
import Badge from 'primevue/badge';
import Breadcrumb from 'primevue/breadcrumb';
import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import ProgressSpinner from 'primevue/progressspinner';
import Rating from 'primevue/rating';
import { onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const product = ref(null);
const relatedProducts = ref([]);
const loading = ref(true);
const quantity = ref(1);

const breadcrumbHome = ref({ icon: 'pi pi-home', to: '/' });
const breadcrumbItems = ref([{ label: 'Products', to: '/products' }, { label: 'Product Detail' }]);

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
};

const addToCart = () => {
    console.log('Adding to cart:', product.value, 'quantity:', quantity.value);
    // TODO: Implement add to cart functionality
};

const addToWishlist = () => {
    console.log('Adding to wishlist:', product.value);
    // TODO: Implement wishlist functionality
};

const loadProduct = async (productId) => {
    try {
        loading.value = true;
        const response = await IndoPetProductService.getProductById(productId);
        product.value = response.data;

        // Update breadcrumb with product name
        breadcrumbItems.value[1].label = product.value?.name || 'Product Detail';

        // Load related products (mock for now)
        loadRelatedProducts();
    } catch (error) {
        console.error('Failed to load product:', error);
        product.value = null;
    } finally {
        loading.value = false;
    }
};

const loadRelatedProducts = async () => {
    try {
        // Get products from same category
        const response = await IndoPetProductService.getProducts();
        const allProducts = response.data || [];

        // Filter products from same category, exclude current product
        relatedProducts.value = allProducts.filter((p) => p.category_id === product.value?.category_id && p.id !== product.value?.id).slice(0, 4);
    } catch (error) {
        console.error('Failed to load related products:', error);
        relatedProducts.value = [];
    }
};

// Watch for route changes
watch(
    () => route.params.id,
    (newId) => {
        if (newId) {
            loadProduct(newId);
        }
    }
);

onMounted(() => {
    const productId = route.params.id;
    if (productId) {
        loadProduct(productId);
    }
});
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
