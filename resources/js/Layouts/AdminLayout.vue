<script setup>
import { onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useAuth } from '@/composables/useAuth';
import { requireAuth } from '@/utils/authGuard';

const { user, logout } = useAuth();

// Проверяем авторизацию при загрузке админки
onMounted(async () => {
    await requireAuth();
});

const isActive = (path) => {
    return window.location.pathname.startsWith(path);
};

const handleLogout = () => {
    logout();
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <h1 class="text-2xl font-bold text-gray-900">Админка</h1>
                        <nav class="flex gap-4">
                            <Link
                                href="/admin/products"
                                :class="[
                                    'text-sm font-medium transition-colors',
                                    isActive('/admin/products')
                                        ? 'text-indigo-600'
                                        : 'text-gray-700 hover:text-indigo-600'
                                ]"
                            >
                                Товары
                            </Link>
                            <Link
                                href="/"
                                class="text-sm font-medium text-gray-700 transition-colors hover:text-indigo-600"
                            >
                                На сайт
                            </Link>
                        </nav>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600">
                            {{ user?.name || user?.email }}
                        </span>
                        <button
                            @click="handleLogout"
                            class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700"
                        >
                            Выйти
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
