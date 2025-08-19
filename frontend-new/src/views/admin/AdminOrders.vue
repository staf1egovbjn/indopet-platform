<template>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0">Orders Management</h1>
            <p class="text-surface-600 dark:text-surface-400">View and manage customer orders</p>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <DataTable 
                :value="orders" 
                :paginator="true" 
                :rows="10"
                :loading="loading"
                dataKey="id"
                filterDisplay="menu"
                :globalFilterFields="['id', 'user.name', 'status']"
                showGridlines
            >
                <template #header>
                    <div class="flex justify-between items-center">
                        <h5 class="m-0">Orders</h5>
                        <IconField iconPosition="left">
                            <InputIcon class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Search..." />
                        </IconField>
                    </div>
                </template>

                <Column field="id" header="Order ID" sortable>
                    <template #body="{ data }">
                        <Badge :value="`#${data.id}`" severity="info" />
                    </template>
                </Column>

                <Column field="user.name" header="Customer" sortable>
                    <template #body="{ data }">
                        <div>
                            <div class="font-medium">{{ data.user?.name || 'Guest' }}</div>
                            <div class="text-sm text-surface-500">{{ data.user?.email }}</div>
                        </div>
                    </template>
                </Column>

                <Column field="total_amount" header="Total" sortable>
                    <template #body="{ data }">
                        <div class="font-medium">Rp {{ formatPrice(data.total_amount) }}</div>
                    </template>
                </Column>

                <Column field="status" header="Status" sortable>
                    <template #body="{ data }">
                        <Badge 
                            :value="data.status" 
                            :severity="getStatusSeverity(data.status)"
                        />
                    </template>
                </Column>

                <Column field="payment_status" header="Payment" sortable>
                    <template #body="{ data }">
                        <Badge 
                            :value="data.payment_status || 'pending'" 
                            :severity="getPaymentStatusSeverity(data.payment_status)"
                        />
                    </template>
                </Column>

                <Column field="created_at" header="Date" sortable>
                    <template #body="{ data }">
                        <div class="text-sm">{{ formatDate(data.created_at) }}</div>
                    </template>
                </Column>

                <Column header="Actions" style="min-width: 200px">
                    <template #body="{ data }">
                        <div class="flex gap-2">
                            <Button 
                                icon="pi pi-eye" 
                                size="small"
                                severity="info"
                                @click="viewOrder(data)"
                                v-tooltip.top="'View Details'" 
                            />
                            <Dropdown 
                                v-model="data.status" 
                                :options="statusOptions" 
                                optionLabel="label" 
                                optionValue="value"
                                @change="updateOrderStatus(data)"
                                placeholder="Update Status"
                                class="w-32"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Order Details Dialog -->
        <Dialog 
            v-model:visible="orderDialog" 
            :modal="true" 
            :style="{ width: '800px' }" 
            header="Order Details"
        >
            <div v-if="selectedOrder" class="space-y-6">
                <!-- Order Info -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h6 class="font-semibold mb-2">Order Information</h6>
                        <div class="space-y-2 text-sm">
                            <div><strong>Order ID:</strong> #{{ selectedOrder.id }}</div>
                            <div><strong>Date:</strong> {{ formatDate(selectedOrder.created_at) }}</div>
                            <div><strong>Status:</strong> 
                                <Badge :value="selectedOrder.status" :severity="getStatusSeverity(selectedOrder.status)" />
                            </div>
                            <div><strong>Payment:</strong> 
                                <Badge :value="selectedOrder.payment_status || 'pending'" :severity="getPaymentStatusSeverity(selectedOrder.payment_status)" />
                            </div>
                        </div>
                    </div>
                    <div>
                        <h6 class="font-semibold mb-2">Customer Information</h6>
                        <div class="space-y-2 text-sm">
                            <div><strong>Name:</strong> {{ selectedOrder.user?.name || 'Guest' }}</div>
                            <div><strong>Email:</strong> {{ selectedOrder.user?.email || 'N/A' }}</div>
                            <div><strong>Phone:</strong> {{ selectedOrder.phone || 'N/A' }}</div>
                            <div><strong>Address:</strong> {{ selectedOrder.address || 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div>
                    <h6 class="font-semibold mb-2">Order Items</h6>
                    <DataTable :value="selectedOrder.items || []" showGridlines>
                        <Column field="product.name" header="Product">
                            <template #body="{ data }">
                                <div>
                                    <div class="font-medium">{{ data.product?.name || 'Unknown Product' }}</div>
                                    <div class="text-sm text-surface-500">SKU: {{ data.product?.sku }}</div>
                                </div>
                            </template>
                        </Column>
                        <Column field="quantity" header="Quantity">
                            <template #body="{ data }">
                                <Badge :value="data.quantity" severity="secondary" />
                            </template>
                        </Column>
                        <Column field="price" header="Unit Price">
                            <template #body="{ data }">
                                Rp {{ formatPrice(data.price) }}
                            </template>
                        </Column>
                        <Column header="Total">
                            <template #body="{ data }">
                                <div class="font-medium">Rp {{ formatPrice(data.price * data.quantity) }}</div>
                            </template>
                        </Column>
                    </DataTable>
                </div>

                <!-- Order Summary -->
                <div class="border-t pt-4">
                    <div class="flex justify-end">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between"><span>Subtotal:</span><span>Rp {{ formatPrice((selectedOrder.total_amount || 0) - (selectedOrder.shipping_cost || 0)) }}</span></div>
                            <div class="flex justify-between"><span>Shipping:</span><span>Rp {{ formatPrice(selectedOrder.shipping_cost || 0) }}</span></div>
                            <div class="flex justify-between font-bold text-lg border-t pt-2"><span>Total:</span><span>Rp {{ formatPrice(selectedOrder.total_amount) }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <template #footer>
                <Button 
                    label="Close" 
                    icon="pi pi-times" 
                    severity="secondary" 
                    @click="orderDialog = false" 
                />
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';

const toast = useToast();

// Data
const orders = ref([]);
const loading = ref(false);
const orderDialog = ref(false);
const selectedOrder = ref(null);

const statusOptions = [
    { label: 'Pending', value: 'pending' },
    { label: 'Processing', value: 'processing' },
    { label: 'Shipped', value: 'shipped' },
    { label: 'Delivered', value: 'delivered' },
    { label: 'Cancelled', value: 'cancelled' }
];

// Filters - simplified without FilterMatchMode
const filters = ref({
    global: { value: null, matchMode: 'contains' }
});

// Methods
const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getStatusSeverity = (status) => {
    switch (status) {
        case 'pending': return 'warning';
        case 'processing': return 'info';
        case 'shipped': return 'primary';
        case 'delivered': return 'success';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
};

const getPaymentStatusSeverity = (status) => {
    switch (status) {
        case 'paid': return 'success';
        case 'pending': return 'warning';
        case 'failed': return 'danger';
        default: return 'secondary';
    }
};

const loadOrders = async () => {
    try {
        loading.value = true;
        // Mock data for now since we don't have admin orders endpoint implemented yet
        orders.value = [
            {
                id: 1,
                user: { name: 'John Doe', email: 'john@example.com' },
                total_amount: 450000,
                status: 'pending',
                payment_status: 'pending',
                created_at: '2025-08-11T10:30:00Z',
                items: [
                    { product: { name: 'Royal Canin Adult Dog Food', sku: 'RC-AD-001' }, quantity: 1, price: 400000 }
                ],
                shipping_cost: 50000,
                phone: '081234567890',
                address: 'Jl. Sudirman No. 123, Jakarta'
            },
            {
                id: 2,
                user: { name: 'Jane Smith', email: 'jane@example.com' },
                total_amount: 275000,
                status: 'processing',
                payment_status: 'paid',
                created_at: '2025-08-10T14:20:00Z',
                items: [
                    { product: { name: 'Cat Food Premium', sku: 'CF-PR-002' }, quantity: 2, price: 125000 }
                ],
                shipping_cost: 25000,
                phone: '081234567891',
                address: 'Jl. Thamrin No. 456, Jakarta'
            }
        ];
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load orders',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};

const viewOrder = (order) => {
    selectedOrder.value = order;
    orderDialog.value = true;
};

const updateOrderStatus = async (order) => {
    try {
        // await AdminOrderService.updateOrderStatus(order.id, { status: order.status });
        
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Order status updated successfully',
            life: 3000
        });
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to update order status',
            life: 3000
        });
    }
};

onMounted(() => {
    loadOrders();
});
</script>
