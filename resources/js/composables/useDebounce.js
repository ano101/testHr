import { ref } from 'vue';

export function useDebounce(fn, delay = 300) {
    const timeoutId = ref(null);

    return (...args) => {
        if (timeoutId.value) {
            clearTimeout(timeoutId.value);
        }

        timeoutId.value = setTimeout(() => {
            fn(...args);
        }, delay);
    };
}
