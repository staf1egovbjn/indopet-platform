import axios from 'axios';

// Base API configuration
const API_BASE_URL = 'https://indopet-platform-production.up.railway.app';

// Create axios instance
const apiClient = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json'
    },
    timeout: 10000 // 10 seconds timeout
});

// Request interceptor untuk menambahkan auth token jika ada
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor untuk handle errors
apiClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response?.status === 401) {
            // Handle unauthorized - redirect to login
            localStorage.removeItem('auth_token');
            // You can add router.push('/login') here later
        }
        return Promise.reject(error);
    }
);

export default apiClient;
export { API_BASE_URL };
