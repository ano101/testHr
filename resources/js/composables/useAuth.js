import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const token = ref(localStorage.getItem('auth_token') || null);
const user = ref(null);

export function useAuth() {
    const isAuthenticated = computed(() => !!token.value);

    const login = async (email, password) => {
        try {
            const response = await axios.post('/api/login', { email, password });

            token.value = response.data.token;
            user.value = response.data.user;

            // Сохраняем только токен, юзер будет загружаться при необходимости
            localStorage.setItem('auth_token', response.data.token);

            return { success: true };
        } catch (error) {
            const message = error.response?.data?.message || 'Ошибка входа';
            return { success: false, error: message };
        }
    };

    const logout = async () => {
        try {
            // Удаляем токен на бэкенде через API
            if (token.value) {
                await axios.post('/api/logout');
            }
        } catch (error) {
            console.error('Ошибка при logout:', error);
        } finally {
            // Очищаем локальное состояние
            token.value = null;
            user.value = null;
            localStorage.removeItem('auth_token');

            // Редирект на главную
            router.visit('/');
        }
    };

    const checkAuth = async () => {
        if (!token.value) {
            user.value = null;
            return false;
        }

        try {
            const response = await axios.get('/api/me');
            user.value = response.data.user;
            return true;
        } catch (error) {
            // Токен невалидный, очищаем
            token.value = null;
            user.value = null;
            localStorage.removeItem('auth_token');
            return false;
        }
    };

    return {
        token,
        user,
        isAuthenticated,
        login,
        logout,
        checkAuth,
    };
}
