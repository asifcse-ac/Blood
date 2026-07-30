# Blood Bank Management System - Laravel Version

A complete Blood Bank Management System built with Laravel 10, converted from a raw PHP project.

## Features

### Admin Panel
- **Dashboard** - Overview of donors, users, blood stock, and pending requests
- **Donors Management** - CRUD operations for donor records with location tracking
- **Blood Stock Management** - Manage blood inventory across all blood types
- **Blood Requests Management** - Approve/reject blood requests with notifications
- **User Management** - Manage registered users
- **Reports** - Generate reports and export data

### User Panel
- **Dashboard** - View blood availability and recent requests
- **Blood Request** - Submit blood requests with urgency levels
- **Find Donors** - Search donors by blood group
- **Nearby Donors** - Find donors using geolocation
- **Track Stock** - Real-time blood stock monitoring
- **Notifications** - Get notified about request status changes

### AI Features
- AI-powered blood request extraction from natural language
- Request summarization
- Donor outreach message generation

## Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM (for frontend assets, optional)

## Installation

### 1. Clone or Extract the Project

```bash
cd your-web-server-directory
# Extract the blood-bank-laravel folder
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit the `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blood_bank_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations and Seeders

```bash
# Run migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed
```

### 6. Configure AI Features (Optional)

Add your GROQ API key to `.env`:

```env
GROQ_API_KEY=your_groq_api_key_here
```

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Serve the Application

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Default Credentials

### Admin
- **Username:** admin
- **Password:** admin123

## Directory Structure

```
blood-bank-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Admin controllers
│   │   │   └── User/          # User controllers
│   │   └── Middleware/        # Custom middleware
│   └── Models/                # Eloquent models
├── config/                    # Configuration files
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── public/
│   ├── css/                   # CSS files
│   ├── js/                    # JavaScript files
│   └── uploads/               # Uploaded files
├── resources/
│   └── views/
│       ├── admin/             # Admin views
│       ├── user/              # User views
│       └── layouts/           # Layout templates
└── routes/
    └── web.php                # Web routes
```

## Database Schema

### Tables

1. **admins** - Administrator accounts
2. **users** - User accounts with health info
3. **donors** - Donor profiles with location
4. **blood_stock** - Blood inventory
5. **blood_requests** - Blood request records
6. **notifications** - User notifications

## API Routes

### AI Endpoints

- `POST /ai/chat` - AI chat endpoint for request extraction

## Key Routes

### Public
- `/` - Home page
- `/admin/login` - Admin login
- `/user/login` - User login
- `/user/register` - User registration

### Admin (Requires Authentication)
- `/admin/dashboard` - Admin dashboard
- `/admin/donors` - Donors management
- `/admin/blood-stock` - Blood stock management
- `/admin/requests` - Blood requests management
- `/admin/users` - Users management
- `/admin/reports` - Reports

### User (Requires Authentication)
- `/user/dashboard` - User dashboard
- `/user/request-blood` - Submit blood request
- `/user/my-requests` - View requests
- `/user/donors` - View donors
- `/user/find-nearby` - Find nearby donors
- `/user/track-stock` - Track blood stock
- `/user/notifications` - Notifications

## Technologies Used

- **Framework:** Laravel 10
- **PHP Version:** 8.1+
- **Database:** MySQL
- **Frontend:** Bootstrap 5, Font Awesome
- **Fonts:** DM Sans, Instrument Serif
- **AI Integration:** GROQ API (LLaMA models)

## Conversion Notes

This project was converted from a raw PHP application to Laravel. Key changes include:

1. **Database** - Raw SQL queries converted to Eloquent ORM
2. **Authentication** - Custom session handling replaced with Laravel guards
3. **Routing** - Centralized routing in web.php
4. **Views** - PHP files converted to Blade templates
5. **Security** - CSRF protection added to all forms
6. **Structure** - MVC pattern properly implemented

## License

This project is open-source and available under the MIT License.

## Support

For issues or questions, please create an issue in the repository.
