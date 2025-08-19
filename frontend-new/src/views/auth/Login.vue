<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 dark:from-surface-800 dark:to-surface-900 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mb-4">
                    <i class="pi pi-heart text-4xl text-primary-600 mr-2"></i>
                    <span class="text-3xl font-bold text-surface-900 dark:text-surface-0">IndoPet</span>
                </div>
                <p class="text-surface-600 dark:text-surface-400">Sign in to your account</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white dark:bg-surface-800 rounded-lg shadow-lg p-8">
                <form @submit.prevent="handleLogin">
                    <div class="space-y-6">
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
                                :feedback="false"
                                required
                            />
                            <small v-if="errors.password" class="p-error">{{ errors.password[0] }}</small>
                        </div>

                        <Button 
                            type="submit" 
                            label="Sign In" 
                            icon="pi pi-sign-in" 
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

                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-surface-600 dark:text-surface-400">
                        Don't have an account?
                        <router-link to="/auth/register" class="text-primary-600 hover:text-primary-500 font-medium">
                            Sign up
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
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const toast = useToast();

// Form data
const form = ref({
    email: '',
    password: ''
});

const loading = ref(false);
const errors = ref({});

// Methods
const handleLogin = async () => {
    loading.value = true;
    errors.value = {};

    try {
        const result = await AuthService.login(form.value);
        
        if (result.success) {
            const user = result.data.user;
            
            toast.add({
                severity: 'success',
                summary: 'Welcome Back!',
                detail: `Hello ${user.name}`,
                life: 3000
            });

            // Redirect based on user role
            if (user.role === 'admin') {
                router.push('/admin');
            } else {
                router.push('/shop');
            }
        } else {
            if (typeof result.error === 'object') {
                errors.value = result.error;
            } else {
                toast.add({
                    severity: 'error',
                    summary: 'Login Failed',
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
