<template>
    <div class="space-y-6">
        <div class="bg-white dark:bg-surface-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-surface-900 dark:text-surface-0 mb-6">My Orders</h2>
            
            <!-- Empty State -->
            <div v-if="orders.length === 0" class="text-center py-16">
                <div class="mb-8">
                    <i class="pi pi-list text-8xl text-surface-300 dark:text-surface-600"></i>
                </div>
                <h3 class="text-xl font-bold text-surface-900 dark:text-surface-0 mb-4">No orders yet</h3>
                <p class="text-surface-600 dark:text-surface-400 mb-8">
                    Start shopping to create your first order
                </p>
                <Button 
                    label="Shop Now" 
                    icon="pi pi-shopping-bag" 
                    @click="$router.push('/shop')"
                />
            </div>

            <!-- Orders List -->
            <div v-else class="space-y-4">
                <div v-for="order in orders" :key="order.id" class="border border-surface-200 dark:border-surface-700 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-semibold text-surface-900 dark:text-surface-0">Order #{{ order.id }}</h3>
                            <p class="text-surface-600 dark:text-surface-400">{{ formatDate(order.created_at) }}</p>
                        </div>
                        <Badge 
                            :value="order.status" 
                            :severity="getStatusSeverity(order.status)"
                            class="capitalize"
                        />
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        <div v-for="item in order.items" :key="item.id" class="flex justify-between">
                            <span class="text-surface-700 dark:text-surface-300">{{ item.product_name }} x{{ item.quantity }}</span>
                            <span class="font-medium">Rp {{ formatPrice(item.price * item.quantity) }}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-surface-200 dark:border-surface-700">
                        <span class="font-semibold text-surface-900 dark:text-surface-0">Total: Rp {{ formatPrice(order.total_amount) }}</span>
                        <div class="space-x-2">
                            <Button 
                                label="View Details" 
                                icon="pi pi-eye" 
                                size="small" 
                                severity="secondary"
                                outlined
                            />
                            <Button 
                                v-if="order.status === 'pending'"
                                label="Cancel" 
                                icon="pi pi-times" 
                                size="small" 
                                severity="danger"
                                outlined
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import { onMounted, ref } from 'vue';

const orders = ref([]);

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getStatusSeverity = (status) => {
    const severities = {
        pending: 'warning',
        processing: 'info',
        shipped: 'info',
        delivered: 'success',
        cancelled: 'danger'
    };
    return severities[status] || 'info';
};

onMounted(() => {
    // Mock orders data
    orders.value = [
        {
            id: 1001,
            status: 'delivered',
            total_amount: 225000,
            created_at: '2025-08-10T10:00:00Z',
            items: [
                {
                    id: 1,
                    product_name: 'Premium Dog Food',
                    quantity: 1,
                    price: 150000
                },
                {
                    id: 2,
                    product_name: 'Cat Toy Set',
                    quantity: 1,
                    price: 75000
                }
            ]
        },
        {
            id: 1002,
            status: 'processing',
            total_amount: 320000,
            created_at: '2025-08-11T14:30:00Z',
            items: [
                {
                    id: 3,
                    product_name: 'Dog Bed Large',
                    quantity: 1,
                    price: 320000
                }
            ]
        }
    ];
});
</script>
