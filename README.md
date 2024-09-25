**Laravel Blog API**
This is a RESTful API for a blog application built using Laravel. It allows users to manage posts and comments with features like pagination, searching, and role-based access control.

**Requirements**
PHP 8.0 or higher
Composer
MySQL or another supported database
Laravel 8.x or higher

**Installation**
1. Clone the repository: git clone https://github.com/Ishii29/blog_app.git cd blog_app

2. Install dependencies: composer install

3. Copy the example environment file:
   cp .env.example .env
4.Generate the application key:
    php artisan key:generate
5.Configure database:
    Open the .env file and set database credentials:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=blog_app
    DB_USERNAME=root
    DB_PASSWORD=

6.Run migrations: 
     php artisan migrate 
7.Seed the database (optional): 
     php artisan db:seed

The application will be available at http://localhost:8000.
