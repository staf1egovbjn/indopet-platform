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
            console.log(`Fetching product with ID: ${id}`);
            console.log(`API URL: /products/${id}`);
            const response = await apiClient.get(`/products/${id}`);
            console.log('API Response:', response);
            console.log('API Response Data:', response.data);
            return response.data;
        } catch (error) {
            console.error('Error fetching product:', error);
            console.error('Error details:', error.response);
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
