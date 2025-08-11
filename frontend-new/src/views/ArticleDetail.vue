<template>
    <div class="space-y-6">
        <div v-if="loading" class="flex justify-center items-center py-12">
            <ProgressSpinner />
        </div>

        <div v-else-if="article" class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <Breadcrumb :home="breadcrumbHome" :model="breadcrumbItems" class="mb-6" />

            <!-- Article Header -->
            <div class="card">
                <div class="mb-6">
                    <Badge v-if="article.is_featured" value="Featured" severity="success" class="mb-3" />
                    <h1 class="text-4xl font-bold text-surface-900 dark:text-surface-0 mb-4">{{ article.title }}</h1>

                    <div class="flex items-center gap-6 text-sm text-surface-600 dark:text-surface-400 mb-4">
                        <div class="flex items-center gap-2">
                            <i class="pi pi-calendar"></i>
                            <span>{{ formatDate(article.created_at) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="pi pi-clock"></i>
                            <span>{{ article.read_time || '5' }} min read</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="pi pi-eye"></i>
                            <span>{{ article.views || 0 }} views</span>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="w-full h-64 bg-surface-100 dark:bg-surface-800 rounded-lg mb-6 flex items-center justify-center">
                        <i class="pi pi-image text-6xl text-surface-400"></i>
                    </div>

                    <!-- Article Excerpt -->
                    <div class="text-lg text-surface-600 dark:text-surface-400 leading-relaxed mb-6 p-4 bg-surface-50 dark:bg-surface-800 rounded-lg border-l-4 border-primary-500">
                        {{ article.excerpt }}
                    </div>
                </div>

                <!-- Article Content -->
                <div class="prose max-w-none">
                    <div class="text-surface-900 dark:text-surface-0 leading-relaxed space-y-4">
                        <!-- Mock content since we don't have full content in API -->
                        <p>{{ article.content || generateMockContent() }}</p>

                        <h3 class="text-2xl font-semibold mt-8 mb-4">Key Points</h3>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Proper nutrition is essential for your pet's health and longevity</li>
                            <li>Regular veterinary checkups can prevent many health issues</li>
                            <li>Exercise and mental stimulation are crucial for pet wellbeing</li>
                            <li>Creating a safe environment protects your pet from hazards</li>
                        </ul>

                        <h3 class="text-2xl font-semibold mt-8 mb-4">Conclusion</h3>
                        <p>Taking care of your pet requires dedication, knowledge, and love. By following these guidelines and staying informed about best practices, you can ensure your furry friend lives a happy and healthy life.</p>
                    </div>
                </div>

                <!-- Article Actions -->
                <div class="flex items-center justify-between pt-6 mt-8 border-t border-surface-200 dark:border-surface-700">
                    <div class="flex items-center gap-4">
                        <Button icon="pi pi-thumbs-up" label="Like" severity="secondary" outlined @click="likeArticle" />
                        <Button icon="pi pi-share-alt" label="Share" severity="secondary" outlined @click="shareArticle" />
                    </div>
                    <div class="flex items-center gap-2">
                        <span v-if="article.category" class="text-sm text-primary-600">
                            <i class="pi pi-tag mr-2"></i>
                            {{ article.category }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Related Articles -->
            <div v-if="relatedArticles.length > 0" class="card">
                <h3 class="text-2xl font-semibold text-surface-900 dark:text-surface-0 mb-6">Related Articles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        v-for="relatedArticle in relatedArticles"
                        :key="relatedArticle.id"
                        class="flex gap-4 p-4 border border-surface-200 dark:border-surface-700 rounded-lg cursor-pointer hover:shadow-md transition-shadow"
                        @click="$router.push(`/articles/${relatedArticle.id}`)"
                    >
                        <div class="w-20 h-20 bg-surface-100 dark:bg-surface-800 rounded-lg flex-shrink-0 flex items-center justify-center">
                            <i class="pi pi-file-text text-2xl text-surface-400"></i>
                        </div>
                        <div class="flex-1">
                            <h6 class="font-medium text-surface-900 dark:text-surface-0 mb-2 line-clamp-2">{{ relatedArticle.title }}</h6>
                            <p class="text-sm text-surface-600 dark:text-surface-400 mb-2 line-clamp-2">{{ relatedArticle.excerpt }}</p>
                            <span class="text-xs text-surface-500">{{ formatDate(relatedArticle.created_at) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div v-else class="text-center py-12">
            <i class="pi pi-exclamation-triangle text-6xl text-surface-400 mb-4"></i>
            <h2 class="text-2xl font-semibold text-surface-900 dark:text-surface-0 mb-2">Article Not Found</h2>
            <p class="text-surface-600 dark:text-surface-400 mb-6">The article you're looking for doesn't exist or has been removed.</p>
            <Button label="Back to Articles" icon="pi pi-arrow-left" @click="$router.push('/articles')" />
        </div>
    </div>
</template>

<script setup>
import IndoPetArticleService from '@/service/IndoPetArticleService.js';
import Badge from 'primevue/badge';
import Breadcrumb from 'primevue/breadcrumb';
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';
import { onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const article = ref(null);
const relatedArticles = ref([]);
const loading = ref(true);

const breadcrumbHome = ref({ icon: 'pi pi-home', to: '/' });
const breadcrumbItems = ref([{ label: 'Articles', to: '/articles' }, { label: 'Article Detail' }]);

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const generateMockContent = () => {
    return "This is a comprehensive guide about pet care. In this article, we'll explore various aspects of keeping your pets healthy and happy. Pet ownership comes with great responsibility, and understanding your pet's needs is crucial for their wellbeing. From proper nutrition to regular exercise, there are many factors that contribute to your pet's quality of life. We'll discuss each of these elements in detail to help you provide the best possible care for your furry companion.";
};

const likeArticle = () => {
    console.log('Liking article:', article.value);
    // TODO: Implement like functionality
};

const shareArticle = () => {
    console.log('Sharing article:', article.value);
    // TODO: Implement share functionality
};

const loadArticle = async (articleId) => {
    try {
        loading.value = true;
        const response = await IndoPetArticleService.getArticleById(articleId);
        article.value = response.data;

        // Update breadcrumb with article title
        breadcrumbItems.value[1].label = article.value?.title || 'Article Detail';

        // Load related articles
        loadRelatedArticles();
    } catch (error) {
        console.error('Failed to load article:', error);
        article.value = null;
    } finally {
        loading.value = false;
    }
};

const loadRelatedArticles = async () => {
    try {
        const response = await IndoPetArticleService.getArticles();
        const allArticles = response.data || [];

        // Filter out current article and get random related articles
        relatedArticles.value = allArticles.filter((a) => a.id !== article.value?.id).slice(0, 4);
    } catch (error) {
        console.error('Failed to load related articles:', error);
        relatedArticles.value = [];
    }
};

// Watch for route changes
watch(
    () => route.params.id,
    (newId) => {
        if (newId) {
            loadArticle(newId);
        }
    }
);

onMounted(() => {
    const articleId = route.params.id;
    if (articleId) {
        loadArticle(articleId);
    }
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose {
    line-height: 1.7;
}

.prose h3 {
    color: var(--text-color);
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.prose p {
    margin-bottom: 1rem;
    color: var(--text-color-secondary);
}

.prose ul {
    color: var(--text-color-secondary);
}
</style>
