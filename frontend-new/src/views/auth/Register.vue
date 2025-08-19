<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 dark:from-surface-800 dark:to-surface-900 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mb-4">
                    <i class="pi pi-heart text-4xl text-primary-600 mr-2"></i>
                    <span class="text-3xl font-bold text-surface-900 dark:text-surface-0">IndoPet</span>
                </div>
                <p class="text-surface-600 dark:text-surface-400">Create your account</p>
            </div>

            <!-- Register Form -->
            <div class="bg-white dark:bg-surface-800 rounded-lg shadow-lg p-8">
                <form @submit.prevent="handleRegister">
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Full Name
                            </label>
                            <InputText
                                id="name"
                                v-model="form.name"
                                placeholder="Enter your full name"
                                class="w-full"
                                :class="{ 'p-invalid': errors.name }"
                                required
                            />
                            <small v-if="errors.name" class="p-error">{{ errors.name[0] }}</small>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Email Address
                            </label>
                            <InputText
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="Enter your email"
                                class="w-full"
                                :class="{ 'p-invalid': errors.email }"
                                required
                            />
                            <small v-if="errors.email" class="p-error">{{ errors.email[0] }}</small>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Phone Number (Optional)
                            </label>
                            <InputText
                                id="phone"
                                v-model="form.phone"
                                placeholder="Enter your phone number"
                                class="w-full"
                                :class="{ 'p-invalid': errors.phone }"
                            />
                            <small v-if="errors.phone" class="p-error">{{ errors.phone[0] }}</small>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Address (Optional)
                            </label>
                            <Textarea
                                id="address"
                                v-model="form.address"
                                placeholder="Enter your address"
                                class="w-full"
                                :class="{ 'p-invalid': errors.address }"
                                rows="3"
                            />
                            <small v-if="errors.address" class="p-error">{{ errors.address[0] }}</small>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Password
                            </label>
                            <Password
                                id="password"
                                v-model="form.password"
                                placeholder="Enter your password"
                                toggle-mask
                                class="w-full"
                                :class="{ 'p-invalid': errors.password }"
                                required
                            />
                            <small v-if="errors.password" class="p-error">{{ errors.password[0] }}</small>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">
                                Confirm Password
                            </label>
                            <Password
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                placeholder="Confirm your password"
                                toggle-mask
                                class="w-full"
                                :class="{ 'p-invalid': errors.password_confirmation }"
                                :feedback="false"
                                required
                            />
                            <small v-if="errors.password_confirmation" class="p-error">{{ errors.password_confirmation[0] }}</small>
                        </div>

                        <Button 
                            type="submit" 
                            label="Create Account" 
                            icon="pi pi-user-plus" 
                            class="w-full"
                            size="large"
                            :loading="loading"
                        />
                    </div>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-surface-200 dark:border-surface-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-surface-800 text-surface-500">or</span>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-surface-600 dark:text-surface-400">
                        Already have an account?
                        <router-link to="/auth/login" class="text-primary-600 hover:text-primary-500 font-medium">
                            Sign in
                        </router-link>
                    </p>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-6">
                <router-link to="/" class="text-surface-500 hover:text-surface-700 dark:hover:text-surface-300">
                    <i class="pi pi-arrow-left mr-2"></i>
                    Back to Home
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import AuthService from '@/service/AuthService.js';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Textarea from 'primevue/textarea';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const toast = useToast();

// Form data
const form = ref({
    name: '',
    email: '',
    phone: '',
    address: '',
    password: '',
    password_confirmation: ''
});

const loading = ref(false);
const errors = ref({});

// Methods
const handleRegister = async () => {
    loading.value = true;
    errors.value = {};

    try {
        const result = await AuthService.register(form.value);
        
        if (result.success) {
            const user = result.data.user;
            
            toast.add({
                severity: 'success',
                summary: 'Registration Successful!',
                detail: `Welcome to IndoPet, ${user.name}!`,
                life: 3000
            });

            // Redirect to shop (customers always go to shop after registration)
            router.push('/shop');
        } else {
            if (typeof result.error === 'object') {
                errors.value = result.error;
            } else {
                toast.add({
                    severity: 'error',
                    summary: 'Registration Failed',
                    detail: result.error,
                    life: 5000
                });
            }
        }
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'An unexpected error occurred',
            life: 5000
        });
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
:deep(.p-password input) {
    width: 100%;
}
</style>
