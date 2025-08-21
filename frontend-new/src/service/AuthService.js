import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'https://indopet-platform-production.up.railway.app';

class AuthService {
    constructor() {
        this.baseURL = `${API_BASE_URL}/auth`;
        
        // Add token to all requests if available
        axios.interceptors.request.use((config) => {
            const token = localStorage.getItem('auth_token');
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
            return config;
        });

        // Handle token expiration
        axios.interceptors.response.use(
            (response) => response,
            (error) => {
                if (error.response?.status === 401) {
                    this.logout();
                    window.location.href = '/auth/login';
                }
                return Promise.reject(error);
            }
        );
    }

    async login(credentials) {
        try {
            const response = await axios.post(`${this.baseURL}/login`, credentials);
            if (response.data.success) {
                const { user, token } = response.data.data;
                localStorage.setItem('auth_token', token);
                localStorage.setItem('user', JSON.stringify(user));
                return { success: true, data: response.data.data };
            }
            return { success: false, error: response.data.message };
        } catch (error) {
            return { 
                success: false, 
                error: error.response?.data?.message || 'Login failed'
            };
        }
    }

    async register(userData) {
        try {
            const response = await axios.post(`${this.baseURL}/register`, userData);
            if (response.data.success) {
                const { user, token } = response.data.data;
                localStorage.setItem('auth_token', token);
                localStorage.setItem('user', JSON.stringify(user));
                return { success: true, data: response.data.data };
            }
            return { success: false, error: response.data.message };
        } catch (error) {
            return { 
                success: false, 
                error: error.response?.data?.errors || error.response?.data?.message || 'Registration failed'
            };
        }
    }

    async logout() {
        try {
            await axios.post(`${this.baseURL}/logout`);
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
        }
    }

    async getCurrentUser() {
        try {
            const response = await axios.get(`${this.baseURL}/me`);
            if (response.data.success) {
                const user = response.data.data.user;
                localStorage.setItem('user', JSON.stringify(user));
                return user;
            }
        } catch (error) {
            console.error('Get user error:', error);
            this.logout();
        }
        return null;
    }

    isAuthenticated() {
        return !!localStorage.getItem('auth_token');
    }

    getUser() {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    }

    isAdmin() {
        const user = this.getUser();
        return user?.role === 'admin';
    }

    isCustomer() {
        const user = this.getUser();
        return user?.role === 'customer';
    }

    getToken() {
        return localStorage.getItem('auth_token');
    }
}

export default new AuthService();
