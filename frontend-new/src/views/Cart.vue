<template>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0 mb-2">Shopping Cart</h1>
                    <p class="text-surface-600 dark:text-surface-400">Review your items before checkout</p>
                </div>
                <Button label="Continue Shopping" icon="pi pi-arrow-left" severity="secondary" outlined @click="continueShopping" />
            </div>
        </div>

        <!-- Cart Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div v-if="cartItems.length === 0" class="text-center py-12">
                        <i class="pi pi-shopping-cart text-6xl text-surface-400 mb-4"></i>
                        <h3 class="text-xl font-medium text-surface-900 dark:text-surface-0 mb-2">Your cart is empty</h3>
                        <p class="text-surface-600 dark:text-surface-400 mb-6">Add some products to get started</p>
                        <Button label="Browse Products" icon="pi pi-shopping-bag" @click="continueShopping" />
                    </div>

                    <div v-else class="space-y-4">
                        <div v-for="item in cartItems" :key="item.id" class="flex gap-4 p-4 border border-surface-200 dark:border-surface-700 rounded-lg">
                            <div class="w-20 h-20 bg-surface-100 dark:bg-surface-800 rounded-lg flex-shrink-0 flex items-center justify-center">
                                <i class="pi pi-image text-2xl text-surface-400"></i>
                            </div>
                            <div class="flex-1">
                                <h6 class="font-medium text-surface-900 dark:text-surface-0 mb-1">{{ item.name }}</h6>
                                <p class="text-sm text-surface-600 dark:text-surface-400 mb-2">{{ item.description }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg font-bold text-primary-600">Rp {{ formatPrice(item.price) }}</span>
                                        <div class="flex items-center gap-2">
                                            <Button icon="pi pi-minus" size="small" severity="secondary" text @click="updateQuantity(item, item.quantity - 1)" :disabled="item.quantity <= 1" />
                                            <span class="px-3 py-1 bg-surface-100 dark:bg-surface-800 rounded">{{ item.quantity }}</span>
                                            <Button icon="pi pi-plus" size="small" severity="secondary" text @click="updateQuantity(item, item.quantity + 1)" />
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold">Rp {{ formatPrice(item.price * item.quantity) }}</span>
                                        <Button icon="pi pi-trash" size="small" severity="danger" text @click="removeItem(item)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div v-if="cartItems.length > 0" class="lg:col-span-1">
                <div class="card sticky top-6">
                    <h5 class="text-xl font-semibold text-surface-900 dark:text-surface-0 mb-4">Order Summary</h5>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-surface-600 dark:text-surface-400">Subtotal ({{ totalItems }} items)</span>
                            <span class="font-medium">Rp {{ formatPrice(subtotal) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-surface-600 dark:text-surface-400">Shipping</span>
                            <span class="font-medium">Rp {{ formatPrice(shippingCost) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-surface-600 dark:text-surface-400">Tax</span>
                            <span class="font-medium">Rp {{ formatPrice(tax) }}</span>
                        </div>
                        <Divider />
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span class="text-primary-600">Rp {{ formatPrice(total) }}</span>
                        </div>
                    </div>
                    <Button label="Proceed to Checkout" icon="pi pi-credit-card" class="w-full" @click="proceedToCheckout" />
                    <Button label="Clear Cart" severity="secondary" outlined class="w-full mt-2" @click="clearCart" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Button from 'primevue/button';
import Divider from 'primevue/divider';
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// Mock cart data - in real app this would come from a store/service
const cartItems = ref([
    {
        id: 1,
        name: 'Royal Canin Adult',
        description: 'Premium dog food for adult dogs',
        price: 250000,
        quantity: 2
    },
    {
        id: 2,
        name: 'Cat Vitamin Supplement',
        description: 'Essential vitamins for healthy cats',
        price: 75000,
        quantity: 1
    }
]);

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
};

const continueShopping = () => {
    router.push('/shop');
};

const updateQuantity = (item, newQuantity) => {
    if (newQuantity > 0) {
        item.quantity = newQuantity;
    }
};

const removeItem = (itemToRemove) => {
    const index = cartItems.value.findIndex((item) => item.id === itemToRemove.id);
    if (index > -1) {
        cartItems.value.splice(index, 1);
    }
};

const clearCart = () => {
    cartItems.value = [];
};

const proceedToCheckout = () => {
    toast.add({
        severity: 'info',
        summary: 'Checkout',
        detail: 'Redirecting to checkout page...',
        life: 3000
    });
};

// Computed values
const totalItems = computed(() => {
    return cartItems.value.reduce((total, item) => total + item.quantity, 0);
});

const subtotal = computed(() => {
    return cartItems.value.reduce((total, item) => total + item.price * item.quantity, 0);
});

const shippingCost = computed(() => {
    return subtotal.value > 500000 ? 0 : 50000; // Free shipping over 500k
});

const tax = computed(() => {
    return Math.round(subtotal.value * 0.1); // 10% tax
});

const total = computed(() => {
    return subtotal.value + shippingCost.value + tax.value;
});
</script>
