<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0">Articles Management</h1>
                <p class="text-surface-600 dark:text-surface-400">Manage blog articles and content</p>
            </div>
            <Button 
                icon="pi pi-plus" 
                label="Add Article" 
                @click="openArticleDialog()" 
            />
        </div>

        <!-- Articles Table -->
        <div class="card">
            <DataTable 
                :value="articles" 
                :paginator="true" 
                :rows="10"
                :loading="loading"
                dataKey="id"
                filterDisplay="menu"
                :globalFilterFields="['title', 'excerpt']"
                showGridlines
            >
                <template #header>
                    <div class="flex justify-between items-center">
                        <h5 class="m-0">Articles</h5>
                        <IconField iconPosition="left">
                            <InputIcon class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Search..." />
                        </IconField>
                    </div>
                </template>

                <Column field="title" header="Title" sortable style="min-width: 250px">
                    <template #body="{ data }">
                        <div>
                            <div class="font-medium">{{ data.title }}</div>
                            <div class="text-sm text-surface-500">{{ data.slug }}</div>
                        </div>
                    </template>
                </Column>

                <Column field="excerpt" header="Excerpt" style="min-width: 200px">
                    <template #body="{ data }">
                        <div class="text-sm line-clamp-2">{{ data.excerpt }}</div>
                    </template>
                </Column>

                <Column field="status" header="Status" sortable>
                    <template #body="{ data }">
                        <Badge 
                            :value="data.status" 
                            :severity="data.status === 'published' ? 'success' : 'warning'"
                        />
                    </template>
                </Column>

                <Column field="is_featured" header="Featured">
                    <template #body="{ data }">
                        <i v-if="data.is_featured" class="pi pi-star-fill text-yellow-500"></i>
                        <i v-else class="pi pi-star text-surface-400"></i>
                    </template>
                </Column>

                <Column field="created_at" header="Created" sortable>
                    <template #body="{ data }">
                        <div class="text-sm">{{ formatDate(data.created_at) }}</div>
                    </template>
                </Column>

                <Column header="Actions" style="min-width: 120px">
                    <template #body="{ data }">
                        <div class="flex gap-2">
                            <Button 
                                icon="pi pi-pencil" 
                                size="small"
                                severity="info"
                                @click="openArticleDialog(data)" 
                            />
                            <Button 
                                icon="pi pi-trash" 
                                size="small"
                                severity="danger"
                                @click="confirmDelete(data)" 
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Article Dialog -->
        <Dialog 
            v-model:visible="articleDialog" 
            :modal="true" 
            :style="{ width: '800px' }" 
            :header="editingArticle ? 'Edit Article' : 'Add Article'"
        >
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Title *</label>
                    <InputText v-model="articleForm.title" class="w-full" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Excerpt *</label>
                    <Textarea v-model="articleForm.excerpt" rows="3" class="w-full" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Content *</label>
                    <Textarea v-model="articleForm.content" rows="8" class="w-full" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Status</label>
                        <Dropdown 
                            v-model="articleForm.status" 
                            :options="statusOptions" 
                            optionLabel="label" 
                            optionValue="value" 
                            placeholder="Select Status"
                            class="w-full" 
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Published At</label>
                        <Calendar v-model="articleForm.published_at" showTime hourFormat="24" class="w-full" />
                    </div>
                </div>

                <div class="flex items-center">
                    <Checkbox v-model="articleForm.is_featured" :binary="true" />
                    <label class="ml-2">Featured Article</label>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Meta Title</label>
                    <InputText v-model="articleForm.meta_title" class="w-full" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Meta Description</label>
                    <Textarea v-model="articleForm.meta_description" rows="2" class="w-full" />
                </div>
            </div>

            <template #footer>
                <Button 
                    label="Cancel" 
                    icon="pi pi-times" 
                    severity="secondary" 
                    @click="articleDialog = false" 
                />
                <Button 
                    :label="editingArticle ? 'Update' : 'Create'" 
                    icon="pi pi-check" 
                    @click="saveArticle" 
                    :loading="saving"
                />
            </template>
        </Dialog>

        <!-- Delete Confirmation -->
        <ConfirmDialog />
    </div>
</template>

<script setup>
import AdminArticleService from '@/service/AdminArticleService.js';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import Checkbox from 'primevue/checkbox';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';

const confirm = useConfirm();
const toast = useToast();

// Data
const articles = ref([]);
const loading = ref(false);
const saving = ref(false);
const articleDialog = ref(false);
const editingArticle = ref(null);

const statusOptions = [
    { label: 'Draft', value: 'draft' },
    { label: 'Published', value: 'published' }
];

// Filters - simplified without FilterMatchMode
const filters = ref({
    global: { value: null, matchMode: 'contains' }
});

// Form
const articleForm = ref({
    title: '',
    excerpt: '',
    content: '',
    status: 'draft',
    published_at: new Date(),
    is_featured: false,
    meta_title: '',
    meta_description: ''
});

// Methods
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const loadArticles = async () => {
    try {
        loading.value = true;
        const response = await AdminArticleService.getArticles();
        articles.value = response.data?.data || [];
    } catch (error) {
        console.error('Error loading admin articles:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load articles',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};

const openArticleDialog = (article = null) => {
    editingArticle.value = article;
    if (article) {
        articleForm.value = { 
            ...article,
            published_at: article.published_at ? new Date(article.published_at) : new Date()
        };
    } else {
        resetForm();
    }
    articleDialog.value = true;
};

const resetForm = () => {
    articleForm.value = {
        title: '',
        excerpt: '',
        content: '',
        status: 'draft',
        published_at: new Date(),
        is_featured: false,
        meta_title: '',
        meta_description: ''
    };
};

const saveArticle = async () => {
    try {
        saving.value = true;
        
        // Generate slug from title
        articleForm.value.slug = articleForm.value.title
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)+/g, '');

        if (editingArticle.value) {
            await AdminArticleService.updateArticle(editingArticle.value.id, articleForm.value);
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Article updated successfully',
                life: 3000
            });
        } else {
            await AdminArticleService.createArticle(articleForm.value);
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Article created successfully',
                life: 3000
            });
        }

        articleDialog.value = false;
        loadArticles();
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to save article',
            life: 3000
        });
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (article) => {
    confirm.require({
        message: `Are you sure you want to delete "${article.title}"?`,
        header: 'Confirm Delete',
        icon: 'pi pi-exclamation-triangle',
        rejectClass: 'p-button-secondary p-button-outlined',
        acceptClass: 'p-button-danger',
        accept: () => deleteArticle(article.id),
        reject: () => {}
    });
};

const deleteArticle = async (id) => {
    try {
        await AdminArticleService.deleteArticle(id);
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Article deleted successfully',
            life: 3000
        });
        loadArticles();
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to delete article',
            life: 3000
        });
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
</style>
