import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = localStorage.getItem('auth_token');
if (token) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

window.updateAxiosToken = (token) => {
    if (token) {
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        localStorage.setItem('auth_token', token);
    } else {
        delete window.axios.defaults.headers.common['Authorization'];
        localStorage.removeItem('auth_token');
    }
};

