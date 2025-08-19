import apiClient from '@/config/api.js';

class AdminProductService {
    async getProducts(params = {}) {
        try {
            const response = await apiClient.get('/admin/products', { params });
            return response.data;
        } catch (error) {
            console.error('Error fetching admin products:', error);
            throw error;
        }
    }

    async createProduct(productData) {
        try {
            const response = await apiClient.post('/admin/products', productData);
            return response.data;
        } catch (error) {
            console.error('Error creating product:', error);
            throw error;
        }
    }

    async updateProduct(id, productData) {
        try {
            const response = await apiClient.put(`/admin/products/${id}`, productData);
            return response.data;
        } catch (error) {
            console.error('Error updating product:', error);
            throw error;
        }
    }

    async deleteProduct(id) {
        try {
            const response = await apiClient.delete(`/admin/products/${id}`);
            return response.data;
        } catch (error) {
            console.error('Error deleting product:', error);
            throw error;
        }
    }
}

export default new AdminProductService();
