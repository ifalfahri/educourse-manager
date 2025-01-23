# EduCourse Manager

## Features

- Course Management (CRUD operations)
- Student Management
- Enrollment System
- Role-based Access Control (Admin/Student)
- Excel Import/Export
- PDF Report Generation
- Search, Filter, and Sort functionality

## Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

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

## Technology Stack
- Laravel 11 for backend
- Vue 3 for frontend
- Inertia.js for SPA functionality
- Tailwind CSS for styling
- Laravel Excel for spreadsheet handling
- DomPDF for PDF generation

---
Made with ❤️ by Ifal Fahri A