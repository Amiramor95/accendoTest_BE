# Organisation Chart Management System - SOFTWARE ENGINEER TECHNICAL TEST for ACCENDO

## Overview

This project using Laravel backend API 

## Prerequisites

- PHP >= 8.1
- Composer
- npm
- Laravel 11
- MySQL

## Installation

### Laravel API

1. **Clone the repository**:
    ```bash
    git clone https://github.com/yourusername/your-laravel-repo.git
    cd your-laravel-repo
    ```

2. **Install dependencies**:
    ```bash
    composer install
    ```

3. **Copy `.env` file and configure your environment variables**:
    ```bash
    cp .env.example .env
    ```
    Update the `.env` file with your database credentials and other configurations.

4. **Generate application key**:
    ```bash
    php artisan key:generate
    ```

5. **Run migrations**:
    ```bash
    php artisan migrate
    ```

6. **Serve the application**:
    ```bash
    php artisan serve
    ```
    The Laravel API will be running at `http://localhost:8000`.

## POSTMAN to Test API