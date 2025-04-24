# 🛒 Ecommerce Project

**A modern, scalable e-commerce platform built with Laravel, Blade, Alpine.js, and Vue.js.**

## 📖 Description

**Ecommerce Project** is a complete and flexible solution for building an online store. It combines a robust Laravel backend with the simplicity of Blade templating, the reactivity of Alpine.js, and a powerful admin dashboard powered by Vue.js.

Whether you're launching a new online business or looking to build a customizable e-commerce solution, this project provides a solid foundation that you can scale and modify as needed.

---

## 🚀 Features

- **🔧 Laravel Backend**  
  Structured, secure, and high-performance backend with Laravel.

- **🧩 Blade + Alpine.js Frontend**  
  Clean, simple Blade templates enhanced with Alpine.js for reactive UI components.

- **📊 Vue.js Admin Dashboard**  
  A dynamic, modern interface for managing the store, built entirely in Vue.js.

- **📦 Modular and Scalable Architecture**  
  Organized codebase that’s easy to extend and maintain.

- **🔐 Breeze Authentication**  
  Laravel Breeze provides a simple and secure authentication system.

- **🛍 Product & Order Management**  
  Admins can manage products, categories, and customer orders with ease.

---

## 🛠 Technologies Used

- **Backend:** Laravel
- **Frontend (Customer-facing):** Blade + Alpine.js
- **Dashboard (Admin Panel):** Vue.js
- **Authentication:** Laravel Breeze

---

## ⚙️ Getting Started

Follow the steps below to set up the project locally.

### 🧬 Prerequisites

- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL or another supported database

---

### 1. 🔄 Clone the Repository

```bash
git clone https://github.com/yourusername/ecommerce-project.git
cd ecommerce-project

When you finish you must go to the folder you want to work on.

# Backend (Laravel)
cd backend-ecommerce-laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link

# Frontend (Vue.js)
cd dashboard-admin-vuejs
npm install
npm run dev

# Iniciar servidor Laravel
cd backend-ecommerce-laravel
php artisan serve

# Iniciar servidor Vue.js
cd dashboard-admin-vuejs
npm run serve


Explore and customize the project to your needs. Welcome to the world of e-commerce with Laravel, Blade, Alpine, and Vue.js!


🧪 Explore & Customize
This project is designed to be a starting point. You can extend it with:

Payment gateways (e.g., PayPal)

User roles and permissions

Product reviews and ratings

Plugin-based architecture



🤝 Contributing
Feel free to fork this repository and submit pull requests. Feedback, ideas, and improvements are always welcome.

📄 License
This project is open-source under the MIT License.