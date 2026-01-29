import { router } from '@inertiajs/vue3';
import { useAuth } from '@/composables/useAuth';

export function requireAuth() {
    const { checkAuth } = useAuth();

    return checkAuth().then((isAuth) => {
        if (!isAuth) {
            router.visit('/login');
            return false;
        }
        return true;
    });
}
