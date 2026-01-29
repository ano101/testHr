import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { useAuth } from '@/composables/useAuth';

export function useProductApi() {
    const { getAuthHeaders } = useAuth();

    /**
     * Создание нового продукта
     */
    const createProduct = async (formData) => {
        const response = await fetch('/api/products', {
            method: 'POST',
            headers: getAuthHeaders(),
            body: JSON.stringify(formData),
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw {
                errors: errorData.errors || null,
                message: errorData.message || 'Ошибка при создании товара',
            };
        }

        return await response.json();
    };

    /**
     * Обновление существующего продукта
     */
    const updateProduct = async (productId, formData) => {
        const response = await fetch(`/api/products/${productId}`, {
            method: 'PUT',
            headers: getAuthHeaders(),
            body: JSON.stringify(formData),
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw {
                errors: errorData.errors || null,
                message: errorData.message || 'Ошибка при обновлении товара',
            };
        }

        return await response.json();
    };

    /**
     * Удаление продукта
     */
    const deleteProduct = async (productId) => {
        const response = await fetch(`/api/products/${productId}`, {
            method: 'DELETE',
            headers: getAuthHeaders(),
        });

        if (!response.ok) {
            throw new Error('Ошибка при удалении товара');
        }

        return true;
    };

    return {
        createProduct,
        updateProduct,
        deleteProduct,
    };
}

/**
 * Composable для формы продукта с валидацией и отправкой
 */
export function useProductForm(product = null, categories = []) {
    const { createProduct, updateProduct } = useProductApi();

    const isEditMode = !!product;

    const form = reactive({
        name: product?.name || '',
        category_id: product?.category_id || '',
        price: product?.price || '',
        description: product?.description || '',
    });

    const loading = ref(false);

    const errors = reactive({
        general: '',
        name: '',
        category_id: '',
        price: '',
        description: '',
    });

    const resetErrors = () => {
        errors.general = '';
        errors.name = '';
        errors.category_id = '';
        errors.price = '';
        errors.description = '';
    };

    const validateForm = () => {
        resetErrors();

        let isValid = true;

        if (!form.name.trim()) {
            errors.name = 'Название обязательно для заполнения';
            isValid = false;
        }

        if (!form.category_id) {
            errors.category_id = 'Выберите категорию';
            isValid = false;
        }

        if (!form.price || form.price <= 0) {
            errors.price = 'Цена должна быть больше 0';
            isValid = false;
        }

        if (!form.description.trim()) {
            errors.description = 'Описание обязательно для заполнения';
            isValid = false;
        }

        return isValid;
    };

    const handleSubmit = async () => {
        if (!validateForm()) {
            return;
        }

        loading.value = true;
        resetErrors();

        try {
            if (isEditMode) {
                await updateProduct(product.id, form);
            } else {
                await createProduct(form);
            }

            // Success - redirect to products list
            router.visit('/admin/products');
        } catch (error) {
            if (error.errors) {
                // Laravel validation errors
                Object.keys(error.errors).forEach(key => {
                    if (errors[key] !== undefined) {
                        errors[key] = error.errors[key][0];
                    }
                });
            } else {
                errors.general = error.message || 'Произошла ошибка при сохранении товара';
            }
        } finally {
            loading.value = false;
        }
    };

    return {
        form,
        loading,
        errors,
        isEditMode,
        handleSubmit,
        validateForm,
        resetErrors,
    };
}

/**
 * Composable для удаления продукта с подтверждением
 */
export function useProductDelete() {
    const { deleteProduct } = useProductApi();

    const showDeleteModal = ref(false);
    const productToDelete = ref(null);
    const deleting = ref(false);

    const confirmDelete = (product) => {
        productToDelete.value = product;
        showDeleteModal.value = true;
    };

    const cancelDelete = () => {
        showDeleteModal.value = false;
        productToDelete.value = null;
    };

    const handleDelete = async () => {
        if (!productToDelete.value) return;

        deleting.value = true;

        try {
            await deleteProduct(productToDelete.value.id);

            showDeleteModal.value = false;
            productToDelete.value = null;

            router.reload({
                only: ['products'],
            });
        } catch (error) {
            alert('Ошибка при удалении товара: ' + error.message);
        } finally {
            deleting.value = false;
        }
    };

    return {
        showDeleteModal,
        productToDelete,
        deleting,
        confirmDelete,
        cancelDelete,
        handleDelete,
    };
}
