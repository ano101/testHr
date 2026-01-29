<script setup>
import { Link } from '@inertiajs/vue3';
import { formatPrice, formatDateTime } from '@/utils/formatters';

defineProps({
    product: Object,
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <Link
                            href="/"
                            class="text-indigo-600 transition-colors hover:text-indigo-700"
                        >
                            ← К каталогу
                        </Link>
                    </div>
                    <Link
                        href="/login"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700"
                    >
                        Админка
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="p-8">
                    <div class="mb-6 border-b pb-6">
                        <h1 class="mb-2 text-3xl font-bold text-gray-900">
                            {{ product.name }}
                        </h1>
                        <p class="text-lg text-indigo-600">
                            {{ product.category?.name || 'Разное' }}
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h2 class="mb-2 text-sm font-medium text-gray-500">Цена</h2>
                            <p class="text-4xl font-bold text-gray-900">
                                {{ formatPrice(product.price) }}
                            </p>
                        </div>

                        <div>
                            <h2 class="mb-2 text-sm font-medium text-gray-500">Описание</h2>
                            <p class="text-base leading-relaxed text-gray-700">
                                {{ product.description }}
                            </p>
                        </div>

                        <div class="grid gap-4 border-t pt-6 sm:grid-cols-2">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">ID</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ product.id }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Категория</h3>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ product.category?.name || '—' }}
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Создан</h3>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ formatDateTime(product.created_at) }}
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Обновлён</h3>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ formatDateTime(product.updated_at) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t pt-6">
                        <Link
                            href="/"
                            class="inline-block rounded-md bg-indigo-600 px-6 py-3 text-base font-medium text-white transition-colors hover:bg-indigo-700"
                        >
                            Назад
                        </Link>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

