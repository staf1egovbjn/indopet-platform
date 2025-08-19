<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0">Categories Management</h1>
                <p class="text-surface-600 dark:text-surface-400">Manage product categories</p>
            </div>
            <Button 
                icon="pi pi-plus" 
                label="Add Category" 
                @click="openCategoryDialog()" 
            />
        </div>

        <!-- Categories Table -->
        <div class="card">
            <DataTable 
                :value="categories" 
                :paginator="true" 
                :rows="10"
                :loading="loading"
                dataKey="id"
                filterDisplay="menu"
                :globalFilterFields="['name', 'description']"
                showGridlines
            >
                <template #header>
                    <div class="flex justify-between items-center">
                        <h5 class="m-0">Categories</h5>
                        <IconField iconPosition="left">
                            <InputIcon class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Search..." />
                        </IconField>
                    </div>
                </template>

                <Column field="name" header="Name" sortable style="min-width: 200px">
                    <template #body="{ data }">
                        <div>
                            <div class="font-medium">{{ data.name }}</div>
                            <div class="text-sm text-surface-500">{{ data.slug }}</div>
                        </div>
                    </template>
                </Column>

                <Column field="description" header="Description" style="min-width: 300px">
                    <template #body="{ data }">
                        <div class="text-sm">{{ data.description || 'No description' }}</div>
                    </template>
                </Column>

                <Column field="sort_order" header="Sort Order" sortable>
                    <template #body="{ data }">
                        <Badge :value="data.sort_order" severity="secondary" />
                    </template>
                </Column>

                <Column field="is_active" header="Status" sortable>
                    <template #body="{ data }">
                        <Badge 
                            :value="data.is_active ? 'Active' : 'Inactive'" 
                            :severity="data.is_active ? 'success' : 'danger'"
                        />
                    </template>
                </Column>

                <Column header="Actions" style="min-width: 120px">
                    <template #body="{ data }">
                        <div class="flex gap-2">
                            <Button 
                                icon="pi pi-pencil" 
                                size="small"
                                severity="info"
                                @click="openCategoryDialog(data)" 
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

        <!-- Category Dialog -->
        <Dialog 
            v-model:visible="categoryDialog" 
            :modal="true" 
            :style="{ width: '500px' }" 
            :header="editingCategory ? 'Edit Category' : 'Add Category'"
        >
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Name *</label>
                    <InputText v-model="categoryForm.name" class="w-full" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Description</label>
                    <Textarea v-model="categoryForm.description" rows="3" class="w-full" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Sort Order</label>
                    <InputNumber v-model="categoryForm.sort_order" :useGrouping="false" class="w-full" />
                </div>

                <div class="flex items-center">
                    <Checkbox v-model="categoryForm.is_active" :binary="true" />
                    <label class="ml-2">Active</label>
                </div>
            </div>

            <template #footer>
                <Button 
                    label="Cancel" 
                    icon="pi pi-times" 
                    severity="secondary" 
                    @click="categoryDialog = false" 
                />
                <Button 
                    :label="editingCategory ? 'Update' : 'Create'" 
                    icon="pi pi-check" 
                    @click="saveCategory" 
                    :loading="saving"
                />
            </template>
        </Dialog>

        <!-- Delete Confirmation -->
        <ConfirmDialog />
    </div>
</template>

<script setup>
import AdminCategoryService from '@/service/AdminCategoryService.js';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';

const confirm = useConfirm();
const toast = useToast();

// Data
const categories = ref([]);
const loading = ref(false);
const saving = ref(false);
const categoryDialog = ref(false);
const editingCategory = ref(null);

// Filters - simplified without FilterMatchMode
const filters = ref({
    global: { value: null, matchMode: 'contains' }
});

// Form
const categoryForm = ref({
    name: '',
    description: '',
    sort_order: 1,
    is_active: true
});

// Methods
const loadCategories = async () => {
    try {
        loading.value = true;
        const response = await AdminCategoryService.getCategories();
        categories.value = response.data?.data || [];
    } catch (error) {
        console.error('Error loading admin categories:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load categories',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};

const openCategoryDialog = (category = null) => {
    editingCategory.value = category;
    if (category) {
        categoryForm.value = { ...category };
    } else {
        resetForm();
    }
    categoryDialog.value = true;
};

const resetForm = () => {
    categoryForm.value = {
        name: '',
        description: '',
        sort_order: 1,
        is_active: true
    };
};

const saveCategory = async () => {
    try {
        saving.value = true;
        
        // Generate slug from name
        categoryForm.value.slug = categoryForm.value.name
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)+/g, '');

        if (editingCategory.value) {
            await AdminCategoryService.updateCategory(editingCategory.value.id, categoryForm.value);
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Category updated successfully',
                life: 3000
            });
        } else {
            await AdminCategoryService.createCategory(categoryForm.value);
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Category created successfully',
                life: 3000
            });
        }

        categoryDialog.value = false;
        loadCategories();
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to save category',
            life: 3000
        });
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (category) => {
    confirm.require({
        message: `Are you sure you want to delete "${category.name}"?`,
        header: 'Confirm Delete',
        icon: 'pi pi-exclamation-triangle',
        rejectClass: 'p-button-secondary p-button-outlined',
        acceptClass: 'p-button-danger',
        accept: () => deleteCategory(category.id),
        reject: () => {}
    });
};

const deleteCategory = async (id) => {
    try {
        await AdminCategoryService.deleteCategory(id);
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Category deleted successfully',
            life: 3000
        });
        loadCategories();
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to delete category',
            life: 3000
        });
    }
};

onMounted(() => {
    loadCategories();
});
</script>
