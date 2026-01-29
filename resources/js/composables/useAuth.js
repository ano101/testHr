import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const token = ref(localStorage.getItem('auth_token') || null);
const user = ref(JSON.parse(localStorage.getItem('auth_user') || 'null'));

export function useAuth() {
    const isAuthenticated = computed(() => !!token.value);

    const login = async (email, password) => {
        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Ошибка входа');
            }

            const data = await response.json();

            token.value = data.token;
            user.value = data.user;

            localStorage.setItem('auth_token', data.token);
            localStorage.setItem('auth_user', JSON.stringify(data.user));

            return { success: true };
        } catch (error) {
            return { success: false, error: error.message };
        }
    };

    const logout = () => {
        token.value = null;
        user.value = null;
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');

        router.post('/logout');
    };

    const getAuthHeaders = () => {
        return {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token.value}`,
        };
    };

    const checkAuth = async () => {
        if (!token.value) {
            return false;
        }

        try {
            const response = await fetch('/api/me', {
                method: 'GET',
                headers: getAuthHeaders(),
            });

            if (!response.ok) {
                // Токен невалидный, очищаем данные
                token.value = null;
                user.value = null;
                localStorage.removeItem('auth_token');
                localStorage.removeItem('auth_user');
                return false;
            }

            const data = await response.json();
            user.value = data.user;
            localStorage.setItem('auth_user', JSON.stringify(data.user));
            return true;
        } catch (error) {
            // При ошибке очищаем данные
            token.value = null;
            user.value = null;
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');
            return false;
        }
    };

    return {
        token,
        user,
        isAuthenticated,
        login,
        logout,
        getAuthHeaders,
        checkAuth,
    };
}
