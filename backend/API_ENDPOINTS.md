# IndoPet Platform API Endpoints

Base URL: http://localhost:8000/api/v1

## Public Endpoints

### Categories
- GET /categories - Get all categories
- GET /categories/{id} - Get single category

### Products  
- GET /products - Get all products (with filters)
- GET /products/{id} - Get single product
- GET /products/featured - Get featured products
- GET /products/category/{category_id} - Get products by category

### Articles
- GET /articles - Get all articles
- GET /articles/{id} - Get single article
- GET /articles/featured - Get featured articles

## Protected Endpoints (require auth:sanctum)

### User
- GET /user - Get authenticated user info

### Cart
- GET /cart - Get user cart
- POST /cart/add - Add item to cart
- PUT /cart/update/{id} - Update cart item
- DELETE /cart/remove/{id} - Remove cart item
- DELETE /cart/clear - Clear cart

### Orders
- GET /orders - Get user orders
- POST /orders - Create new order
- GET /orders/{id} - Get single order
- POST /orders/{id}/cancel - Cancel order

## Admin Endpoints (require auth:sanctum + admin role)

### Categories Management
- POST /admin/categories - Create category
- PUT /admin/categories/{id} - Update category
- DELETE /admin/categories/{id} - Delete category

### Products Management  
- POST /admin/products - Create product
- PUT /admin/products/{id} - Update product
- DELETE /admin/products/{id} - Delete product

### Articles Management
- POST /admin/articles - Create article
- PUT /admin/articles/{id} - Update article
- DELETE /admin/articles/{id} - Delete article

### Orders Management
- GET /admin/orders - Get all orders
- PUT /admin/orders/{id}/status - Update order status

## Sample API Calls

### Get Categories
```bash
curl -X GET http://localhost:8000/api/v1/categories
```

### Get Products
```bash
curl -X GET http://localhost:8000/api/v1/products
```

### Get Featured Products
```bash
curl -X GET http://localhost:8000/api/v1/products/featured
```
