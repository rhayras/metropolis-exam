## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation)

Clone the repository

    git clone git@github.com:rhayras/metropolis-exam.git

Switch to the repo folder

    cd metropolis-exam

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Create database to your localhost and set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve

## Database seeding

**Populate user data using seeder**

Open the CreateUsersSeeder and set the property values as per your requirement

    database/seeders/CreateUsersSeeder.php

Run the database seeder and you're done

    php artisan db:seed --class=CreateUsersSeeder

## System Usage

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

Login using this account

    admin@sample.com
    Admin123
