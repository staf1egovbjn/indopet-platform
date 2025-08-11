import apiClient from '@/config/api.js';

class ProductService {
    async getProducts(params = {}) {
        try {
            const response = await apiClient.get('/products', { params });
            return response.data;
        } catch (error) {
            console.error('Error fetching products:', error);
            throw error;
        }
    }

    async getFeaturedProducts() {
        try {
            const response = await apiClient.get('/products/featured');
            return response.data;
        } catch (error) {
            console.error('Error fetching featured products:', error);
            throw error;
        }
    }

    async getProductById(id) {
        try {
            const response = await apiClient.get(`/products/${id}`);
            return response.data;
        } catch (error) {
            console.error('Error fetching product:', error);
            throw error;
        }
    }

    async getProductsByCategory(categoryId, params = {}) {
        try {
            const response = await apiClient.get(`/products?category_id=${categoryId}`, { params });
            return response.data;
        } catch (error) {
            console.error('Error fetching products by category:', error);
            throw error;
        }
    }
}

export default new ProductService();
