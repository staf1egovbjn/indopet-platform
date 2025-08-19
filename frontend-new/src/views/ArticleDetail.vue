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
                    <div class="text-surface-900 dark:text-surface-0 leading-relaxed space-y-6">
                        <!-- Display full content if available, otherwise generate expanded content -->
                        <div v-if="article.content && article.content.length > 200" v-html="formatContent(article.content)"></div>
                        <div v-else class="space-y-4">
                            <p>{{ article.content || generateExpandedContent() }}</p>
                            
                            <h3 class="text-2xl font-semibold mt-8 mb-4">Understanding Pet Care Fundamentals</h3>
                            <p>Caring for pets requires a comprehensive understanding of their needs, behaviors, and health requirements. Each species has unique characteristics that must be considered when providing optimal care.</p>
                            
                            <h3 class="text-2xl font-semibold mt-8 mb-4">Key Areas of Focus</h3>
                            <ul class="list-disc list-inside space-y-2 ml-4">
                                <li><strong>Nutrition:</strong> Providing balanced, species-appropriate diets is fundamental to pet health</li>
                                <li><strong>Healthcare:</strong> Regular veterinary checkups help prevent and detect health issues early</li>
                                <li><strong>Exercise:</strong> Physical activity is essential for maintaining healthy weight and mental stimulation</li>
                                <li><strong>Environment:</strong> Creating a safe, comfortable living space reduces stress and promotes wellbeing</li>
                                <li><strong>Socialization:</strong> Proper social interaction helps pets develop confidence and reduces behavioral problems</li>
                            </ul>

                            <h3 class="text-2xl font-semibold mt-8 mb-4">Expert Recommendations</h3>
                            <p>Veterinarians and animal behaviorists recommend establishing consistent routines that address all aspects of pet care. This includes regular feeding schedules, daily exercise, grooming sessions, and quality time for bonding.</p>
                            
                            <div class="bg-primary-50 dark:bg-primary-900/20 p-6 rounded-lg border-l-4 border-primary-500 my-6">
                                <h4 class="font-semibold text-primary-900 dark:text-primary-100 mb-2">💡 Pro Tip</h4>
                                <p class="text-primary-800 dark:text-primary-200">Keep a pet care journal to track your pet's habits, health changes, and veterinary appointments. This information is invaluable during vet visits and helps identify patterns in behavior or health.</p>
                            </div>

                            <h3 class="text-2xl font-semibold mt-8 mb-4">Building a Long-term Care Strategy</h3>
                            <p>Successful pet ownership involves planning for both daily care and long-term health management. This includes budgeting for veterinary expenses, understanding your pet's life stage needs, and preparing for emergencies.</p>
                            
                            <h3 class="text-2xl font-semibold mt-8 mb-4">Conclusion</h3>
                            <p>Taking care of your pet is a rewarding journey that requires dedication, knowledge, and love. By focusing on comprehensive care that addresses nutrition, health, exercise, and emotional needs, you can ensure your furry friend lives a happy and healthy life. Remember that every pet is unique, so adapt these guidelines to suit your pet's individual needs and personality.</p>
                        </div>
                    </div>
                </div>

                <!-- Article Actions -->
                <div class="flex items-center justify-between pt-6 mt-8 border-t border-surface-200 dark:border-surface-700">
                    <div class="flex items-center gap-4">
                        <Button icon="pi pi-thumbs-up" label="Like" severity="secondary" outlined @click="likeArticle" />
                        <Button icon="pi pi-share-alt" label="Share" severity="secondary" outlined @click="shareArticle" />
                        <Button icon="pi pi-bookmark" label="Save" severity="secondary" outlined @click="saveArticle" />
                    </div>
                    <div class="flex items-center gap-4">
                        <div v-if="article.category" class="flex items-center gap-2">
                            <i class="pi pi-tag text-primary-600"></i>
                            <router-link 
                                :to="`/articles?category=${article.category}`" 
                                class="text-sm text-primary-600 hover:underline"
                            >
                                {{ article.category }}
                            </router-link>
                        </div>
                        <Button 
                            label="Back to Articles" 
                            icon="pi pi-arrow-left" 
                            severity="secondary" 
                            text 
                            @click="router.push('/shop/articles')" 
                        />
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
                        @click="$router.push(`/shop/articles/${relatedArticle.id}`)"
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
            <Button label="Back to Articles" icon="pi pi-arrow-left" @click="$router.push('/shop/articles')" />
        </div>
    </div>
</template>

<script setup>
import IndoPetArticleService from '@/service/IndoPetArticleService.js';
import Badge from 'primevue/badge';
import Breadcrumb from 'primevue/breadcrumb';
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const toast = useToast();

const article = ref(null);
const relatedArticles = ref([]);
const loading = ref(true);

const breadcrumbHome = ref({ icon: 'pi pi-home', to: '/' });
const breadcrumbItems = ref([{ label: 'Articles', to: '/shop/articles' }, { label: 'Article Detail' }]);

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const generateExpandedContent = () => {
    return `This comprehensive guide provides detailed insights into pet care, covering everything from basic needs to advanced care techniques. 

Pet ownership is one of life's most rewarding experiences, but it comes with significant responsibilities. Understanding your pet's unique needs, behaviors, and health requirements is essential for providing the best possible care.

Whether you're a first-time pet owner or looking to improve your existing pet care routine, this guide will provide you with practical, evidence-based information to help you make informed decisions about your pet's wellbeing.`;
};

const formatContent = (content) => {
    // Basic HTML formatting for content that comes from the API
    return content.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
};

const likeArticle = async () => {
    toast.add({
        severity: 'success',
        summary: 'Liked',
        detail: `You liked "${article.value.title}"`,
        life: 3000
    });
};

const shareArticle = async () => {
    const shareData = {
        title: article.value.title,
        text: article.value.excerpt,
        url: window.location.href
    };

    try {
        if (navigator.share && navigator.canShare && navigator.canShare(shareData)) {
            await navigator.share(shareData);
        } else {
            // Fallback: copy to clipboard
            await navigator.clipboard.writeText(window.location.href);
            toast.add({
                severity: 'success',
                summary: 'Link Copied',
                detail: 'Article link copied to clipboard',
                life: 3000
            });
        }
    } catch (error) {
        toast.add({
            severity: 'info',
            summary: 'Share',
            detail: `Article "${article.value.title}" ready to share`,
            life: 3000
        });
    }
};

const saveArticle = async () => {
    toast.add({
        severity: 'success',
        summary: 'Saved',
        detail: `"${article.value.title}" saved to reading list`,
        life: 3000
    });
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
    line-clamp: 2;
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
