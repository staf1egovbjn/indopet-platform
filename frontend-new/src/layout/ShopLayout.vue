<template>
    <div class="flex min-h-screen bg-surface-50 dark:bg-surface-900">
        <!-- Sidebar -->
        <div class="w-64 bg-white dark:bg-surface-800 shadow-lg">
            <!-- Logo -->
            <div class="p-6 border-b border-surface-200 dark:border-surface-700">
                <div class="flex items-center">
                    <i class="pi pi-heart text-2xl text-primary-600 mr-2"></i>
                    <span class="text-xl font-bold text-surface-900 dark:text-surface-0">IndoPet Shop</span>
                </div>
            </div>

            <!-- User Info -->
            <div v-if="user" class="p-4 border-b border-surface-200 dark:border-surface-700">
                <div class="flex items-center mb-2">
                    <div class="bg-primary-100 dark:bg-primary-900 rounded-full w-10 h-10 flex items-center justify-center mr-3">
                        <i class="pi pi-user text-primary-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-surface-900 dark:text-surface-0">{{ user.name }}</p>
                        <p class="text-sm text-surface-500 capitalize">{{ user.role }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4">
                <ul class="space-y-2">
                    <!-- Customer Navigation -->
                    <li>
                        <router-link
                            to="/shop"
                            class="flex items-center px-4 py-2 text-surface-700 dark:text-surface-300 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                            active-class="bg-primary-50 dark:bg-primary-900 text-primary-600"
                        >
                            <i class="pi pi-shopping-bag mr-3"></i>
                            Products
                        </router-link>
                    </li>
                    <li>
                        <router-link
                            to="/shop/articles"
                            class="flex items-center px-4 py-2 text-surface-700 dark:text-surface-300 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                            active-class="bg-primary-50 dark:bg-primary-900 text-primary-600"
                        >
                            <i class="pi pi-book mr-3"></i>
                            Articles
                        </router-link>
                    </li>
                    <li v-if="user">
                        <router-link
                            to="/shop/cart"
                            class="flex items-center px-4 py-2 text-surface-700 dark:text-surface-300 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                            active-class="bg-primary-50 dark:bg-primary-900 text-primary-600"
                        >
                            <i class="pi pi-shopping-cart mr-3"></i>
                            Cart
                            <Badge v-if="cartCount > 0" :value="cartCount" severity="danger" class="ml-auto" />
                        </router-link>
                    </li>
                    <li v-if="user">
                        <router-link
                            to="/shop/orders"
                            class="flex items-center px-4 py-2 text-surface-700 dark:text-surface-300 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                            active-class="bg-primary-50 dark:bg-primary-900 text-primary-600"
                        >
                            <i class="pi pi-list mr-3"></i>
                            My Orders
                        </router-link>
                    </li>

                    <!-- Divider -->
                    <li class="py-2">
                        <div class="border-t border-surface-200 dark:border-surface-700"></div>
                    </li>

                    <!-- Admin Link (for admins only) -->
                    <li v-if="user?.role === 'admin'">
                        <router-link
                            to="/admin"
                            class="flex items-center px-4 py-2 text-surface-700 dark:text-surface-300 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                        >
                            <i class="pi pi-cog mr-3"></i>
                            Admin Panel
                        </router-link>
                    </li>

                    <!-- Auth Links -->
                    <li v-if="!user">
                        <router-link
                            to="/auth/login"
                            class="flex items-center px-4 py-2 text-surface-700 dark:text-surface-300 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                        >
                            <i class="pi pi-sign-in mr-3"></i>
                            Login
                        </router-link>
                    </li>
                    <li v-if="!user">
                        <router-link
                            to="/auth/register"
                            class="flex items-center px-4 py-2 text-surface-700 dark:text-surface-300 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                        >
                            <i class="pi pi-user-plus mr-3"></i>
                            Register
                        </router-link>
                    </li>
                    
                    <!-- Logout -->
                    <li v-if="user">
                        <button
                            @click="logout"
                            class="w-full flex items-center px-4 py-2 text-surface-700 dark:text-surface-300 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                        >
                            <i class="pi pi-sign-out mr-3"></i>
                            Logout
                        </button>
                    </li>

                    <!-- Home Link -->
                    <li class="pt-4">
                        <router-link
                            to="/"
                            class="flex items-center px-4 py-2 text-surface-500 dark:text-surface-400 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                        >
                            <i class="pi pi-home mr-3"></i>
                            Back to Home
                        </router-link>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <header class="bg-white dark:bg-surface-800 shadow-sm border-b border-surface-200 dark:border-surface-700">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-surface-900 dark:text-surface-0">
                                {{ pageTitle }}
                            </h1>
                            <p v-if="pageSubtitle" class="text-surface-600 dark:text-surface-400">{{ pageSubtitle }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <Button
                                v-if="!user"
                                label="Login"
                                icon="pi pi-sign-in"
                                severity="secondary"
                                outlined
                                @click="$router.push('/auth/login')"
                            />
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                <router-view />
            </main>
        </div>
    </div>
</template>

<script setup>
import AuthService from '@/service/AuthService.js';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const router = useRouter();
const route = useRoute();
const toast = useToast();

// State
const user = ref(null);
const cartCount = ref(0);

// Computed
const pageTitle = computed(() => {
    const titles = {
        '/shop': 'Products',
        '/shop/articles': 'Articles',
        '/shop/cart': 'Shopping Cart',
        '/shop/orders': 'My Orders'
    };
    return titles[route.path] || 'Shop';
});

const pageSubtitle = computed(() => {
    const subtitles = {
        '/shop': 'Discover amazing products for your pets',
        '/shop/articles': 'Learn more about pet care',
        '/shop/cart': 'Review your items before checkout',
        '/shop/orders': 'Track your order history'
    };
    return subtitles[route.path] || '';
});

// Methods
const logout = async () => {
    try {
        await AuthService.logout();
        toast.add({
            severity: 'success',
            summary: 'Logged Out',
            detail: 'You have been successfully logged out',
            life: 3000
        });
        router.push('/');
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Logout Error',
            detail: 'Failed to logout properly',
            life: 3000
        });
    }
};

const loadUserData = async () => {
    if (AuthService.isAuthenticated()) {
        user.value = AuthService.getUser();
        if (!user.value) {
            // Try to fetch user data from server
            user.value = await AuthService.getCurrentUser();
        }
    }
};

// Lifecycle
onMounted(() => {
    loadUserData();
});

// Watch for route changes to update user data
watch(() => route.path, () => {
    loadUserData();
});
</script>
