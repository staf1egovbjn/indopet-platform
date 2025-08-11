import AppLayout from '@/layout/AppLayout.vue';
import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: AppLayout,
            children: [
                {
                    path: '/',
                    name: 'dashboard',
                    component: () => import('@/views/Dashboard.vue')
                },
                {
                    path: '/products',
                    name: 'products',
                    component: () => import('@/views/Products.vue')
                },
                {
                    path: '/products/:id',
                    name: 'product-detail',
                    component: () => import('@/views/ProductDetail.vue')
                },
                {
                    path: '/categories',
                    name: 'categories',
                    component: () => import('@/views/Categories.vue')
                },
                {
                    path: '/articles',
                    name: 'articles',
                    component: () => import('@/views/Articles.vue')
                },
                {
                    path: '/articles/:id',
                    name: 'article-detail',
                    component: () => import('@/views/ArticleDetail.vue')
                },
                {
                    path: '/cart',
                    name: 'cart',
                    component: () => import('@/views/Cart.vue')
                },
                {
                    path: '/profile',
                    name: 'profile',
                    component: () => import('@/views/Profile.vue')
                }
            ]
        },
        {
            path: '/landing',
            name: 'landing',
            component: () => import('@/views/pages/Landing.vue')
        },
        {
            path: '/auth/login',
            name: 'login',
            component: () => import('@/views/pages/auth/Login.vue')
        },
        {
            path: '/auth/register',
            name: 'register',
            component: () => import('@/views/pages/auth/Register.vue')
        },
        {
            path: '/pages/notfound',
            name: 'notfound',
            component: () => import('@/views/pages/NotFound.vue')
        },
        {
            path: '/:pathMatch(.*)*',
            redirect: '/pages/notfound'
        }
    ]
});

export default router;
