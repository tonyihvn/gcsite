# GINTEC Solutions - Complete Website Project

A comprehensive PHP web application for GINTEC Solutions Consults Ltd - a leading provider of IT solutions and consultancy services.

## Project Overview

This is a full-stack PHP application featuring:

### **Core Features**
- ✅ Professional website with modern design
- ✅ User authentication and registration
- ✅ Admin dashboard with comprehensive management tools
- ✅ Product and service management
- ✅ Subscription and invoice management
- ✅ Blog and FAQ system
- ✅ Contact form with feedback management
- ✅ AI-powered chat widget
- ✅ User profile management
- ✅ Payment tracking

## Directory Structure

```
gintec/
├── public/                          # Publicly accessible files
│   ├── index.php                   # Application entry point
│   └── assets/
│       ├── css/                    # Stylesheets
│       │   ├── style.css          # Main styles
│       │   ├── admin.css          # Admin panel styles
│       │   └── bootstrap.min.css  # Bootstrap framework
│       ├── js/                     # JavaScript files
│       │   ├── main.js            # Main functionality
│       │   ├── admin.js           # Admin panel scripts
│       │   ├── chat.js            # AI chat widget
│       │   └── bootstrap.bundle.min.js
│       ├── images/                # Brand images
│       └── uploads/               # User uploaded files
├── app/
│   ├── controllers/               # Application controllers
│   │   ├── HomeController.php    # Public site controller
│   │   ├── AuthController.php    # Authentication controller
│   │   ├── UserController.php    # User dashboard controller
│   │   ├── AdminController.php   # Admin panel controller
│   │   └── ApiController.php     # API endpoints
│   ├── models/                   # Data models
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Service.php
│   │   ├── Subscription.php
│   │   ├── Invoice.php
│   │   ├── Page.php
│   │   ├── Feedback.php
│   │   ├── Setting.php
│   │   ├── Faq.php
│   │   ├── Slide.php
│   │   ├── BlogPost.php
│   │   └── ChatSession.php
│   ├── views/                    # View templates
│   │   ├── layouts/
│   │   │   ├── app.php          # Main layout
│   │   │   └── admin.php        # Admin layout
│   │   ├── home/                # Public pages
│   │   ├── auth/                # Authentication pages
│   │   ├── user/                # User dashboard pages
│   │   ├── admin/               # Admin pages
│   │   └── errors/              # Error pages
│   ├── middleware/              # Middleware classes
│   │   └── AuthMiddleware.php
│   └── helpers/
│       └── functions.php         # Helper functions
├── core/                          # Framework core
│   ├── DotEnv.php               # Environment loader
│   ├── Database.php             # Database connection
│   ├── Controller.php           # Base controller
│   ├── Model.php                # Base model
│   ├── Router.php               # Request router
│   └── Security.php             # Security utilities
├── config/                        # Configuration files
│   ├── app.php                  # Application config
│   └── database.php             # Database config
├── database/
│   ├── migrations/              # Database schema
│   │   └── 001_create_tables.php
│   ├── seeds/                   # Sample data
│   └── Migrator.php             # Migration runner
├── .env                          # Environment variables
├── .env.example                 # Environment template
└── README.md                    # This file

```

## Installation & Setup

### 1. **Prerequisites**
- PHP 7.4 or higher
- MySQL/MariaDB 5.7 or higher
- Apache/Nginx web server
- Composer (optional, for dependency management)

### 2. **Clone/Download Project**
```bash
cd your-projects-folder
# Files are already created in: c:\Users\Ogochukwu\Desktop\PROJECTS\PHP\gintec
```

### 3. **Configure Environment**
```bash
# Copy environment file
cp .env.example .env

# Edit .env with your settings:
# - Database credentials
# - Application URL
# - Email configuration
# - API keys
```

### 4. **Create Database**
```sql
CREATE DATABASE gintec_solutions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'gintec_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON gintec_solutions.* TO 'gintec_user'@'localhost';
FLUSH PRIVILEGES;
```

### 5. **Run Migrations**
```php
// Create a CLI script to run migrations
// Visit: http://localhost/gintec/migrate (after creating migration endpoint)

// Or manually import the SQL from database/migrations/001_create_tables.php
```

### 6. **Configure Web Server**

