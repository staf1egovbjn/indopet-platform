<template>
    <div class="card">
        <h1 class="text-3xl font-bold mb-6">Profile</h1>
        
        <div class="grid">
            <div class="col-12 md:col-6">
                <div class="card p-4">
                    <div class="mb-4 text-center">
                        <div class="w-24 h-24 rounded-full bg-primary-100 flex items-center justify-center mx-auto mb-3">
                            <i class="pi pi-user text-4xl text-primary-600"></i>
                        </div>
                        <h2 class="text-xl font-semibold">{{ user.name }}</h2>
                        <p class="text-surface-600">{{ user.email }}</p>
                    </div>

                    <form @submit.prevent="updateProfile" class="mt-6">
                        <div class="field mb-4">
                            <label for="name" class="block font-medium mb-2">Name</label>
                            <InputText id="name" v-model="form.name" class="w-full" :class="{ 'p-invalid': v$.form.name.$error }" />
                            <small v-if="v$.form.name.$error" class="p-error">Name is required</small>
                        </div>

                        <div class="field mb-4">
                            <label for="email" class="block font-medium mb-2">Email</label>
                            <InputText id="email" v-model="form.email" type="email" class="w-full" :class="{ 'p-invalid': v$.form.email.$error }" />
                            <small v-if="v$.form.email.$error" class="p-error">Valid email is required</small>
                        </div>

                        <div class="field mb-4">
                            <label for="password" class="block font-medium mb-2">New Password (optional)</label>
                            <Password id="password" v-model="form.password" :feedback="false" toggleMask class="w-full" />
                        </div>

                        <Button type="submit" label="Update Profile" class="w-full" :loading="loading" />
                    </form>
                </div>
            </div>
        </div>

        <Toast />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, email } from '@vuelidate/validators';
import { useToast } from 'primevue/usetoast';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Toast from 'primevue/toast';
import { AuthService } from '@/service/AuthService';

const toast = useToast();
const loading = ref(false);
const user = ref({
    name: '',
    email: ''
});

const form = ref({
    name: '',
    email: '',
    password: ''
});

const rules = {
    form: {
        name: { required },
        email: { required, email }
    }
};

const v$ = useVuelidate(rules, { form });

onMounted(async () => {
    try {
        const userData = await AuthService.getCurrentUser();
        user.value = userData;
        form.value.name = userData.name;
        form.value.email = userData.email;
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load profile data',
            life: 3000
        });
    }
});

const updateProfile = async () => {
    try {
        const isValid = await v$.value.$validate();
        if (!isValid) return;

        loading.value = true;
        const response = await AuthService.updateProfile({
            name: form.value.name,
            email: form.value.email,
            password: form.value.password || undefined
        });

        if (response.success) {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Profile updated successfully',
                life: 3000
            });
            user.value = await AuthService.getCurrentUser();
        } else {
            throw new Error(response.error);
        }
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: error.message || 'Failed to update profile',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};
</script>
