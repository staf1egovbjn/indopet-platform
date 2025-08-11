import apiClient from '@/config/api.js';

class CategoryService {
    async getCategories() {
        try {
            const response = await apiClient.get('/categories');
            return response.data;
        } catch (error) {
            console.error('Error fetching categories:', error);
            throw error;
        }
    }

    async getCategoryById(id) {
        try {
            const response = await apiClient.get(`/categories/${id}`);
            return response.data;
        } catch (error) {
            console.error('Error fetching category:', error);
            throw error;
        }
    }
}

export default new CategoryService();
