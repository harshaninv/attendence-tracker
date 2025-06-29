# Attendance Tracker Application

This is a web application for tracking student attendance, built with Laravel, Inertia.js, and React. It provides features for teachers to mark daily attendance and view an attendance dashboard with filters.
## 🚀 Features

- User Authentication: Secure login and registration (provided by Laravel Breeze).
- Role-Based Access: Differentiates between administrators and teachers.
- Daily Attendance Marking: Teachers can select a subject and mark the attendance status (Present, Absent, Late, Excused) for enrolled students on a specific date.
- Attendance Dashboard: View attendance summaries, including percentage attendance for students in various subjects, with filtering capabilities by date range, subject, and student name/registration number.

Real-time Updates: Smooth, SPA-like experience with Inertia.js.

## 📦 Technologies Used

- Backend: PHP 8.2+, Laravel 11
- Frontend: React 18
- Fullstack Bridge: Inertia.js
- Styling: Tailwind CSS
- Database: MySQL
- Build Tool: Vite

## 📋 Prerequisites

Before you begin, ensure you have the following installed on your machine:

- PHP: Version 8.2 or higher (check with php -v)
- Composer: Latest stable version (check with composer -v)
- Node.js & npm: Latest LTS version (Node.js 18+ recommended) (check with node -v and npm -v)
- MySQL Server: Running locally or accessible remotely.

## ⚙️ Installation & Setup

Follow these steps to get the project up and running on your local machine:

- Clone the repository (or start a new Laravel project):
    If you're starting from scratch:
- composer create-project laravel/laravel attendance-tracker

      cd attendance-tracker

    (If you cloned a repository, just cd attendance-tracker)

- Install PHP Dependencies:

      composer install

- Install Node.js Dependencies:

      npm install

-    Configure Environment Variables:

Open the .env file and update your database connection details:

        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=attendance_tracker_db # Choose your database name
        DB_USERNAME=root                 # Your MySQL username
        DB_PASSWORD=                     # Your MySQL password


Important: Create the database in your MySQL server if it doesn't exist (e.g., using phpMyAdmin, MySQL Workbench, or a command-line client):

        CREATE DATABASE attendance_tracker_db;


Run Database Migrations and Seeders:
    This command will drop any existing tables, re-run all migrations to create the schema, and then execute all seeders to populate the database with dummy users, students, subjects, and initial attendance records.

    php artisan migrate:fresh --seed


## ▶️ Running the Application

You'll need two separate terminal windows for the frontend and backend development servers:

-Start the Vite Development Server (Frontend):
    Open a new terminal, navigate to your project root, and run:

    npm run dev


-Keep this terminal running. It watches for changes in your React files and recompiles them.

Start the Laravel Development Server (Backend):
    Open another new terminal, navigate to your project root, and run:

    php artisan serve


 Keep this terminal running. This serves your Laravel application.

## 🌐 Accessing the Application

Open your web browser and visit:
http://localhost:8000

You should see the Laravel welcome page. Click on "Login" or "Register" to get started.
🔑 Default Credentials (After Seeding)

After running php artisan migrate:fresh --seed, the database will be populated with the following users:

- Admin User:

        Email: admin@example.com

        Password: password

        Role: admin (Can access all pages, but primarily for future admin functionalities)

- Teacher User (for marking attendance):

        Email: teacher@example.com

        Password: password

        Role: teacher (This user is assigned to subjects and can mark attendance.)

- Other Teachers:

        Emails like teacher2@example.com, teacher3@example.com (password password for all).

## 📂 Project Structure Highlights

- app/Models/: Contains Eloquent models (User, Student, Subject, Attendance).
- app/Http/Controllers/: Handles application logic and renders Inertia pages (AttendanceController, SubjectController).
- routes/web.php: Defines application routes, mapping URLs to controller methods and Inertia components.
- database/migrations/: Database schema definitions.
- database/seeders/: Dummy data for development and testing.
- resources/js/Pages/: Your React components for different application "pages" (e.g., Attendance/Mark.jsx, Attendance/Dashboard.jsx).
- resources/js/Layouts/: Base layouts for React pages (e.g., AuthenticatedLayout.jsx).
- resources/js/Components/: Reusable React components.


    Laravel Logs: For backend errors (e.g., 500 Internal Server Error), check storage/logs/laravel.log for detailed error messages.
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
