import apiClient from '@/config/api.js';

class AdminCategoryService {
    async getCategories(params = {}) {
        try {
            const response = await apiClient.get('/admin/categories', { params });
            return response.data;
        } catch (error) {
            console.error('Error fetching admin categories:', error);
            throw error;
        }
    }

    async createCategory(categoryData) {
        try {
            const response = await apiClient.post('/admin/categories', categoryData);
            return response.data;
        } catch (error) {
            console.error('Error creating category:', error);
            throw error;
        }
    }

    async updateCategory(id, categoryData) {
        try {
            const response = await apiClient.put(`/admin/categories/${id}`, categoryData);
            return response.data;
        } catch (error) {
            console.error('Error updating category:', error);
            throw error;
        }
    }

    async deleteCategory(id) {
        try {
            const response = await apiClient.delete(`/admin/categories/${id}`);
            return response.data;
        } catch (error) {
            console.error('Error deleting category:', error);
            throw error;
        }
    }
}

export default new AdminCategoryService();
