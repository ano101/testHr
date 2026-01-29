<script setup>
import { Link } from '@inertiajs/vue3';
import { useProductSearch } from '@/composables/useProductSearch';
import { useAuth } from '@/composables/useAuth';
import { formatPrice } from '@/utils/formatters';

const props = defineProps({
    products: Object,
    categories: Array,
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const { isAuthenticated } = useAuth();

const {
    selectedCategory,
    searchQuery,
    isSearching,
    debouncedSearch,
    filterByCategory,
    clearSearch,
} = useProductSearch(props.filters);
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Каталог товаров</h1>
                    <Link
                        :href="isAuthenticated ? '/admin/products' : '/login'"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700"
                    >
                        {{ isAuthenticated ? 'Админка' : 'Войти' }}
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-8 rounded-xl bg-white p-6 shadow-md">
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

            <div v-if="products.data.length > 0" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="product in products.data"
                    :key="product.id"
                    class="group relative overflow-hidden rounded-xl bg-white shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
                >
                    <div class="absolute right-4 top-4 z-10">
                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-800 shadow-sm">
                            {{ product.category?.name || 'Разное' }}
                        </span>
                    </div>

                    <div class="p-6">
                        <h3 class="mb-3 text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                            {{ product.name }}
                        </h3>

                        <div class="mb-4 flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-gray-900">
                                {{ formatPrice(product.price) }}
                            </span>
                        </div>

                        <p class="mb-6 line-clamp-3 text-sm leading-relaxed text-gray-600">
                            {{ product.description }}
                        </p>

                        <Link
                            :href="`/products/${product.id}`"
                            class="group/btn flex w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:from-indigo-700 hover:to-indigo-800 hover:shadow-md"
                        >
                            <span>Подробнее</span>
                            <svg class="h-4 w-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </article>
            </div>

            <div v-else class="rounded-xl bg-white p-16 text-center shadow-md">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Ничего не найдено</h3>
                <p class="mt-2 text-sm text-gray-500">Попробуйте изменить фильтры</p>
            </div>

            <div v-if="products.data.length > 0" class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
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
        </main>
    </div>
</template>


<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
