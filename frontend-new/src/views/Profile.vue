<template>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-surface-900 dark:text-surface-0 mb-2">My Profile</h1>
                    <p class="text-surface-600 dark:text-surface-400">Manage your account settings and preferences</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Info -->
            <div class="lg:col-span-2">
                <div class="card">
                    <h5 class="text-xl font-semibold text-surface-900 dark:text-surface-0 mb-6">Personal Information</h5>
                    <form @submit.prevent="updateProfile">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">First Name</label>
                                <InputText v-model="profile.firstName" placeholder="Enter first name" class="w-full" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Last Name</label>
                                <InputText v-model="profile.lastName" placeholder="Enter last name" class="w-full" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-2">Email</label>
                                <InputText v-model="profile.email" placeholder="Enter email" type="email" class="w-full" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-2">Phone</label>
                                <InputText v-model="profile.phone" placeholder="Enter phone number" class="w-full" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-2">Address</label>
                                <Textarea v-model="profile.address" placeholder="Enter your address" rows="3" class="w-full" />
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <Button label="Cancel" severity="secondary" outlined />
                            <Button label="Update Profile" type="submit" />
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="card mt-6">
                    <h5 class="text-xl font-semibold text-surface-900 dark:text-surface-0 mb-6">Change Password</h5>
                    <form @submit.prevent="changePassword">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Current Password</label>
                                <Password v-model="passwordForm.current" placeholder="Enter current password" class="w-full" toggleMask />
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">New Password</label>
                                <Password v-model="passwordForm.new" placeholder="Enter new password" class="w-full" toggleMask />
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Confirm New Password</label>
                                <Password v-model="passwordForm.confirm" placeholder="Confirm new password" class="w-full" toggleMask />
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <Button label="Cancel" severity="secondary" outlined />
                            <Button label="Change Password" type="submit" />
                        </div>
                    </form>
                </div>
            </div>

            <!-- Profile Stats & Actions -->
            <div class="lg:col-span-1">
                <!-- Profile Picture -->
                <div class="card text-center">
                    <div class="w-32 h-32 mx-auto mb-4 bg-surface-100 dark:bg-surface-800 rounded-full flex items-center justify-center">
                        <i class="pi pi-user text-6xl text-surface-400"></i>
                    </div>
                    <h6 class="text-xl font-medium text-surface-900 dark:text-surface-0 mb-2">{{ fullName }}</h6>
                    <p class="text-surface-600 dark:text-surface-400 mb-4">{{ profile.email }}</p>
                    <Button label="Upload Photo" icon="pi pi-upload" outlined class="w-full" />
                </div>

                <!-- Account Stats -->
                <div class="card mt-6">
                    <h5 class="text-xl font-semibold text-surface-900 dark:text-surface-0 mb-6">Account Summary</h5>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-surface-600 dark:text-surface-400">Total Orders</span>
                            <Badge value="12" severity="info" />
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-surface-600 dark:text-surface-400">Wishlist Items</span>
                            <Badge value="5" severity="success" />
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-surface-600 dark:text-surface-400">Member Since</span>
                            <span class="text-sm font-medium">Jan 2024</span>
                        </div>
                        <Divider />
                        <div class="flex justify-between items-center">
                            <span class="text-surface-600 dark:text-surface-400">Account Status</span>
                            <Badge value="Active" severity="success" />
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-6">
                    <h5 class="text-xl font-semibold text-surface-900 dark:text-surface-0 mb-6">Quick Actions</h5>
                    <div class="space-y-3">
                        <Button label="Order History" icon="pi pi-list" severity="secondary" outlined class="w-full" @click="viewOrderHistory" />
                        <Button label="My Wishlist" icon="pi pi-heart" severity="secondary" outlined class="w-full" @click="viewWishlist" />
                        <Button label="Address Book" icon="pi pi-map-marker" severity="secondary" outlined class="w-full" @click="manageAddresses" />
                        <Button label="Logout" icon="pi pi-sign-out" severity="danger" outlined class="w-full" @click="logout" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import Divider from 'primevue/divider';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Textarea from 'primevue/textarea';
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// Mock profile data
const profile = ref({
    firstName: 'John',
    lastName: 'Doe',
    email: 'john.doe@example.com',
    phone: '+62 812 3456 7890',
    address: 'Jl. Contoh No. 123, Jakarta Selatan, DKI Jakarta 12345'
});

const passwordForm = ref({
    current: '',
    new: '',
    confirm: ''
});

const fullName = computed(() => {
    return `${profile.value.firstName} ${profile.value.lastName}`.trim();
});

const updateProfile = () => {
    toast.add({
        severity: 'success',
        summary: 'Profile Updated',
        detail: 'Your profile has been updated successfully',
        life: 3000
    });
};

const changePassword = () => {
    if (passwordForm.value.new !== passwordForm.value.confirm) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Passwords do not match',
            life: 3000
        });
        return;
    }
    
    toast.add({
        severity: 'success',
        summary: 'Password Changed',
        detail: 'Your password has been changed successfully',
        life: 3000
    });
    
    // Reset form
    passwordForm.value = { current: '', new: '', confirm: '' };
};

const viewOrderHistory = () => {
    // TODO: Navigate to order history
    router.push('/shop/orders');
};

const viewWishlist = () => {
    // TODO: Navigate to wishlist
    toast.add({
        severity: 'info',
        summary: 'Wishlist',
        detail: 'Wishlist feature coming soon',
        life: 3000
    });
};

const manageAddresses = () => {
    // TODO: Navigate to address management
    toast.add({
        severity: 'info',
        summary: 'Address Management',
        detail: 'Address management feature coming soon',
        life: 3000
    });
};

const logout = () => {
    router.push('/auth/login');
};
</script>
