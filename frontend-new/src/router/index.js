import AdminLayout from '@/layout/AdminLayout.vue';
import ShopLayout from '@/layout/ShopLayout.vue';
import AuthService from '@/service/AuthService.js';
import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        // Landing Page (public)
        {
            path: '/',
            name: 'landing',
            component: () => import('@/views/LandingPage.vue')
        },

        // Authentication routes
        {
            path: '/auth',
            children: [
                {
                    path: 'login',
                    name: 'login',
                    component: () => import('@/views/auth/Login.vue'),
                    beforeEnter: (to, from, next) => {
                        if (AuthService.isAuthenticated()) {
                            const user = AuthService.getUser();
                            if (user?.role === 'admin') {
                                next('/admin');
                            } else {
                                next('/shop');
                            }
                        } else {
                            next();
                        }
                    }
                },
                {
                    path: 'register',
                    name: 'register',
                    component: () => import('@/views/auth/Register.vue'),
                    beforeEnter: (to, from, next) => {
                        if (AuthService.isAuthenticated()) {
                            next('/shop');
                        } else {
                            next();
                        }
                    }
                }
            ]
        },

        // Shop routes (customer area)
        {
            path: '/shop',
            component: ShopLayout,
            beforeEnter: (to, from, next) => {
                const user = AuthService.getUser();
                if (user?.role === 'admin') {
                    next('/admin');
                } else {
                    next();
                }
            },
            children: [
                {
                    path: '',
                    name: 'shop-products',
                    component: () => import('@/views/Products.vue')
                },
                {
                    path: 'products/:id',
                    name: 'shop-product-detail',
                    component: () => import('@/views/ProductDetail.vue')
                },
                {
                    path: 'articles',
                    name: 'shop-articles',
                    component: () => import('@/views/Articles.vue')
                },
                {
                    path: 'articles/:id',
                    name: 'shop-article-detail',
                    component: () => import('@/views/ArticleDetail.vue')
                },
                {
                    path: 'cart',
                    name: 'shop-cart',
                    component: () => import('@/views/Cart.vue'),
                    meta: { requiresAuth: true }
                },
                {
                    path: 'orders',
                    name: 'shop-orders',
                    component: () => import('@/views/Orders.vue'),
                    meta: { requiresAuth: true }
                }
            ]
        },

        // Admin routes
        {
            path: '/admin',
            component: AdminLayout,
            meta: { requiresAuth: true, requiresAdmin: true },
            children: [
                {
                    path: '',
                    name: 'admin-dashboard',
                    component: () => import('@/views/admin/AdminDashboard.vue')
                },
                {
                    path: 'products',
                    name: 'admin-products',
                    component: () => import('@/views/admin/AdminProducts.vue')
                },
                {
                    path: 'categories',
                    name: 'admin-categories',
                    component: () => import('@/views/admin/AdminCategories.vue')
                },
                {
                    path: 'articles',
                    name: 'admin-articles',
                    component: () => import('@/views/admin/AdminArticles.vue')
                },
                {
                    path: 'orders',
                    name: 'admin-orders',
                    component: () => import('@/views/admin/AdminOrders.vue')
                }
            ]
        },

        // 404 Route
        {
            path: '/:pathMatch(.*)*',
            name: 'notfound',
            component: () => import('@/views/NotFound.vue')
        }
    ]
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const isAuthenticated = AuthService.isAuthenticated();
    const user = AuthService.getUser();

    // Check if route requires authentication
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isAuthenticated) {
            next('/auth/login');
            return;
        }
    }

    // Check if route requires admin role
    if (to.matched.some(record => record.meta.requiresAdmin)) {
        if (!isAuthenticated) {
            next('/auth/login');
            return;
        }
        if (user?.role !== 'admin') {
            next('/shop');
            return;
        }
    }

    next();
});

export default router;
