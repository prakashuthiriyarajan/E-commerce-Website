# E-Shop - Laravel E-Commerce Platform

## 📋 Project Overview

E-Shop is a full-featured e-commerce platform built with Laravel 12, featuring a complete admin panel, user shopping interface, cart management, wishlist functionality, and order processing system. The application supports both traditional email/password authentication and Google OAuth login.

## 🚀 Key Features

### User Features
- **Product Browsing**: Browse products with search, filtering, and sorting capabilities
- **Shopping Cart**: Add products to cart, update quantities, and manage items
- **Wishlist**: Save favorite products for later
- **Quick Purchase**: "Buy Now" feature for instant checkout
- **Order Management**: View order history and track order status
- **Address Management**: Save multiple delivery addresses
- **Secure Checkout**: Complete order placement with address selection
- **Google OAuth**: Login with Google account
- **Responsive Design**: Mobile-friendly interface using Bootstrap 5

### Admin Features
- **Dashboard**: Overview with statistics (products, categories, orders, stock alerts)
- **Product Management**: Create, edit, delete products with image upload
- **Category Management**: Manage product categories
- **Order Management**: View orders, update order status (On Process, Shipped, Delivered)
- **Search & Filter**: Advanced filtering for products and orders
- **Stock Management**: Track inventory and out-of-stock items

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 12.x
- **PHP**: 8.2+
- **Database**: SQLite (default), MySQL/PostgreSQL supported
- **Authentication**: Laravel Auth with custom middleware
- **OAuth**: Laravel Socialite (Google)
- **Image Processing**: Intervention Image 3.11

### Frontend
- **CSS Framework**: Bootstrap 5.3
- **JavaScript**: Vanilla JS + Bootstrap Bundle
- **Icons**: Font Awesome 6.0
- **Build Tool**: Vite 7 with Tailwind CSS 4.0
- **UI Components**: SweetAlert2 for alerts

## 📦 Installation Guide

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite extension enabled (or MySQL/PostgreSQL)

### Step-by-Step Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd eshop
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Database setup**
```bash
# Create SQLite database
touch database/database.sqlite

# Run migrations
php artisan migrate
```

6. **Create storage link**
```bash
php artisan storage:link
```

7. **Seed admin user**
```bash
php artisan db:seed --class=AdminSeeder
```

Default admin credentials:
- Email: `admin@eshop.com`
- Password: `admin123`

8. **Configure Google OAuth (Optional)**

Add to `.env`:
```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

9. **Build assets**
```bash
npm run build
# or for development
npm run dev
```

10. **Start the application**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## 🗂️ Project Structure

```
eshop/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin controllers
│   │   │   │   ├── AdminController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   └── OrderController.php
│   │   │   ├── Auth/               # Authentication controllers
│   │   │   │   ├── LoginController.php
│   │   │   │   └── GoogleAuthController.php
│   │   │   ├── AddressController.php
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── HomeController.php
│   │   │   ├── UserDashboardController.php
│   │   │   └── WishlistController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Product.php
│       ├── Category.php
│       ├── Cart.php
│       ├── Wishlist.php
│       ├── Address.php
│       ├── Order.php
│       └── OrderItem.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── AdminSeeder.php
├── resources/
│   ├── views/
│   │   ├── admin/                  # Admin panel views
│   │   ├── auth/                   # Authentication views
│   │   ├── frontend/               # User interface views
│   │   ├── cart/                   # Shopping cart views
│   │   ├── checkout/               # Checkout views
│   │   └── layouts/
│   │       ├── app.blade.php       # User layout
│   │       └── admin.blade.php     # Admin layout
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
├── routes/
│   └── web.php
└── public/
    └── storage/                    # Product images
```

## 🔐 Authentication System

### Single Login System
The application uses a unified authentication system for both users and admins:

- **User Registration**: Creates regular users (`is_admin = 0`)
- **Admin Login**: Checks `is_admin = 1` flag and redirects to admin dashboard
- **User Login**: Regular users redirected to home page
- **Google OAuth**: Supports Google login for both user types

### Middleware
- `auth`: Ensures user is authenticated
- `admin`: Ensures user is authenticated AND is an admin

## 🗄️ Database Schema

### Users Table
- id, name, email, password, is_admin, google_id, timestamps

### Products Table
- id, name, category_id, price, description, image, stock_count, timestamps

### Categories Table
- id, name, description, timestamps

### Carts Table
- id, user_id, product_id, quantity, timestamps

### Wishlists Table
- id, user_id, product_id, timestamps

### Addresses Table
- id, user_id, address_line1, address_line2, city, state, pincode, phone, timestamps

### Orders Table
- id, user_id, address_id, total_amount, status, timestamps

### Order Items Table
- id, order_id, product_id, quantity, price, timestamps

## 🌐 Routes Overview

### Public Routes
- `GET /` - Home page with product listing
- `GET /products/{id}` - Product detail page
- `GET /login` - Login page
- `GET /register` - Registration page

### User Routes (Authenticated)
- `GET /cart` - Shopping cart
- `POST /cart/add/{product}` - Add to cart
- `POST /cart/buy-now/{product}` - Quick purchase
- `GET /wishlist` - Wishlist
- `GET /addresses` - Address management
- `GET /checkout` - Checkout page
- `POST /checkout/place-order` - Place order
- `GET /my-orders` - Order history

### Admin Routes (Authenticated + Admin)
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/products` - Product management
- `GET /admin/categories` - Category management
- `GET /admin/orders` - Order management
- `PATCH /admin/orders/{id}/status` - Update order status

