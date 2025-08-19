<template>
    <div class="flex items-center justify-center min-h-screen bg-surface-50 dark:bg-surface-900">
        <div class="text-center">
            <div class="mb-8">
                <i class="pi pi-exclamation-triangle text-8xl text-orange-500"></i>
            </div>
            <h1 class="text-4xl font-bold text-surface-900 dark:text-surface-0 mb-4">Page Not Found</h1>
            <p class="text-lg text-surface-600 dark:text-surface-400 mb-8">
                The page you're looking for doesn't exist or has been moved.
            </p>
            <div class="space-x-4">
                <Button 
                    label="Go Home" 
                    icon="pi pi-home" 
                    @click="$router.push('/')"
                />
                <Button 
                    label="Go to Shop" 
                    icon="pi pi-shopping-bag" 
                    severity="secondary"
                    @click="$router.push('/shop')"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import Button from 'primevue/button';
</script>
