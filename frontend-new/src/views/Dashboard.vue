<script setup>
import CategoriesWidget from '@/components/dashboard/CategoriesWidget.vue';
import FeaturedArticlesWidget from '@/components/dashboard/FeaturedArticlesWidget.vue';
import FeaturedProductsWidget from '@/components/dashboard/FeaturedProductsWidget.vue';
import IndoPetStatsWidget from '@/components/dashboard/IndoPetStatsWidget.vue';
</script>

<template>
    <div class="space-y-8">
        <!-- Welcome Section -->
        <div class="card p-6 bg-gradient-to-r from-primary-500 to-primary-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome to IndoPet Platform</h1>
                    <p class="text-primary-100">Your one-stop solution for pet care and shopping</p>
                </div>
                <div class="hidden md:block">
                    <i class="pi pi-heart text-6xl text-primary-200"></i>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <IndoPetStatsWidget />

        <!-- Categories Section -->
        <CategoriesWidget />

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Featured Products -->
            <div>
                <FeaturedProductsWidget />
            </div>

            <!-- Featured Articles -->
            <div>
                <FeaturedArticlesWidget />
            </div>
        </div>
    </div>
</template>
