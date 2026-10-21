# Online Food Ordering ERP System - Laravel Web Application

A full-featured, multi-role **Online Food Ordering ERP System** built using **Laravel**, **PHP 8.2+**, **MySQL / SQLite**, **Eloquent ORM**, **Bootstrap 5**, and **Blade Templates**.

---

## 🌟 Key Features & Role Architecture

### 1. 🔑 Multi-Role Authentication & Security
- **Role-Based Access Control (RBAC)**: Supports 4 distinct user roles:
  - `Admin`: Complete ERP management access.
  - `Restaurant Staff`: Kitchen queue fulfillment & order status tracking.
  - `Delivery Staff`: Dispatch portal, customer contact details, and milestone updates.
  - `Customer`: Public storefront, cart management, checkout, and live order tracking.
- **Security Features**: Middleware route protection, CSRF protection, password hashing (`bcrypt`), and session management.
- **Quick-Fill Demo Selector**: One-click demo login buttons on the login screen for testing all 4 roles.

### 2. 📊 Admin ERP Portal & Analytics
- **Overview Metrics**: Total Sales ($), Total Orders, Pending/Preparing/Delivered breakdown, Registered Customers, Available Food Menu Items.
- **Interactive Graphs**: Revenue & order trend charts powered by Chart.js.
- **Order Management Console**: Live order pipeline status updates (`Pending` -> `Confirmed` -> `Preparing` -> `Out for Delivery` -> `Delivered` -> `Cancelled`).
- **Delivery Dispatch Console**: One-click assignment of delivery staff to orders.

### 3. 🍔 Menu & Category Management
- Full CRUD for food categories and menu items.
- Food item image uploads, price setting, category classification, and preparation time tracking.
- One-click instant availability toggle (`Available` / `Unavailable`).
- Category pill filters and real-time search on customer storefront.

### 4. 👥 Customer Management
- Customer account creation and profile management.
- Admin directory listing customer phone, address, city, and total lifetime orders value.
- View individual customer profile and complete order history log.

### 5. 🛒 Customer Food Storefront & Cart
- Responsive food menu browsing with category pills and search.
- Interactive shopping cart with quantity modifiers, special instructions input, subtotal, 5% estimated tax, delivery fee ($3.50), and grand total calculations.
- Seamless Checkout supporting Cash on Delivery (COD), Card on Delivery, or Online Payment.
- Visual step-by-step order tracker timeline for customers.

### 6. 🍳 Kitchen / Restaurant Staff Queue
- Dedicated Kitchen Kanban queue grouped into 3 live stages:
  - 🕒 **Incoming Pending**: Confirm Order
  - 🍳 **Confirmed**: Start Cooking / Preparing
  - 🔥 **Preparing**: Mark Ready for Delivery

### 7. 🚚 Delivery Staff Portal
- Mobile-friendly dashboard listing assigned deliveries.
- One-touch phone dialing and delivery address instructions.
- Milestone tracking buttons (`Picked Up` -> `Out for Delivery` -> `Delivered`).

### 8. 📈 Business Intelligence & Reports Module
- **Daily Orders & Sales Report**
- **Monthly Revenue Matrix Report**
- **Top 10 Most Popular Food Items Ranking**
- **Customer Lifetime Value Ranking**
- **Delivery Dispatch & Efficiency Log**
- Filter by custom date ranges (`Start Date` to `End Date`).
- Export & Print ready layout.

---

## 🛠️ System Requirements

- **PHP**: 8.2 or higher
- **Composer**: 2.x
- **Database**: MySQL 8.0+ or SQLite 3
- **Extensions**: `pdo`, `pdo_mysql` / `pdo_sqlite`, `mbstring`, `openssl`, `json`, `cURL`

---

## 🚀 Setup & Installation Instructions

### 1. Clone or Extract Project
Open your terminal in the root directory of the project:
```bash
cd "Online Food Ordering ERP System"
```

### 2. Configure Environment `.env`
The application comes pre-configured with SQLite by default for instant zero-configuration execution.

To use **MySQL**, open `.env` and set:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=food_erp_db
DB_USERNAME=root
DB_PASSWORD=
```
*(Make sure to create the database `food_erp_db` in MySQL beforehand if using MySQL)*.

### 3. Run Database Migrations & Seed Demo Data
Run the following Artisan command to generate all database tables and seed sample accounts, menu categories, food items, orders, and deliveries:
```bash
php artisan migrate:fresh --seed
```

### 4. Create Storage Symlink
Ensure uploaded images can be accessed publicly:
```bash
php artisan storage:link
```

### 5. Start Local Development Server
Start the Laravel local development server:
```bash
php artisan serve
```

Access the application in your browser at:
`http://127.0.0.1:8000`

---

## 🔐 Default Demo Accounts (Password: `password`)

| User Role | Email Address | Password | Portal Features |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@fooderp.com` | `password` | Complete ERP Overview, Menu CRUD, Customers, Orders, Dispatch, Reports |
| **Kitchen Staff** | `staff@fooderp.com` | `password` | Kitchen Fulfillment Queue (Pending, Confirmed, Preparing) |
| **Delivery Staff 1** | `delivery1@fooderp.com` | `password` | Assigned Deliveries, Contact Phone, Status Updates |
| **Delivery Staff 2** | `delivery2@fooderp.com` | `password` | Assigned Deliveries & Milestone Tracking |
| **Customer 1** | `customer1@fooderp.com` | `password` | Storefront Menu, Cart, Checkout, Order Tracking |
| **Customer 2** | `customer2@fooderp.com` | `password` | Storefront Menu, Cart, Checkout, Order Tracking |

---

## 📁 Laravel Project Architecture

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AdminDashboardController.php
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── CategoryController.php
│   │   ├── CustomerController.php
│   │   ├── DeliveryController.php
│   │   ├── MenuItemController.php
│   │   ├── OrderController.php
│   │   ├── ReportController.php
│   │   └── StaffDashboardController.php
│   └── Middleware/
│       └── RoleMiddleware.php
├── Models/
│   ├── Category.php
│   ├── Customer.php
│   ├── Delivery.php
│   ├── MenuItem.php
│   ├── Order.php
│   ├── OrderDetail.php
│   └── User.php
database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2026_08_08_000001_create_customers_table.php
│   ├── 2026_08_08_000002_create_categories_table.php
│   ├── 2026_08_08_000003_create_menu_items_table.php
│   ├── 2026_08_08_000004_create_orders_table.php
│   ├── 2026_08_08_000005_create_order_details_table.php
│   └── 2026_08_08_000006_create_deliveries_table.php
└── seeders/
    └── DatabaseSeeder.php
resources/views/
├── admin/
│   ├── categories/ (index, create, edit)
│   ├── customers/ (index, create, edit, show)
│   ├── deliveries/ (index)
│   ├── menu/ (index, create, edit)
│   ├── orders/ (index)
│   ├── reports/ (index)
│   └── dashboard.blade.php
├── auth/ (login, register, forgot-password)
├── customer/ (menu, cart, checkout, orders)
├── delivery/ (dashboard)
├── layouts/ (app, admin)
├── orders/ (show)
└── staff/ (dashboard)
routes/
└── web.php
```
