<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useProductSearch } from '@/composables/useProductSearch';
import { useProductDelete } from '@/composables/useProductApi';
import { formatPrice } from '@/utils/formatters';

const props = defineProps({
  products: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    required: true,
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  flash: {
    type: Object,
    default: () => ({}),
  },
});

// Use the composable for search and filter logic
const {
  selectedCategory,
  searchQuery,
  isSearching,
  debouncedSearch,
  filterByCategory,
  clearSearch,
} = useProductSearch(props.filters, {
  homeRoute: '/admin/products',
  useSearchRoute: false, // Admin always uses the same route
});

// Use the composable for delete logic
const {
  showDeleteModal,
  productToDelete,
  deleting,
  confirmDelete,
  cancelDelete,
  handleDelete,
} = useProductDelete();
</script>

<template>
    <AdminLayout>
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">Товары</h2>
                <Link
                    href="/admin/products/create"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700"
                >
                    + Добавить
                </Link>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-md">
                <div class="space-y-4">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            type="search"
                            placeholder="Найти товар..."
                            class="block w-full rounded-lg border-2 border-gray-200 py-3 pl-12 pr-12 text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 sm:text-sm"
                            @input="debouncedSearch"
                        >
                        <div v-if="searchQuery" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <button
                                type="button"
                                class="rounded-full p-1 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                                @click="clearSearch"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div v-if="isSearching" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-5 w-5 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <label for="category-filter" class="text-sm font-semibold text-gray-700">
                            Категория:
                        </label>
                        <div class="relative flex-1 sm:max-w-xs">
                            <select
                                id="category-filter"
                                v-model="selectedCategory"
                                class="block w-full appearance-none rounded-lg border-2 border-gray-200 bg-white px-4 py-2.5 pr-10 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                @change="filterByCategory"
                            >
                                <option value="">Все</option>
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
                    </div>
                </div>
            </div>

            <div v-if="flash.success" class="rounded-md bg-green-50 p-4">
                <p class="text-sm text-green-800">{{ flash.success }}</p>
            </div>
            <div v-if="flash.error" class="rounded-md bg-red-50 p-4">
                <p class="text-sm text-red-800">{{ flash.error }}</p>
            </div>

            <div class="overflow-hidden rounded-lg bg-white shadow">
                <table v-if="products.data.length > 0" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Название
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Категория
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Цена
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                {{ product.id }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ product.name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                {{ product.category?.name || '—' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                {{ formatPrice(product.price) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <div class="flex gap-2">
                                    <Link
                                        :href="`/admin/products/${product.id}/edit`"
                                        class="rounded bg-blue-600 px-3 py-1 text-white transition-colors hover:bg-blue-700"
                                    >
                                        Изменить
                                    </Link>
                                    <button
                                        @click="confirmDelete(product)"
                                        class="rounded bg-red-600 px-3 py-1 text-white transition-colors hover:bg-red-700"
                                    >
                                        Удалить
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="p-12 text-center text-gray-500">
                    Ничего не найдено
                </div>
            </div>

            <div v-if="products.data.length > 0" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-gray-600">
                    Показано <span class="font-semibold text-gray-900">{{ products.from }}</span> -
                    <span class="font-semibold text-gray-900">{{ products.to }}</span> из
                    <span class="font-semibold text-gray-900">{{ products.total }}</span> товаров
                </div>
                <div class="flex flex-wrap gap-2">
                    <template v-for="link in products.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                'rounded-lg px-4 py-2 text-sm font-semibold transition-all shadow-sm',
                                link.active
                                    ? 'bg-indigo-600 text-white shadow-md hover:bg-indigo-700'
                                    : 'bg-white text-gray-700 hover:bg-gray-50 hover:text-gray-900',
                            ]"
                            :preserve-state="true"
                            :preserve-scroll="true"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            :class="[
                                'rounded-lg px-4 py-2 text-sm font-semibold',
                                'bg-gray-100 text-gray-400 cursor-not-allowed',
                            ]"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>

            <div
                v-if="showDeleteModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
                @click.self="showDeleteModal = false"
            >
                <div class="w-full max-w-md transform rounded-xl bg-white p-6 shadow-2xl transition-all">
                    <div class="mb-4 flex items-center gap-4">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">
                                Удалить товар?
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Действие нельзя отменить
                            </p>
                        </div>
                    </div>
                    <p class="mb-6 text-sm leading-relaxed text-gray-600">
                        Удалить <span class="font-semibold text-gray-900">"{{ productToDelete?.name }}"</span>?
                    </p>
                    <div class="flex gap-3">
                        <button
                            @click="cancelDelete"
                            class="flex-1 rounded-lg border-2 border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition-all hover:bg-gray-50 hover:border-gray-400"
                        >
                            Отмена
                        </button>
                        <button
                            @click="handleDelete"
                            :disabled="deleting"
                            class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition-all hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md"
                        >
                            <svg v-if="deleting" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ deleting ? 'Удаление...' : 'Удалить' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
