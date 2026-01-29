<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useProductForm } from '@/composables/useProductApi';

const props = defineProps({
    product: {
        type: Object,
        default: null,
    },
    categories: {
        type: Array,
        required: true,
    },
});

const {
    form,
    loading,
    errors,
    isEditMode,
    handleSubmit,
} = useProductForm(props.product, props.categories);
</script>

<template>
    <AdminLayout>
        <div class="mx-auto max-w-4xl space-y-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <Link
                            href="/admin/products"
                            class="text-gray-500 transition-colors hover:text-gray-700"
                        >
                            Товары
                        </Link>
                    </li>
                    <li>
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </li>
                    <li>
                        <span class="font-medium text-gray-900">
                            {{ isEditMode ? 'Редактирование' : 'Создание' }}
                        </span>
                    </li>
                </ol>
            </nav>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ isEditMode ? 'Редактирование товара' : 'Создание товара' }}
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ isEditMode ? 'Обновите информацию о товаре' : 'Заполните данные для создания нового товара' }}
                    </p>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow-md">
                <form @submit.prevent="handleSubmit" class="space-y-8 p-8">
                    <div v-if="errors.general" class="rounded-lg border-l-4 border-red-500 bg-red-50 p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <p class="ml-3 text-sm font-medium text-red-800">{{ errors.general }}</p>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700">
                                Название товара <span class="text-red-500">*</span>
                            </label>
                            <div class="relative mt-2">
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    placeholder="Введите название товара"
                                    class="block w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                    :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500/20': errors.name }"
                                />
                                <div v-if="errors.name" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <p v-if="errors.name" class="mt-2 text-sm text-red-600">
                                {{ errors.name }}
                            </p>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-700">
                                Категория <span class="text-red-500">*</span>
                            </label>
                            <div class="relative mt-2">
                                <select
                                    id="category_id"
                                    v-model="form.category_id"
                                    required
                                    class="block w-full appearance-none rounded-lg border-2 border-gray-200 bg-white px-4 py-3 pr-10 text-gray-900 transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                    :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500/20': errors.category_id }"
                                >
                                    <option value="">Выберите категорию</option>
                                    <option
                                        v-for="category in categories"
                                        :key="category.id"
                                        :value="category.id"
                                    >
                                        {{ category.name }}
                                    </option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            <p v-if="errors.category_id" class="mt-2 text-sm text-red-600">
                                {{ errors.category_id }}
                            </p>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-700">
                                Цена <span class="text-red-500">*</span>
                            </label>
                            <div class="relative mt-2">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <span class="text-gray-500 sm:text-sm">₽</span>
                                </div>
                                <input
                                    id="price"
                                    v-model.number="form.price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    required
                                    placeholder="0.00"
                                    class="block w-full rounded-lg border-2 border-gray-200 py-3 pl-10 pr-4 text-gray-900 placeholder:text-gray-400 transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                    :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500/20': errors.price }"
                                />
                                <div v-if="errors.price" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <p v-if="errors.price" class="mt-2 text-sm text-red-600">
                                {{ errors.price }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700">
                            Описание <span class="text-red-500">*</span>
                        </label>
                        <div class="relative mt-2">
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="6"
                                required
                                placeholder="Опишите товар..."
                                class="block w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                                :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500/20': errors.description }"
                            />
                        </div>
                        <p v-if="errors.description" class="mt-2 text-sm text-red-600">
                            {{ errors.description }}
                        </p>
                        <p class="mt-2 text-xs text-gray-500">
                            Опишите основные характеристики и преимущества товара
                        </p>
                    </div>

                    <div class="flex items-center justify-between gap-4 border-t border-gray-200 pt-6">
                        <p class="text-sm text-gray-500">
                            <span class="text-red-500">*</span> Обязательные поля
                        </p>
                        <div class="flex gap-3">
                            <Link
                                href="/admin/products"
                                class="rounded-lg border-2 border-gray-300 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 transition-all hover:bg-gray-50 hover:border-gray-400"
                            >
                                Отмена
                            </Link>
                            <button
                                type="submit"
                                :disabled="loading"
                                class="flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white transition-all hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md"
                            >
                                <svg v-if="loading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ loading ? 'Сохранение...' : 'Сохранить товар' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

