import axios from 'axios';

// Создаем глобальный экземпляр axios
window.axios = axios;

// Базовая настройка
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';

// Автоматически добавляем токен из localStorage к каждому запросу
window.axios.interceptors.request.use((config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Обработка 401 ошибок (токен истек или невалиден)
window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Очищаем невалидный токен
            localStorage.removeItem('auth_token');
            delete window.axios.defaults.headers.common['Authorization'];
        }
        return Promise.reject(error);
    }
);

