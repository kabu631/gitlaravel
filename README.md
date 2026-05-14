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
- **AI Chatbot** — Integrated chatbot powered by OpenRouter / NVIDIA NIM
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
- **Audit Logging** — Recent comprehensive verification of all CRUD operations

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
| Database     | MySQL (Production) / SQLite (Dev)   |
| AI Features  | OpenRouter / NVIDIA NIM             |
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

# 6. Database Setup
# The project includes a latest SQL dump: git_infosys_v2.sql
# 1. Create a MySQL database named 'git_infosys_v2'
# 2. Import git_infosys_v2.sql into your database

# 7. Create storage symlink (for uploaded images)
php artisan storage:link

# 8. Build frontend assets
npm run build

# 9. Start the development server
php artisan serve
```

The application will be available at **http://localhost:8000**

---

## 🔐 Demo Login Credentials

### Admin Panel Access
1. Navigate to **http://localhost:8000/admin**
2. Log in with the following credentials:

| Field    | Value                |
|----------|----------------------|
| Email    | `admin@gitinfosys.com`|
| Password | `password`           |

---

## 🗄 Database & SQL

The repository maintains the latest database state in two locations:
1.  **Root Directory**: `git_infosys_v2.sql` (Main production dump)
2.  **Database Directory**: `database/project.sql` (Sync for reference)

To update your local environment with the latest data, import either of these files into your MySQL server.

---

## 🧪 Testing & Verification

A comprehensive audit was performed on **May 14, 2026**, covering all administrative and public components.

### Admin Panel CRUD Audit
All resource modules were manually and automatically verified for:
- ✅ **Brand Management**: Full CRUD verified.
- ✅ **Category Management**: Full CRUD verified.
- ✅ **Gadget/Product Management**: Image uploads and specification handling verified.
- ✅ **Order Processing**: Workflow from customer order to admin dashboard verified.
- ✅ **User Management**: Role-based access and password resets verified.

### Public Storefront
- Verified cross-browser compatibility for the PC Builder and Comparison engines.
- Confirmed AI chatbot responsiveness using OpenRouter.

---

## 📁 Project Structure

```
gitlaravel/
├── app/
│   ├── Console/              # Artisan commands
│   ├── Filament/             # Admin panel resources & widgets
│   │   ├── Resources/        # CRUD resources (Gadgets, Brands, Orders, etc.)
│   ├── Http/
│   │   ├── Controllers/      # Feature controllers
│   │   ├── Middleware/        # Custom middleware
│   ├── Models/               # Eloquent models
├── database/
│   ├── migrations/           # Schema definitions
│   ├── project.sql           # Latest SQL dump
├── resources/
│   ├── js/
│   │   ├── Components/       # Vue UI components
│   │   ├── Pages/            # Page modules (Home, Gadgets, Cart, etc.)
├── git_infosys_v2.sql        # Root SQL dump for easy access
```

---

## ⚙️ Environment Variables

Key variables in `.env`:

| Variable             | Description                              |
|----------------------|------------------------------------------|
| `OPENROUTER_API_KEY` | API key for AI features (Chatbot, etc.)  |
| `DB_DATABASE`        | Set to `git_infosys_v2`                  |

> **AI Features:** This project uses **OpenRouter** to provide free/low-cost access to state-of-the-art AI models for the PC Builder and Chatbot features.

---

## 📄 License

This project is open-sourced under the [MIT License](https://opensource.org/licenses/MIT).
