<script setup>
import { ref } from 'vue';
import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Main',
        items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/' }]
    },
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
        items: [{ label: 'Profile', icon: 'pi pi-fw pi-user', to: '/profile' }]
    }
]);
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
