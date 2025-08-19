import apiClient from '@/config/api.js';

class ArticleService {
    async getArticles(params = {}) {
        try {
            const response = await apiClient.get('/articles', { params });
            return response.data;
        } catch (error) {
            console.error('Error fetching articles:', error);
            throw error;
        }
    }

    async getFeaturedArticles() {
        try {
            const response = await apiClient.get('/articles/featured');
            return response.data;
        } catch (error) {
            console.error('Error fetching featured articles:', error);
            throw error;
        }
    }

    async getArticleById(id) {
        try {
            console.log(`Fetching article with ID: ${id}`);
            console.log(`API URL: /articles/${id}`);
            const response = await apiClient.get(`/articles/${id}`);
            console.log('API Response:', response);
            console.log('API Response Data:', response.data);
            return response.data;
        } catch (error) {
            console.error('Error fetching article:', error);
            console.error('Error details:', error.response);
            throw error;
        }
    }
}

export default new ArticleService();
