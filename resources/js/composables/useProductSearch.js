import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useDebounce } from '@/composables/useDebounce';

export function useProductSearch(initialFilters = {}, options = {}) {
    const selectedCategory = ref(initialFilters.category_id || '');
    const searchQuery = ref(initialFilters.search || '');
    const isSearching = ref(false);

    const defaultOptions = {
        searchRoute: '/search',
        homeRoute: '/',
        useSearchRoute: true,
    };

    const config = { ...defaultOptions, ...options };

    const performSearch = () => {
        isSearching.value = true;

        const params = {};

        if (searchQuery.value) {
            params.search = searchQuery.value;
        }

        if (selectedCategory.value) {
            params.category_id = selectedCategory.value;
        }

        const route = config.useSearchRoute
            ? (searchQuery.value ? config.searchRoute : config.homeRoute)
            : config.homeRoute;

        router.get(route, params, {
            preserveState: true,
            preserveScroll: true,
            only: ['products'],
            onFinish: () => {
                isSearching.value = false;
            },
        });
    };

    const debouncedSearch = useDebounce(performSearch, 500);

    const filterByCategory = () => {
        performSearch();
    };

    const clearSearch = () => {
        searchQuery.value = '';
        performSearch();
    };

    return {
        selectedCategory,
        searchQuery,
        isSearching,
        debouncedSearch,
        filterByCategory,
        clearSearch,
    };
}
