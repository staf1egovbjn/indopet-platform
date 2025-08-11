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
            const response = await apiClient.get(`/articles/${id}`);
            return response.data;
        } catch (error) {
            console.error('Error fetching article:', error);
            throw error;
        }
    }
}

export default new ArticleService();
