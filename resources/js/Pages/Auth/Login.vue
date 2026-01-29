<script setup>
import { ref, reactive, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { useAuth } from '@/composables/useAuth';

const { login, checkAuth } = useAuth();

const form = reactive({
    email: '',
    password: '',
});

const loading = ref(false);
const error = ref('');
const checking = ref(true);

// Проверка авторизации при загрузке страницы
onMounted(async () => {
    const isValid = await checkAuth();
    checking.value = false;

    if (isValid) {
        router.visit('/admin/products');
    }
});

const handleSubmit = async () => {
    loading.value = true;
    error.value = '';

    const result = await login(form.email, form.password);

    if (result.success) {
        router.visit('/admin/products');
    } else {
        error.value = result.error || 'Неверный email или пароль';
    }

    loading.value = false;
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div v-if="checking" class="text-center">
            <svg class="mx-auto h-12 w-12 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-sm text-gray-600">Проверка авторизации...</p>
        </div>

        <div v-else class="w-full max-w-md space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                    Панель управления
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Войдите, чтобы продолжить
                </p>
            </div>

            <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
                <div v-if="error" class="rounded-md bg-red-50 p-4">
                    <p class="text-sm text-red-800">{{ error }}</p>
                </div>

                <div class="space-y-4 rounded-md shadow-sm">
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            class="relative block w-full rounded-md border-0 px-3 py-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            placeholder="Email"
                        />
                    </div>
                    <div>
                        <label for="password" class="sr-only">Пароль</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            class="relative block w-full rounded-md border-0 px-3 py-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            placeholder="Пароль"
                        />
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="group relative flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="loading">Вход...</span>
                        <span v-else>Войти</span>
                    </button>
                </div>

                <div class="text-center">
                    <Link href="/" class="text-sm text-indigo-600 hover:text-indigo-500">
                        ← На главную
                    </Link>
                </div>
            </form>

            <div class="mt-4 rounded-md bg-blue-50 p-4">
                <p class="text-xs text-blue-800">
                    <strong>Для теста:</strong><br>
                    admin@test.ru / password
                </p>
            </div>
        </div>
    </div>
</template>