## 💡 Key Features Explained

### Shopping Cart
- Add multiple products with quantity control
- Update quantities inline
- Stock validation before checkout
- Automatic subtotal calculation
- Free shipping on orders > ₹500
- 18% tax calculation

### Checkout Process
1. User selects/adds delivery address
2. Reviews order items
3. Sees price breakdown (subtotal, shipping, tax)
4. Places order with single click
5. Stock automatically decremented
6. Cart cleared after successful order

### Order Status Management (Admin)
- **On Process**: Initial status after order placement
- **Shipped**: Order dispatched
- **Delivered**: Order completed

### Product Management
- Image upload with validation (max 2MB)
- Stock tracking
- Category assignment
- Search and filter functionality

## 🎨 UI/UX Features

### User Interface
- Modern gradient design
- Card-based product display
- Responsive navigation
- Alert notifications (success, error, info)
- Breadcrumb navigation
- Badge indicators (stock status, cart count)

### Admin Interface
- Fixed sidebar navigation
- Statistics dashboard
- Data tables with pagination
- Modal forms for quick actions
- SweetAlert2 confirmations
- Inline editing capabilities

## 🔧 Configuration

### Environment Variables
```env
APP_NAME=E-Shop
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# OR for MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=eshop
# DB_USERNAME=root
# DB_PASSWORD=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

### File Storage
- Product images stored in: `storage/app/public/products/`
- Accessible via: `/storage/products/`
- Supported formats: JPEG, PNG, GIF, JPG
- Maximum size: 2MB

## 🚦 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=ExampleTest
```

## 📝 Development Notes

### Adding New Products (Admin)
1. Navigate to Admin → Products → Add Product
2. Fill in product details
3. Upload product image
4. Set stock quantity
5. Assign category
6. Save

### Managing Orders (Admin)
1. View all orders in Admin → Orders
2. Click on order to view details
3. Update status as needed
4. Customer receives updated status

### Stock Management
- Products with `stock_count = 0` are marked "Out of Stock"
- Out of stock products cannot be added to cart
- Stock automatically decremented on order placement
- Admin dashboard shows out-of-stock alerts

## 🔒 Security Features

- CSRF protection on all forms
- Password hashing with bcrypt
- SQL injection prevention via Eloquent ORM
- XSS protection with Blade templating
- Authentication middleware
- Role-based access control (admin middleware)
- Session management

## 🐛 Troubleshooting

### Common Issues

**Issue**: Images not displaying
```bash
php artisan storage:link
```

**Issue**: Permission denied on storage folder
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

**Issue**: Google OAuth not working
- Verify credentials in `.env`
- Check redirect URI matches Google Console
- Ensure `http://` or `https://` protocol is correct

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Bootstrap Documentation](https://getbootstrap.com/docs)
- [Laravel Socialite](https://laravel.com/docs/socialite)
- [Intervention Image](http://image.intervention.io/)

## 👥 User Roles

### Regular User Permissions
- Browse products
- Manage cart and wishlist
- Place orders
- View order history
- Manage delivery addresses

### Admin Permissions
- All user permissions
- Access admin dashboard
- Manage products
- Manage categories
- View all orders
- Update order status
- View analytics

## 🔄 Workflow

### Customer Purchase Flow
1. Browse products → Add to cart/wishlist
2. View cart → Update quantities
3. Proceed to checkout
4. Select/add delivery address
5. Review order summary
6. Place order
7. Track order status

### Admin Order Processing Flow
1. Receive new order (On Process)
2. Prepare order for shipment
3. Update status to "Shipped"
4. Order delivered
5. Update status to "Delivered"

## 📊 Business Logic

### Pricing Calculation
```
Subtotal = Sum of (Product Price × Quantity)
Shipping = ₹50 (Free if subtotal > ₹500)
Tax = Subtotal × 18%
Total = Subtotal + Shipping + Tax
```

### Stock Management Rules
- Cannot add out-of-stock products to cart
- Cannot order more than available stock
- Stock decremented immediately on order placement
- Admin receives alerts for out-of-stock items

## 🎯 Future Enhancement Ideas

- Payment gateway integration (Razorpay, Stripe)
- Email notifications for orders
- Product reviews and ratings
- Advanced search with filters
- Coupon/discount system
- Order cancellation
- Invoice generation
- Multiple product images
- Product variants (size, color)
- Sales analytics and reports

## 📄 License

This project is open-source and available under the MIT License.

## 👨‍💻 Contributing

Contributions are welcome! Please follow these steps:
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📞 Support

For issues and questions:
- Check the troubleshooting section
- Review Laravel documentation
- Open an issue on the repository

---

