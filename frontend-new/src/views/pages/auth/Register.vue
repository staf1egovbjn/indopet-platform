<script setup>
import FloatingConfigurator from '@/components/FloatingConfigurator.vue';
import { ref } from 'vue';

const firstName = ref('');
const lastName = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');
const acceptTerms = ref(false);
</script>

<template>
    <FloatingConfigurator />
    <div class="bg-surface-50 dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
        <div class="flex flex-col items-center justify-center">
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full bg-surface-0 dark:bg-surface-900 py-12 px-8 sm:px-20" style="border-radius: 53px">
                    <div class="text-center mb-8">
                        <div class="mb-8">
                            <div class="w-16 h-16 mx-auto bg-primary-500 rounded-full flex items-center justify-center mb-4">
                                <i class="pi pi-heart text-white text-2xl"></i>
                            </div>
                            <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium">IndoPet Platform</div>
                        </div>

                        <div class="text-surface-600 dark:text-surface-200 font-medium text-3xl mb-4">Create Account</div>
                        <span class="text-surface-600 dark:text-surface-200 font-medium">Join our pet-loving community</span>
                    </div>

                    <div class="w-full md:w-[410px] mx-auto space-y-6">
                        <!-- Name Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="firstName" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">First Name</label>
                                <InputText id="firstName" v-model="firstName" placeholder="First Name" class="w-full" />
                            </div>
                            <div>
                                <label for="lastName" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">Last Name</label>
                                <InputText id="lastName" v-model="lastName" placeholder="Last Name" class="w-full" />
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">Email</label>
                            <InputText id="email" v-model="email" type="email" placeholder="Email address" class="w-full" />
                        </div>

                        <!-- Password Fields -->
                        <div>
                            <label for="password" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">Password</label>
                            <Password id="password" v-model="password" placeholder="Password" class="w-full" inputClass="w-full" toggleMask />
                        </div>

                        <div>
                            <label for="confirmPassword" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">Confirm Password</label>
                            <Password id="confirmPassword" v-model="confirmPassword" placeholder="Confirm Password" class="w-full" inputClass="w-full" toggleMask />
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-center">
                            <Checkbox id="acceptTerms" v-model="acceptTerms" :binary="true" class="mr-2" />
                            <label for="acceptTerms" class="text-surface-900 dark:text-surface-0"> I accept the <a href="#" class="text-primary-500 hover:underline">Terms and Conditions</a> </label>
                        </div>

                        <!-- Register Button -->
                        <Button label="Create Account" class="w-full" />

                        <!-- Login Link -->
                        <div class="text-center">
                            <span class="text-surface-600 dark:text-surface-200">Already have an account? </span>
                            <router-link to="/auth/login" class="text-primary-500 font-medium hover:underline">Sign in</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
