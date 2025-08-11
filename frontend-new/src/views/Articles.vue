<template>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0 mb-2">Articles</h1>
                    <p class="text-surface-600 dark:text-surface-400">Learn about pet care, health, and training tips</p>
                </div>
                <div class="flex gap-2">
                    <Button icon="pi pi-filter" label="Filter" severity="secondary" outlined @click="showFilterDialog = true" />
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mt-4">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText v-model="searchQuery" placeholder="Search articles..." class="w-full md:w-96" @input="handleSearch" />
                </IconField>
            </div>
        </div>

        <!-- Articles List -->
        <div class="card">
            <div v-if="loading" class="flex justify-center items-center py-8">
                <ProgressSpinner />
            </div>

            <div v-else-if="filteredArticles.length > 0" class="space-y-6">
                <div v-for="article in filteredArticles" :key="article.id" class="border border-surface-200 dark:border-surface-700 rounded-lg p-6 hover:shadow-lg transition-all cursor-pointer" @click="goToArticle(article)">
                    <div class="flex gap-6">
                        <div class="w-48 h-32 bg-surface-100 dark:bg-surface-800 rounded-lg flex-shrink-0 flex items-center justify-center">
                            <i class="pi pi-file-text text-4xl text-surface-400"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <Badge v-if="article.is_featured" value="Featured" severity="success" class="mb-2" />
                                    <h3 class="text-xl font-semibold text-surface-900 dark:text-surface-0 mb-2 line-clamp-2">
                                        {{ article.title }}
                                    </h3>
                                </div>
                                <div class="text-right text-sm text-surface-500 ml-4">
                                    <div>{{ formatDate(article.created_at) }}</div>
                                    <div class="mt-1">{{ article.read_time || '5' }} min read</div>
                                </div>
                            </div>
                            <p class="text-surface-600 dark:text-surface-400 mb-4 line-clamp-3">
                                {{ article.excerpt }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-surface-500">
                                        <i class="pi pi-eye mr-2"></i>
                                        {{ article.views || 0 }} views
                                    </span>
                                    <span v-if="article.category" class="text-sm text-primary-600">
                                        <i class="pi pi-tag mr-2"></i>
                                        {{ article.category }}
                                    </span>
                                </div>
                                <Button label="Read More" icon="pi pi-arrow-right" iconPos="right" text @click.stop="goToArticle(article)" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-8">
                <i class="pi pi-file-text text-6xl text-surface-400 mb-4"></i>
                <h3 class="text-xl font-medium text-surface-900 dark:text-surface-0 mb-2">No Articles Found</h3>
                <p class="text-surface-600 dark:text-surface-400">Try adjusting your search criteria</p>
            </div>
        </div>

        <!-- Filter Dialog -->
        <Dialog v-model:visible="showFilterDialog" modal header="Filter Articles" :style="{ width: '400px' }">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Featured Articles Only</label>
                    <Checkbox v-model="featuredFilter" :binary="true" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Sort By</label>
                    <Dropdown v-model="sortBy" :options="sortOptions" optionLabel="label" optionValue="value" placeholder="Choose sorting" class="w-full" />
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <Button label="Reset" severity="secondary" outlined @click="resetFilters" />
                    <Button label="Apply Filter" @click="applyFilters" />
                </div>
            </div>
        </Dialog>
    </div>
</template>

<script setup>
import IndoPetArticleService from '@/service/IndoPetArticleService.js';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import ProgressSpinner from 'primevue/progressspinner';
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const articles = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const showFilterDialog = ref(false);

// Filter states
const featuredFilter = ref(false);
const sortBy = ref('newest');

const sortOptions = [
    { label: 'Newest First', value: 'newest' },
    { label: 'Oldest First', value: 'oldest' },
    { label: 'Most Viewed', value: 'views' },
    { label: 'Title A-Z', value: 'title' }
];

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const goToArticle = (article) => {
    router.push(`/articles/${article.id}`);
};

const handleSearch = () => {
    // Search is handled by computed filteredArticles
};

const filteredArticles = computed(() => {
    let filtered = articles.value;

    // Search filter
    if (searchQuery.value) {
        filtered = filtered.filter((article) => article.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || article.excerpt.toLowerCase().includes(searchQuery.value.toLowerCase()));
    }

    // Featured filter
    if (featuredFilter.value) {
        filtered = filtered.filter((article) => article.is_featured);
    }

    // Sort articles
    filtered.sort((a, b) => {
        switch (sortBy.value) {
            case 'oldest':
                return new Date(a.created_at) - new Date(b.created_at);
            case 'views':
                return (b.views || 0) - (a.views || 0);
            case 'title':
                return a.title.localeCompare(b.title);
            case 'newest':
            default:
                return new Date(b.created_at) - new Date(a.created_at);
        }
    });

    return filtered;
});

const resetFilters = () => {
    featuredFilter.value = false;
    sortBy.value = 'newest';
    searchQuery.value = '';
};

const applyFilters = () => {
    showFilterDialog.value = false;
};

const loadArticles = async () => {
    try {
        loading.value = true;
        const response = await IndoPetArticleService.getArticles();
        articles.value = response.data || [];
    } catch (error) {
        console.error('Failed to load articles:', error);
        articles.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadArticles();
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
