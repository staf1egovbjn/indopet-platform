<script setup>
import { ref } from 'vue';
import AppMenuItem from './AppMenuItem.vue';

import { AuthService } from '@/service/AuthService';
import { ref, onMounted } from 'vue';

const user = ref(null);
const model = ref([]);

const adminMenu = [
    {
        label: 'Main',
        items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/admin' }]
    },
    {
        label: 'Management',
        items: [
            { label: 'Products', icon: 'pi pi-fw pi-shopping-bag', to: '/admin/products' },
            { label: 'Categories', icon: 'pi pi-fw pi-tag', to: '/admin/categories' },
            { label: 'Orders', icon: 'pi pi-fw pi-shopping-cart', to: '/admin/orders' },
            { label: 'Users', icon: 'pi pi-fw pi-users', to: '/admin/users' }
        ]
    },
    {
        label: 'Content',
        items: [{ label: 'Articles', icon: 'pi pi-fw pi-file-text', to: '/admin/articles' }]
    }
];

const customerMenu = [
    {
        label: 'Shop',
        items: [
            { label: 'Products', icon: 'pi pi-fw pi-shopping-bag', to: '/shop' },
            { label: 'Categories', icon: 'pi pi-fw pi-tag', to: '/categories' },
            { label: 'Cart', icon: 'pi pi-fw pi-shopping-cart', to: '/cart' }
        ]
    },
    {
        label: 'Content',
        items: [{ label: 'Articles', icon: 'pi pi-fw pi-file-text', to: '/shop/articles' }]
    },
    {
        label: 'User',
        items: [
            { label: 'Profile', icon: 'pi pi-fw pi-user', to: '/profile' },
            { label: 'My Orders', icon: 'pi pi-fw pi-shopping-cart', to: '/orders' }
        ]
    }
];

onMounted(async () => {
    if (AuthService.isAuthenticated()) {
        user.value = await AuthService.getCurrentUser();
        model.value = user.value?.role === 'admin' ? adminMenu : customerMenu;
    } else {
        model.value = customerMenu;
    }
});
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>
