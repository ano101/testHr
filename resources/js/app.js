import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'

router.on('before', (event) => {
    const token = localStorage.getItem('auth_token');

    if (token) {
        event.detail.visit.headers = {
            ...event.detail.visit.headers,
            'Authorization': `Bearer ${token}`,
        };
    }
});

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})
