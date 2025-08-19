import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1';

class CartService {
    constructor() {
        this.baseURL = `${API_BASE_URL}/cart`;
    }

    /**
     * Get user's cart items
     */
    async getCartItems() {
        try {
            const response = await axios.get(this.baseURL);
            return response.data;
        } catch (error) {
            console.error('Error fetching cart items:', error);
            throw error;
        }
    }

    /**
     * Add item to cart
     */
    async addToCart(productId, quantity = 1) {
        try {
            const response = await axios.post(`${this.baseURL}/add`, {
                product_id: productId,
                quantity: quantity
            });
            return response.data;
        } catch (error) {
            console.error('Error adding to cart:', error);
            throw error;
        }
    }

    /**
     * Update cart item quantity
     */
    async updateCartItem(cartItemId, quantity) {
        try {
            const response = await axios.put(`${this.baseURL}/update/${cartItemId}`, {
                quantity: quantity
            });
            return response.data;
        } catch (error) {
            console.error('Error updating cart item:', error);
            throw error;
        }
    }

    /**
     * Remove item from cart
     */
    async removeFromCart(cartItemId) {
        try {
            const response = await axios.delete(`${this.baseURL}/remove/${cartItemId}`);
            return response.data;
        } catch (error) {
            console.error('Error removing from cart:', error);
            throw error;
        }
    }

    /**
     * Clear all cart items
     */
    async clearCart() {
        try {
            const response = await axios.delete(`${this.baseURL}/clear`);
            return response.data;
        } catch (error) {
            console.error('Error clearing cart:', error);
            throw error;
        }
    }

    /**
     * Get cart count (for header badge)
     */
    async getCartCount() {
        try {
            const response = await this.getCartItems();
            return response.data?.count || 0;
        } catch (error) {
            console.error('Error getting cart count:', error);
            return 0;
        }
    }
}

export default new CartService();
