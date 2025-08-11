<template>
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h5 class="text-xl font-semibold text-surface-900 dark:text-surface-0">Latest Articles</h5>
            <router-link to="/articles" class="text-primary-500 hover:text-primary-600 font-medium"> View All Articles </router-link>
        </div>
        <div class="space-y-4" v-if="!loading && articles.length > 0">
            <div v-for="article in articles" :key="article.id" class="flex gap-4 p-4 border border-surface-200 dark:border-surface-700 rounded-lg hover:shadow-md transition-shadow cursor-pointer" @click="goToArticle(article)">
                <div class="w-20 h-20 bg-surface-100 dark:bg-surface-800 rounded-lg flex-shrink-0 flex items-center justify-center">
                    <i class="pi pi-file-text text-2xl text-surface-400"></i>
                </div>
                <div class="flex-1">
                    <h6 class="font-medium text-surface-900 dark:text-surface-0 mb-2 line-clamp-1">{{ article.title }}</h6>
                    <p class="text-sm text-surface-600 dark:text-surface-400 mb-3 line-clamp-2">{{ article.excerpt }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-surface-500">{{ formatDate(article.created_at) }}</span>
                        <Button label="Read More" size="small" text />
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="loading" class="flex justify-center items-center py-8">
            <ProgressSpinner />
        </div>
        <div v-else class="text-center py-8">
            <i class="pi pi-file-text text-4xl text-surface-400 mb-4"></i>
            <p class="text-surface-600 dark:text-surface-400">No articles available</p>
        </div>
    </div>
</template>

<script setup>
import IndoPetArticleService from '@/service/IndoPetArticleService.js';
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const articles = ref([]);
const loading = ref(true);

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const goToArticle = (article) => {
    // TODO: Navigate to article detail
    console.log('Navigate to article:', article);
    // router.push(`/articles/${article.id}`);
};

const loadFeaturedArticles = async () => {
    try {
        loading.value = true;
        const response = await IndoPetArticleService.getFeaturedArticles();
        articles.value = response.data || [];
    } catch (error) {
        console.error('Failed to load featured articles:', error);
        articles.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadFeaturedArticles();
});
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
