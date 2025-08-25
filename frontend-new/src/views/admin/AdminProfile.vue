&lt;template&gt;
    &lt;div class=&quot;card&quot;&gt;
        &lt;h1 class=&quot;text-3xl font-bold mb-6&quot;&gt;Profile&lt;/h1&gt;
        
        &lt;div class=&quot;grid&quot;&gt;
            &lt;div class=&quot;col-12 md:col-6&quot;&gt;
                &lt;div class=&quot;card p-4&quot;&gt;
                    &lt;div class=&quot;mb-4 text-center&quot;&gt;
                        &lt;div class=&quot;w-24 h-24 rounded-full bg-primary-100 flex items-center justify-center mx-auto mb-3&quot;&gt;
                            &lt;i class=&quot;pi pi-user text-4xl text-primary-600&quot;&gt;&lt;/i&gt;
                        &lt;/div&gt;
                        &lt;h2 class=&quot;text-xl font-semibold&quot;&gt;{{ user.name }}&lt;/h2&gt;
                        &lt;p class=&quot;text-surface-600&quot;&gt;{{ user.email }}&lt;/p&gt;
                    &lt;/div&gt;

                    &lt;form @submit.prevent=&quot;updateProfile&quot; class=&quot;mt-6&quot;&gt;
                        &lt;div class=&quot;field mb-4&quot;&gt;
                            &lt;label for=&quot;name&quot; class=&quot;block font-medium mb-2&quot;&gt;Name&lt;/label&gt;
                            &lt;InputText id=&quot;name&quot; v-model=&quot;form.name&quot; class=&quot;w-full&quot; :class=&quot;{ 'p-invalid': v$.form.name.$error }&quot; />
                            &lt;small v-if=&quot;v$.form.name.$error&quot; class=&quot;p-error&quot;&gt;Name is required&lt;/small&gt;
                        &lt;/div&gt;

                        &lt;div class=&quot;field mb-4&quot;&gt;
                            &lt;label for=&quot;email&quot; class=&quot;block font-medium mb-2&quot;&gt;Email&lt;/label&gt;
                            &lt;InputText id=&quot;email&quot; v-model=&quot;form.email&quot; type=&quot;email&quot; class=&quot;w-full&quot; :class=&quot;{ 'p-invalid': v$.form.email.$error }&quot; />
                            &lt;small v-if=&quot;v$.form.email.$error&quot; class=&quot;p-error&quot;&gt;Valid email is required&lt;/small&gt;
                        &lt;/div&gt;

                        &lt;div class=&quot;field mb-4&quot;&gt;
                            &lt;label for=&quot;password&quot; class=&quot;block font-medium mb-2&quot;&gt;New Password (optional)&lt;/label&gt;
                            &lt;Password id=&quot;password&quot; v-model=&quot;form.password&quot; :feedback=&quot;false&quot; toggleMask class=&quot;w-full&quot; />
                        &lt;/div&gt;

                        &lt;Button type=&quot;submit&quot; label=&quot;Update Profile&quot; class=&quot;w-full&quot; :loading=&quot;loading&quot; /&gt;
                    &lt;/form&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;

        &lt;Toast /&gt;
    &lt;/div&gt;
&lt;/template&gt;

&lt;script setup&gt;
import { ref, onMounted } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, email } from '@vuelidate/validators';
import { useToast } from 'primevue/usetoast';
import { AuthService } from '@/service/AuthService';

const toast = useToast();
const loading = ref(false);
const user = ref({});

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
    const userData = await AuthService.getCurrentUser();
    user.value = userData;
    form.value.name = userData.name;
    form.value.email = userData.email;
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
&lt;/script&gt;
