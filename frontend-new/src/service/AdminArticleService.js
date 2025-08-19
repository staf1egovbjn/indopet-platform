import apiClient from '@/config/api.js';

class AdminArticleService {
    async getArticles(params = {}) {
        try {
            const response = await apiClient.get('/admin/articles', { params });
            return response.data;
        } catch (error) {
            console.error('Error fetching admin articles:', error);
            throw error;
        }
    }

    async createArticle(articleData) {
        try {
            const response = await apiClient.post('/admin/articles', articleData);
            return response.data;
        } catch (error) {
            console.error('Error creating article:', error);
            throw error;
        }
    }

    async updateArticle(id, articleData) {
        try {
            const response = await apiClient.put(`/admin/articles/${id}`, articleData);
            return response.data;
        } catch (error) {
            console.error('Error updating article:', error);
            throw error;
        }
    }

    async deleteArticle(id) {
        try {
            const response = await apiClient.delete(`/admin/articles/${id}`);
            return response.data;
        } catch (error) {
            console.error('Error deleting article:', error);
            throw error;
        }
    }
}

export default new AdminArticleService();
