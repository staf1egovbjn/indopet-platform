<template>
    <div class="layout-wrapper layout-static">
        <div class="layout-sidebar">
            <div class="sidebar-header">
                <router-link to="/admin" class="layout-topbar-logo">
                    <span>IndoPet Admin</span>
                </router-link>
            </div>
            
            <div class="layout-menu-container">
                <ul class="layout-menu">
                    <li class="menu-item">
                        <router-link to="/admin" class="menu-link">
                            <i class="pi pi-home"></i>
                            <span>Dashboard</span>
                        </router-link>
                    </li>
                    <li class="menu-item">
                        <router-link to="/admin/products" class="menu-link">
                            <i class="pi pi-shopping-bag"></i>
                            <span>Products</span>
                        </router-link>
                    </li>
                    <li class="menu-item">
                        <router-link to="/admin/categories" class="menu-link">
                            <i class="pi pi-tags"></i>
                            <span>Categories</span>
                        </router-link>
                    </li>
                    <li class="menu-item">
                        <router-link to="/admin/articles" class="menu-link">
                            <i class="pi pi-file-text"></i>
                            <span>Articles</span>
                        </router-link>
                    </li>
                    <li class="menu-item">
                        <router-link to="/admin/orders" class="menu-link">
                            <i class="pi pi-shopping-cart"></i>
                            <span>Orders</span>
                        </router-link>
                    </li>
                    <li class="menu-item">
                        <a @click="logout" class="menu-link cursor-pointer">
                            <i class="pi pi-sign-out"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="layout-main-container">
            <div class="layout-topbar">
                <div class="layout-topbar-start">
                    <button @click="onMenuToggle" class="layout-menu-button p-button p-button-text p-button-rounded">
                        <i class="pi pi-bars"></i>
                    </button>
                </div>
                <div class="layout-topbar-end">
                    <div class="layout-topbar-menu">
                        <button class="p-button p-button-text p-button-rounded">
                            <i class="pi pi-user"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="layout-main">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const onMenuToggle = () => {
    // Menu toggle functionality
};

const logout = () => {
    // Remove auth token
    localStorage.removeItem('auth_token');
    
    // Redirect to login
    router.push('/auth/login');
};
</script>

<style scoped>
.layout-wrapper {
    display: flex;
    height: 100vh;
}

.layout-sidebar {
    width: 250px;
    background: var(--surface-card);
    border-right: 1px solid var(--surface-border);
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 1rem;
    border-bottom: 1px solid var(--surface-border);
}

.layout-topbar-logo {
    display: flex;
    align-items: center;
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary-color);
    text-decoration: none;
}

.layout-menu-container {
    flex: 1;
    overflow-y: auto;
}

.layout-menu {
    list-style: none;
    margin: 0;
    padding: 1rem 0;
}

.menu-item {
    margin: 0.5rem 0;
}

.menu-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    color: var(--text-color);
    text-decoration: none;
    transition: background-color 0.15s;
}

.menu-link:hover, .menu-link.router-link-active {
    background-color: var(--surface-hover);
    color: var(--primary-color);
}

.menu-link i {
    margin-right: 0.75rem;
    width: 1rem;
}

.layout-main-container {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.layout-topbar {
    height: 4rem;
    background: var(--surface-card);
    border-bottom: 1px solid var(--surface-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1rem;
}

.layout-main {
    flex: 1;
    padding: 2rem;
    overflow-y: auto;
    background: var(--surface-ground);
}
</style>
