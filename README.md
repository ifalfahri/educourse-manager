# EduCourse Manager
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/vue.js-%2335495e.svg?style=for-the-badge&logo=vuedotjs&logoColor=%234FC08D)
![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)

> Manage your course easily
## Features

- 📚 Course Management (CRUD operations)
- 👨‍🎓 Student Management
- 📝 Enrollment System
- 🔒 Role-based Access Control (Admin/Student)
- 📊 Excel Import/Export
- 📄 PDF Report Generation
- 🔍 Search, Filter, and Sort functionality

## Prerequisites

- 🐘 PHP >= 8.2
- 🎼 Composer
- 🟢 Node.js & NPM
- 🗄️ MySQL

## Installation

1. Clone repository
    ```bash
    git clone https://github.com/ifalfahri/educourse-manager.git
    cd educourse-manager
    ```

2. Install dependencies
   ``` bash
   composer install
   npm install
   ```

3. Setup environment
   ``` bash
   ren .env.example .env
   php artisan key:generate
    ```

4. Setup application
   ``` bash
   php artisan migrate --seed
   php artisan storage:link
   npm run build
   ```

## Running the Application
Start all services with one command:
``` bash
composer run dev
```

This commands runs concurrently:
- 🔵 PHP Server (`php artisan serve`)
- 🟣 Queue Worker (`php artisan queue:listen`)
- 🟠 Vite Dev Server (`npm run dev`)

Access the application at
```
http://localhost:8000
```

## Default Credentials
After seeding, you can login with these default credentials:
- Admin:
  - Email: admin@admin.com
  - Password: admin
- Students:
  - Email: see at Student table
  - Password: password
  - or you can create it yourself through the register page

## API Documentation

### Courses
```http
GET /api/courses
```
Query parameters:
- `search`: Search by name or description
- `status`: Filter by status (true/false)
- `price_range`: Filter by price range (format: min-max)
- `sort_by`: Sort field (default: id)
- `sort_direction`: Sort direction (asc/desc)

```http
GET /api/courses/{id}
```
Returns course details with enrolled students.

### Enrollments
```http
GET /api/enrollments
```
Query parameters:
- `payment_status`: Filter by payment status
- `course_id`: Filter by course
- `user_id`: Filter by user

```http
GET /api/enrollments/{id}
```
Returns enrollment details with user and course information.

## Technology Stack
- 🖥️ Laravel 11 for backend
- 🖼️ Vue 3 for frontend
- ⚡ Inertia.js for SPA functionality
- 🎨 Tailwind CSS for styling
- 📊 Laravel Excel for spreadsheet handling
- 📄 DomPDF for PDF generation

---
Made with ❤️ by [Ifal Fahri A](https://ifal.me)