**Apache (.htaccess)**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?path=$1 [QSA,L]
</IfModule>
```

**Nginx**
```nginx
location / {
    if (!-e $request_filename) {
        rewrite ^(.*)$ /index.php?path=$1 last;
    }
}
```

### 7. **Set File Permissions**
```bash
chmod -R 755 public/assets/uploads
chmod 644 .env
```

## Usage

### **Access the Application**

- **Public Site**: `http://localhost/gintec/`
- **Admin Panel**: `http://localhost/gintec/admin` (login required)
- **User Dashboard**: `http://localhost/gintec/dashboard` (login required)

### **Default Routes**

#### **Public Routes**
- `/` - Homepage
- `/about` - About page
- `/services` - Services listing
- `/products` - Products listing
- `/blog` - Blog posts
- `/faqs` - FAQ page
- `/contact` - Contact form
- `/page/{slug}` - Custom pages

#### **Authentication**
- `/auth/login` - Login page
- `/auth/register` - Registration page
- `/auth/forgot-password` - Password recovery
- `/auth/logout` - Logout

#### **User Dashboard**
- `/dashboard` - User dashboard
- `/dashboard/profile` - User profile
- `/dashboard/subscriptions` - Subscriptions
- `/dashboard/invoices` - Invoices
- `/dashboard/payments` - Payment history
- `/dashboard/products` - Product browsing

#### **Admin Panel** *(Admin only)*
- `/admin` - Admin dashboard
- `/admin/settings` - Site settings
- `/admin/users` - User management
- `/admin/products` - Product management
- `/admin/services` - Service management
- `/admin/pages` - Page management
- `/admin/slides` - Carousel/slides
- `/admin/blog` - Blog management
- `/admin/faqs` - FAQ management
- `/admin/feedbacks` - Feedback management
- `/admin/media` - Media management
- `/admin/subscriptions` - Subscription management
- `/admin/invoices` - Invoice management
- `/admin/payments` - Payment management

### **API Endpoints**
- `POST /api/chat` - AI chat endpoint
- `GET /api/faqs/search` - FAQ search

## Database Schema

### **Key Tables**
- `gintec_users` - User accounts
- `gintec_products` - Product catalog
- `gintec_services` - Services offered
- `gintec_subscriptions` - User subscriptions
- `gintec_invoices` - Invoice records
- `gintec_pages` - CMS pages
- `gintec_blog_posts` - Blog articles
- `gintec_faqs` - FAQ entries
- `gintec_feedbacks` - Customer feedback
- `gintec_settings` - Site settings
- `gintec_chat_sessions` - AI chat history

## Security Features

- ✅ Password hashing with bcrypt
- ✅ CSRF token protection
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS protection (HTML escaping)
- ✅ Rate limiting on login attempts
- ✅ Session management
- ✅ Environment-based configuration
- ✅ Secure password requirements

## Features

### **Public Website**
- Modern, responsive design
- Product showcase
- Service descriptions
- Blog system
- FAQ section
- Contact form
- Customer testimonials/feedback

### **User Management**
- User registration with email verification
- Profile management
- Subscription tracking
- Invoice viewing
- Payment history
- Product browsing

### **Admin Dashboard**
- Complete site management
- User management
- Product/Service CRUD
- Page/Blog management
- Feedback management
- Settings configuration
- Media management
- Analytics overview

### **AI Chat Widget**
- Real-time chat interface
- FAQ-based responses
- Session tracking
- Integration on all pages

## Customization

### **Add New Pages**
1. Create view file in `app/views/`
2. Add route in `public/index.php`
3. Create controller method

### **Add New Admin Feature**
1. Create controller method in `AdminController.php`
2. Create view file in `app/views/admin/`
3. Add route
4. Add sidebar menu link

### **Modify Database Schema**
1. Edit `database/migrations/001_create_tables.php`
2. Run migration
3. Update corresponding model

## Support

For support and inquiries:
- **Email**: info@gintec.com.ng
- **Phone**: 07067973091
- **Address**: 2nd Floor, Peace Plaza B, Utako, Abuja

## License

All rights reserved © 2024 GINTEC Solutions Consults Ltd

## Next Steps

1. ✅ Complete database schema
2. ✅ Build all controllers
3. ✅ Create all views
4. ⏳ Add payment integration (Paystack)
5. ⏳ Implement email notifications
6. ⏳ Add advanced reporting
7. ⏳ Optimize for performance
8. ⏳ Deploy to production

---

**Project Status**: Framework Complete - Ready for Integration & Testing
