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
                                <h6 class="font-medium text-surface-900 dark:text-surface-0 mb-1">{{ item.product.name }}</h6>
                                <p class="text-sm text-surface-600 dark:text-surface-400 mb-2">{{ item.product.description }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg font-bold text-primary-600">Rp {{ formatPrice(item.product.price) }}</span>
                                        <div class="flex items-center gap-2">
                                            <Button icon="pi pi-minus" size="small" severity="secondary" text 
                                                @click="updateQuantity(item.id, item.quantity - 1)" 
                                                :disabled="item.quantity <= 1 || loading" />
                                            <span class="px-3 py-1 bg-surface-100 dark:bg-surface-800 rounded">{{ item.quantity }}</span>
                                            <Button icon="pi pi-plus" size="small" severity="secondary" text 
                                                @click="updateQuantity(item.id, item.quantity + 1)" 
                                                :disabled="loading" />
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold">Rp {{ formatPrice(item.product.price * item.quantity) }}</span>
                                        <Button icon="pi pi-trash" size="small" severity="danger" text 
                                            @click="confirmRemoveItem(item.id)" 
                                            :disabled="loading" />
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
import Toast from 'primevue/toast';
import ProgressSpinner from 'primevue/progressspinner';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { computed, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import CartService from '@/service/CartService';

const router = useRouter();
const toast = useToast();
const confirm = useConfirm();
const loading = ref(false);
const cartItems = ref([]);
const loaded = ref(false);

// Load cart items when component mounts
onMounted(async () => {
    if (!loaded.value) {
        await loadCartItems();
        loaded.value = true;
    }
});

// Load cart items from the server
const loadCartItems = async () => {
    try {
        loading.value = true;
        const response = await CartService.getCartItems();
        if (response.success) {
            cartItems.value = response.data.items;
        } else {
            throw new Error('Failed to load cart items');
        }
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: error.message || 'Failed to load cart items',
            life: 3000
        });
        cartItems.value = [];
    } finally {
        loading.value = false;
    }
};

// Format price to Indonesian Rupiah
const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
};

// Update item quantity
const updateQuantity = async (item, newQuantity) => {
    if (newQuantity < 1) return;
    
    try {
        loading.value = true;
        await CartService.updateCartItem(item.id, newQuantity);
        await loadCartItems(); // Reload cart to get updated data
        
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Cart updated successfully',
            life: 3000
        });
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to update cart item',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};

// Confirm remove item
const confirmRemoveItem = (itemId) => {
    const item = cartItems.value.find(i => i.id === itemId);
    if (!item) return;
    
    confirm.require({
        message: `Are you sure you want to remove ${item.product.name} from your cart?`,
        header: 'Confirm Removal',
        icon: 'pi pi-exclamation-triangle',
        accept: () => removeItem(itemId)
    });
};

// Remove item from cart
const removeItem = async (itemId) => {
    try {
        loading.value = true;
        const response = await CartService.removeFromCart(itemId);
        if (response.success) {
            await loadCartItems();
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Item removed from cart',
                life: 3000
            });
        } else {
            throw new Error('Failed to remove item');
        }
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: error.message || 'Failed to remove item',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};

// Confirm clear cart
const confirmClearCart = () => {
    confirm.require({
        message: 'Are you sure you want to clear your entire cart?',
        header: 'Confirm Clear Cart',
        icon: 'pi pi-exclamation-triangle',
        accept: () => clearCart()
    });
};

// Clear entire cart
const clearCart = async () => {
    try {
        loading.value = true;
        await CartService.clearCart();
        cartItems.value = [];
        
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Cart cleared successfully',
            life: 3000
        });
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to clear cart',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};

// Navigate to shop
const continueShopping = () => {
    router.push('/shop');
};

// Proceed to checkout
const proceedToCheckout = async () => {
    try {
        loading.value = true;
        const response = await CartService.createOrder();
        router.push(`/shop/checkout/${response.data.orderId}`);
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to proceed to checkout',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};

// Computed properties
const totalItems = computed(() => {
    return cartItems.value.reduce((total, item) => total + item.quantity, 0);
});

const subtotal = computed(() => {
    return cartItems.value.reduce((total, item) => total + (parseFloat(item.product.price) * item.quantity), 0);
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
