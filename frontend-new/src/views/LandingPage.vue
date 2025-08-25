<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 dark:from-surface-800 dark:to-surface-900">
        <!-- Navigation Header -->
        <nav class="bg-white dark:bg-surface-800 shadow-lg sticky top-0 z-50">
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="pi pi-heart text-3xl text-primary-600 mr-3"></i>
                        <span class="text-2xl font-bold text-surface-900 dark:text-surface-0">IndoPet</span>
                    </div>
                    <div class="hidden md:flex space-x-8">
                        <a href="#home" class="text-surface-700 dark:text-surface-300 hover:text-primary-600 transition-colors">Home</a>
                        <a href="#products" class="text-surface-700 dark:text-surface-300 hover:text-primary-600 transition-colors">Products</a>
                        <a href="#articles" class="text-surface-700 dark:text-surface-300 hover:text-primary-600 transition-colors">Articles</a>
                        <a href="#about" class="text-surface-700 dark:text-surface-300 hover:text-primary-600 transition-colors">About</a>
                        <a href="#contact" class="text-surface-700 dark:text-surface-300 hover:text-primary-600 transition-colors">Contact</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <template v-if="!isAuthenticated">
                            <Button 
                                label="Login" 
                                icon="pi pi-sign-in" 
                                severity="secondary" 
                                outlined
                                @click="goToLogin"
                            />
                        </template>
                        <template v-else>
                            <span class="text-surface-600 dark:text-surface-400 mr-2">Welcome, {{ user?.name }}</span>
                            <Button 
                                :label="user?.role === 'admin' ? 'Go to Dashboard' : 'Shop Now'"
                                :icon="user?.role === 'admin' ? 'pi pi-th-large' : 'pi pi-shopping-bag'"
                                severity="primary"
                                @click="goToShop"
                            />
                        </template>
                        <template v-if="!isAuthenticated">
                            <Button 
                                label="Shop Now" 
                                icon="pi pi-shopping-bag" 
                                @click="goToShop"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section id="home" class="py-20">
            <div class="container mx-auto px-6">
                <div class="flex flex-col lg:flex-row items-center">
                    <div class="lg:w-1/2 mb-12 lg:mb-0">
                        <h1 class="text-5xl lg:text-6xl font-bold text-surface-900 dark:text-surface-0 mb-6">
                            Everything Your
                            <span class="text-primary-600">Pet Needs</span>
                        </h1>
                        <p class="text-xl text-surface-600 dark:text-surface-400 mb-8 leading-relaxed">
                            From premium food to toys and accessories, find everything to keep your furry friends happy and healthy.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <Button 
                                label="Shop Products" 
                                icon="pi pi-shopping-cart" 
                                size="large"
                                class="px-8 py-3"
                                @click="goToShop"
                            />
                            <Button 
                                label="Read Articles" 
                                icon="pi pi-book" 
                                severity="secondary" 
                                outlined 
                                size="large"
                                class="px-8 py-3"
                                @click="goToArticles"
                            />
                        </div>
                    </div>
                    <div class="lg:w-1/2">
                        <div class="relative">
                            <div class="bg-primary-100 dark:bg-primary-900 rounded-full w-96 h-96 mx-auto flex items-center justify-center">
                                <i class="pi pi-heart text-8xl text-primary-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-white dark:bg-surface-800">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-surface-900 dark:text-surface-0 mb-4">
                        Why Choose IndoPet?
                    </h2>
                    <p class="text-xl text-surface-600 dark:text-surface-400">
                        We provide the best products and care for your beloved pets
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-8 rounded-lg bg-surface-50 dark:bg-surface-700">
                        <div class="bg-primary-100 dark:bg-primary-900 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                            <i class="pi pi-shield text-3xl text-primary-600"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-surface-900 dark:text-surface-0 mb-4">Quality Assured</h3>
                        <p class="text-surface-600 dark:text-surface-400">
                            All our products are carefully selected and tested to ensure the highest quality for your pets.
                        </p>
                    </div>
                    <div class="text-center p-8 rounded-lg bg-surface-50 dark:bg-surface-700">
                        <div class="bg-green-100 dark:bg-green-900 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                            <i class="pi pi-truck text-3xl text-green-600"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-surface-900 dark:text-surface-0 mb-4">Fast Delivery</h3>
                        <p class="text-surface-600 dark:text-surface-400">
                            Quick and reliable delivery service to get your pet supplies right to your door.
                        </p>
                    </div>
                    <div class="text-center p-8 rounded-lg bg-surface-50 dark:bg-surface-700">
                        <div class="bg-orange-100 dark:bg-orange-900 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                            <i class="pi pi-users text-3xl text-orange-600"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-surface-900 dark:text-surface-0 mb-4">Expert Support</h3>
                        <p class="text-surface-600 dark:text-surface-400">
                            Our team of pet experts is always ready to help you choose the right products.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Preview -->
        <section id="products" class="py-20">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-surface-900 dark:text-surface-0 mb-4">
                        Featured Products
                    </h2>
                    <p class="text-xl text-surface-600 dark:text-surface-400">
                        Discover our most popular pet products
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="product in featuredProducts" :key="product.id" class="bg-white dark:bg-surface-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow cursor-pointer" @click="goToShop">
                        <div class="h-48 bg-surface-100 dark:bg-surface-700 flex items-center justify-center">
                            <i class="pi pi-image text-4xl text-surface-400"></i>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-surface-900 dark:text-surface-0 mb-2">{{ product.name }}</h3>
                            <p class="text-surface-600 dark:text-surface-400 mb-4">{{ product.description }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-primary-600">Rp {{ formatPrice(product.price) }}</span>
                                <Badge v-if="product.is_featured" value="Popular" severity="success" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-12">
                    <Button 
                        label="View All Products" 
                        icon="pi pi-arrow-right" 
                        iconPos="right"
                        size="large"
                        @click="goToShop"
                    />
                </div>
            </div>
        </section>

        <!-- Articles Preview -->
        <section id="articles" class="py-20 bg-white dark:bg-surface-800">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-surface-900 dark:text-surface-0 mb-4">
                        Latest Articles
                    </h2>
                    <p class="text-xl text-surface-600 dark:text-surface-400">
                        Learn how to take better care of your pets
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="article in featuredArticles" :key="article.id" class="bg-surface-50 dark:bg-surface-700 rounded-lg overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" @click="goToArticles">
                        <div class="h-48 bg-surface-200 dark:bg-surface-600 flex items-center justify-center">
                            <i class="pi pi-file-text text-4xl text-surface-400"></i>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-2">
                                <Badge v-if="article.is_featured" value="Featured" severity="success" class="mr-2" />
                                <span class="text-sm text-surface-500">{{ formatDate(article.created_at) }}</span>
                            </div>
                            <h3 class="text-xl font-semibold text-surface-900 dark:text-surface-0 mb-2">{{ article.title }}</h3>
                            <p class="text-surface-600 dark:text-surface-400">{{ article.excerpt }}</p>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-12">
                    <Button 
                        label="Read All Articles" 
                        icon="pi pi-arrow-right" 
                        iconPos="right"
                        size="large"
                        severity="secondary"
                        @click="goToArticles"
                    />
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-surface-900 dark:bg-surface-950 text-white py-16">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center mb-4">
                            <i class="pi pi-heart text-2xl text-primary-400 mr-2"></i>
                            <span class="text-xl font-bold">IndoPet</span>
                        </div>
                        <p class="text-surface-300 mb-4">
                            Your trusted partner for all pet care needs. Quality products, expert advice, and loving care.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="#home" class="text-surface-300 hover:text-primary-400 transition-colors">Home</a></li>
                            <li><a href="#products" class="text-surface-300 hover:text-primary-400 transition-colors">Products</a></li>
                            <li><a href="#articles" class="text-surface-300 hover:text-primary-400 transition-colors">Articles</a></li>
                            <li><a href="#about" class="text-surface-300 hover:text-primary-400 transition-colors">About</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Categories</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-surface-300 hover:text-primary-400 transition-colors">Dog Food</a></li>
                            <li><a href="#" class="text-surface-300 hover:text-primary-400 transition-colors">Cat Food</a></li>
                            <li><a href="#" class="text-surface-300 hover:text-primary-400 transition-colors">Toys</a></li>
                            <li><a href="#" class="text-surface-300 hover:text-primary-400 transition-colors">Accessories</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact</h4>
                        <div class="space-y-2 text-surface-300">
                            <p><i class="pi pi-phone mr-2"></i>+62 123 456 789</p>
                            <p><i class="pi pi-envelope mr-2"></i>info@indopet.com</p>
                            <p><i class="pi pi-map-marker mr-2"></i>Jakarta, Indonesia</p>
                        </div>
                    </div>
                </div>
                <div class="border-t border-surface-700 mt-12 pt-8 text-center text-surface-400">
                    <p>&copy; 2025 IndoPet. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import IndoPetProductService from '@/service/IndoPetProductService.js';
import IndoPetArticleService from '@/service/IndoPetArticleService.js';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import AuthService  from '@/service/AuthService';
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const isAuthenticated = ref(false);
const user = ref(null);

// Data
const featuredProducts = ref([]);
const featuredArticles = ref([]);

// Methods
const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const goToLogin = () => {
    router.push('/auth/login');
};

const goToShop = () => {
    if (isAuthenticated.value) {
        if (user.value?.role === 'admin') {
            router.push('/admin/products');
        } else {
            router.push('/shop');
        }
    } else {
        router.push('/auth/login');
    }
};

const goToArticles = () => {
    router.push('/shop/articles');
};

const loadFeaturedContent = async () => {
    try {
        // Load featured products
        const productsResponse = await IndoPetProductService.getProducts();
        const products = productsResponse.data?.data || [];
        featuredProducts.value = products.filter(p => p.is_featured).slice(0, 3);

        // Load featured articles
        const articlesResponse = await IndoPetArticleService.getArticles();
        const articles = articlesResponse.data?.data || [];
        featuredArticles.value = articles.filter(a => a.is_featured).slice(0, 3);
    } catch (error) {
        console.error('Failed to load featured content:', error);
    }
};

onMounted(async () => {
    if (AuthService.isAuthenticated()) {
        isAuthenticated.value = true;
        user.value = AuthService.getUser();
        if (!user.value) {
            user.value = await AuthService.getCurrentUser();
        }
    }
    loadFeaturedContent();
});
</script>

<style scoped>
html {
    scroll-behavior: smooth;
}
</style>
