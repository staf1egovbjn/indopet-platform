<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0">Products Management</h1>
                <p class="text-surface-600 dark:text-surface-400">Manage your product inventory</p>
            </div>
            <Button 
                icon="pi pi-plus" 
                label="Add Product" 
                @click="openProductDialog()" 
            />
        </div>

        <!-- Products Table -->
        <div class="card">
            <DataTable 
                :value="products" 
                :paginator="true" 
                :rows="10"
                :loading="loading"
                dataKey="id"
                filterDisplay="menu"
                :globalFilterFields="['name', 'sku', 'category.name']"
                showGridlines
            >
                <template #header>
                    <div class="flex justify-between items-center">
                        <h5 class="m-0">Products</h5>
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
                            <div class="text-sm text-surface-500">{{ data.sku }}</div>
                        </div>
                    </template>
                </Column>

                <Column field="category.name" header="Category" sortable>
                    <template #body="{ data }">
                        <Badge :value="data.category?.name || 'No Category'" severity="secondary" />
                    </template>
                </Column>

                <Column field="price" header="Price" sortable>
                    <template #body="{ data }">
                        <div>
                            <div class="font-medium">Rp {{ formatPrice(data.price) }}</div>
                            <div v-if="data.sale_price" class="text-sm text-success-500">
                                Sale: Rp {{ formatPrice(data.sale_price) }}
                            </div>
                        </div>
                    </template>
                </Column>

                <Column field="stock_quantity" header="Stock" sortable>
                    <template #body="{ data }">
                        <Badge 
                            :value="data.stock_quantity" 
                            :severity="data.stock_quantity > 10 ? 'success' : data.stock_quantity > 0 ? 'warning' : 'danger'"
                        />
                    </template>
                </Column>

                <Column field="status" header="Status" sortable>
                    <template #body="{ data }">
                        <Badge 
                            :value="data.status" 
                            :severity="data.status === 'active' ? 'success' : 'danger'"
                        />
                    </template>
                </Column>

                <Column field="is_featured" header="Featured">
                    <template #body="{ data }">
                        <i v-if="data.is_featured" class="pi pi-star-fill text-yellow-500"></i>
                        <i v-else class="pi pi-star text-surface-400"></i>
                    </template>
                </Column>

                <Column header="Actions" style="min-width: 120px">
                    <template #body="{ data }">
                        <div class="flex gap-2">
                            <Button 
                                icon="pi pi-pencil" 
                                size="small"
                                severity="info"
                                @click="openProductDialog(data)" 
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

        <!-- Product Dialog -->
        <Dialog 
            v-model:visible="productDialog" 
            :modal="true" 
            :style="{ width: '600px' }" 
            :header="editingProduct ? 'Edit Product' : 'Add Product'"
        >
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Name *</label>
                        <InputText v-model="productForm.name" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">SKU *</label>
                        <InputText v-model="productForm.sku" class="w-full" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Category *</label>
                    <Dropdown 
                        v-model="productForm.category_id" 
                        :options="categories" 
                        optionLabel="name" 
                        optionValue="id" 
                        placeholder="Select Category"
                        class="w-full" 
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Description *</label>
                    <Textarea v-model="productForm.description" rows="3" class="w-full" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Short Description</label>
                    <Textarea v-model="productForm.short_description" rows="2" class="w-full" />
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Price *</label>
                        <InputNumber v-model="productForm.price" :useGrouping="false" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Sale Price</label>
                        <InputNumber v-model="productForm.sale_price" :useGrouping="false" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Stock *</label>
                        <InputNumber v-model="productForm.stock_quantity" :useGrouping="false" class="w-full" />
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex items-center">
                        <Checkbox v-model="productForm.is_featured" :binary="true" />
                        <label class="ml-2">Featured Product</label>
                    </div>
                    <div class="flex items-center">
                        <Checkbox v-model="productForm.manage_stock" :binary="true" />
                        <label class="ml-2">Manage Stock</label>
                    </div>
                </div>
            </div>

            <template #footer>
                <Button 
                    label="Cancel" 
                    icon="pi pi-times" 
                    severity="secondary" 
                    @click="productDialog = false" 
                />
                <Button 
                    :label="editingProduct ? 'Update' : 'Create'" 
                    icon="pi pi-check" 
                    @click="saveProduct" 
                    :loading="saving"
                />
            </template>
        </Dialog>

        <!-- Delete Confirmation -->
        <ConfirmDialog />
    </div>
</template>

<script setup>
import AdminProductService from '@/service/AdminProductService.js';
import IndoPetCategoryService from '@/service/IndoPetCategoryService.js';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
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
const products = ref([]);
const categories = ref([]);
const loading = ref(false);
const saving = ref(false);
const productDialog = ref(false);
const editingProduct = ref(null);

// Filters - simplified without FilterMatchMode
const filters = ref({
    global: { value: null, matchMode: 'contains' }
});

// Form
const productForm = ref({
    name: '',
    sku: '',
    category_id: null,
    description: '',
    short_description: '',
    price: null,
    sale_price: null,
    stock_quantity: 0,
    is_featured: false,
    manage_stock: true,
    status: 'active'
});

// Methods
const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
};

const loadProducts = async () => {
    try {
        loading.value = true;
        const response = await AdminProductService.getProducts();
        products.value = response.data?.data || [];
    } catch (error) {
        console.error('Error loading admin products:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load products',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};

const loadCategories = async () => {
    try {
        const response = await IndoPetCategoryService.getCategories();
        categories.value = response.data || [];
    } catch (error) {
        // Categories failed to load - will show empty dropdown
    }
};

const openProductDialog = (product = null) => {
    editingProduct.value = product;
    if (product) {
        productForm.value = { ...product };
    } else {
        resetForm();
    }
    productDialog.value = true;
};

const resetForm = () => {
    productForm.value = {
        name: '',
        sku: '',
        category_id: null,
        description: '',
        short_description: '',
        price: null,
        sale_price: null,
        stock_quantity: 0,
        is_featured: false,
        manage_stock: true,
        status: 'active'
    };
};

const saveProduct = async () => {
    try {
        saving.value = true;
        
        // Generate slug from name
        productForm.value.slug = productForm.value.name
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)+/g, '');

        if (editingProduct.value) {
            await AdminProductService.updateProduct(editingProduct.value.id, productForm.value);
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Product updated successfully',
                life: 3000
            });
        } else {
            await AdminProductService.createProduct(productForm.value);
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Product created successfully',
                life: 3000
            });
        }

        productDialog.value = false;
        loadProducts();
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to save product',
            life: 3000
        });
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (product) => {
    confirm.require({
        message: `Are you sure you want to delete "${product.name}"?`,
        header: 'Confirm Delete',
        icon: 'pi pi-exclamation-triangle',
        rejectClass: 'p-button-secondary p-button-outlined',
        acceptClass: 'p-button-danger',
        accept: () => deleteProduct(product.id),
        reject: () => {}
    });
};

const deleteProduct = async (id) => {
    try {
        await AdminProductService.deleteProduct(id);
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Product deleted successfully',
            life: 3000
        });
        loadProducts();
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to delete product',
            life: 3000
        });
    }
};

onMounted(() => {
    loadProducts();
    loadCategories();
});
</script>
