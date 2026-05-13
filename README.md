# GitInfosys Laravel — Gadget E-Commerce Portal

A full-featured **gadget and electronics e-commerce platform** built with **Laravel 13**, **Inertia.js**, **Vue 3**, and **Tailwind CSS**. The application includes a public storefront for browsing, comparing, and purchasing gadgets, along with a powerful **Filament v5** admin panel for managing all content and orders.

---

## 🚀 Features

### Public Storefront
- **Homepage** — Hero slider, featured gadgets, latest news, and brand showcase
- **Product Catalog** — Browse gadgets with filtering by brand and category
- **Product Detail** — Specifications, price history, variants, images, and user comments
- **Shopping Cart** — Add/remove items, update quantities, variant selection
- **Checkout & Orders** — Place orders with shipping details, order success confirmation
- **Brand Pages** — View all products from a specific brand
- **News & Articles** — Tech news with full article pages
- **Reviews** — Detailed gadget reviews with reactions
- **Tech Guides** — How-to guides and tutorials
- **Product Comparison** — Compare gadgets side-by-side with AI-powered suggestions
- **PC Builder** — AI-assisted custom PC build recommendations
- **AI Chatbot** — Integrated chatbot powered by NVIDIA NIM API
- **Search** — Global search across all products
- **Wishlist** — Save favorite products (requires login)
- **User Profile** — Edit profile, view order history
- **Sitemap** — Auto-generated XML sitemap for SEO
- **Static Pages** — About, Contact (with form), Services, Terms & Conditions, Privacy Policy

### Admin Panel (Filament v5)
- **Dashboard** — Overview widgets and statistics
- **Gadget Management** — Create/edit products with images, specs, variants, and pricing
- **Brand Management** — Manage brands with logos
- **Category Management** — Organize products into categories
- **Order Management** — View and manage customer orders
- **Product Variants** — Manage color/storage/RAM variants with individual pricing
- **Slider Management** — Control homepage hero slider content
- **News Articles** — Publish and manage news content
- **Reviews** — Create and manage product reviews
- **Tech Guides** — Publish tutorial content
- **Page Content** — Manage static page content dynamically

---

## 🛠 Tech Stack

| Layer        | Technology                          |
|--------------|-------------------------------------|
| Backend      | PHP 8.3+, Laravel 13                |
| Frontend     | Vue 3, Inertia.js v2                |
| Styling      | Tailwind CSS 3, @tailwindcss/forms  |
| Admin Panel  | Filament v5                         |
| Auth         | Laravel Breeze                      |
| Build Tool   | Vite 8                              |
| Database     | SQLite (default) / MySQL            |
| AI Features  | NVIDIA NIM API                      |
| Charts       | Chart.js                            |
| Routing      | Ziggy (Laravel routes in JS)        |

---

## 📦 Installation

### Prerequisites
- **PHP** >= 8.3
- **Composer** >= 2.x
- **Node.js** >= 18.x and **npm**
- **Git**

### Step-by-Step Setup

```bash
# 1. Clone the repository
git clone https://github.com/kabu631/gitlaravel.git
cd gitlaravel

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Create environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Create SQLite database file
# On Windows:
New-Item database/database.sqlite -ItemType File
# On Linux/Mac:
# touch database/database.sqlite

# 7. Run database migrations
php artisan migrate

# 8. Seed the database with demo data
php artisan db:seed

# 9. Create storage symlink (for uploaded images)
php artisan storage:link

# 10. Build frontend assets
npm run build

# 11. Start the development server
php artisan serve
```

The application will be available at **http://localhost:8000**

### Running in Development Mode (with hot-reload)

```bash
# Start all services concurrently (server + vite + queue + logs)
composer dev
```

Or run them separately in different terminals:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (hot-reload for frontend)
npm run dev
```

---

## 🔐 Demo Login Credentials

### Regular User
| Field    | Value                |
|----------|----------------------|
| Email    | `test@example.com`   |
| Password | `password`           |

### Admin Panel Access
1. Navigate to **http://localhost:8000/admin**
2. Log in with the demo user credentials above

| Field    | Value                |
|----------|----------------------|
| Email    | `test@example.com`   |
| Password | `password`           |

> **Note:** The demo user is created via database seeder. Run `php artisan db:seed` if the user doesn't exist.

---

## 🗄 Database

### Using SQLite (Default)
The project uses SQLite by default. The database file is created at `database/database.sqlite`.

### Using MySQL
To switch to MySQL, update the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gitlaravel
DB_USERNAME=root
DB_PASSWORD=your_password
```

Then run migrations:
```bash
php artisan migrate --seed
```

### SQL Schema
A standalone SQL schema file is available at `database/project.sql` for reference.

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage
```

---

## 📁 Project Structure

```
gitlaravel/
├── app/
│   ├── Console/              # Artisan commands
│   ├── Filament/             # Admin panel resources & widgets
│   │   ├── Resources/        # CRUD resources (Gadgets, Brands, Orders, etc.)
│   │   └── Widgets/          # Dashboard widgets
│   ├── Http/
│   │   ├── Controllers/      # 17 controllers for all features
│   │   ├── Middleware/        # Custom middleware
│   │   └── Requests/         # Form request validation
│   ├── Models/               # 19 Eloquent models
│   └── Providers/            # Service providers
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # 21 migration files
│   ├── seeders/              # Database seeders
│   └── project.sql           # Standalone SQL schema
├── resources/
│   ├── css/                  # Stylesheets
│   ├── js/
│   │   ├── Components/       # Reusable Vue components
│   │   ├── Composables/      # Vue composables
│   │   ├── Layouts/          # Page layouts
│   │   └── Pages/            # 14 page modules (Home, Gadgets, Cart, etc.)
│   └── views/                # Blade templates
├── routes/
│   ├── web.php               # Public & authenticated routes
│   └── auth.php              # Authentication routes
├── public/                   # Public assets
├── config/                   # Configuration files
├── tests/                    # PHPUnit test suite
└── storage/                  # Uploads, logs, cache
```

---

## ⚙️ Environment Variables

Key environment variables in `.env`:

| Variable         | Description                        | Default              |
|------------------|------------------------------------|----------------------|
| `APP_NAME`       | Application name                   | `Laravel`            |
| `APP_URL`        | Base URL                           | `http://localhost`   |
| `DB_CONNECTION`  | Database driver                    | `sqlite`             |
| `DB_DATABASE`    | Database name/path                 | `database.sqlite`    |
| `NVIDIA_API_KEY` | API key for AI features (chatbot, compare, PC builder) | _(empty)_ |

> **AI Features:** To enable the AI chatbot, product comparison suggestions, and PC builder recommendations, get a free API key from [NVIDIA NIM](https://build.nvidia.com/) and set `NVIDIA_API_KEY` in your `.env` file.

---

## 📄 License

This project is open-sourced under the [MIT License](https://opensource.org/licenses/MIT).
