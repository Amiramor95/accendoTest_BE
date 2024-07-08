# Organisation Chart Management System - SOFTWARE ENGINEER TECHNICAL TEST for ACCENDO

## Overview

This project using Laravel backend API and Postman for API testing.

## Prerequisites

- PHP >= 8.2
- Composer
- npm
- Laravel 11
- MySQL

## Installation

### Laravel API

1. **Clone the repository**:
    ```bash
    git clone https://github.com/Amiramor95/accendoTest_BE.git
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

1. **Import the exported Postman API file to your workspace**:
    ```bash
    The 'Software Engineer Technical Test.postman_collection.json' file can be found inside the repository main folder.
    ```

2. **Collection**:
    ```bash
    The 'Software Engineer Technical Test' collection include both of the api.
    ```

3. **API**
    ```bash
    The 'UploadEntireOrgChart' API is to fulfill the first API requirement and 'UpdateOrAddInOrgChart' is to fulfill the second.
    ```

## Step by step to Test API

1. **In the Postman, extend the imported collection 'Software Engineer Technical Test' at the collections tab.**

2. **Select the desired API to test.**

3. **Once the tab is open, specify the details in the body data.(Supposedly the details already populated)**

4. **choose the form-data as data type by selecting the radio button.**

5. **The key-value should be 'file'. If not, need to key in manually.**

6. **Select File in the dropdown list next to a key name, then select the file.**

7. **Ensure Laravel is installed and running**

7. **Click 'Send' button to start the testing.**



## API Test Expectation

1. **UploadEntireOrgChart**
    ```bash
    Upon upload '.csv' file it will store all the records to the database.
    ```

2. **UpdateOrAddInOrgChart**
    ```bash
    Upon upload '.csv' file it will add new employee or update employee details or it can do both simultaneously.
    ```
    
## Sample of API Test Validation Scenario and Expectation

1. **Valid data to store**
    ```bash
    Expectation Message
    "message": "Success",
    "Data":  [
        {
            "job_id": "ACC-0001",
            "job_title": "CEO",
            "emp_name": "Alice",
            "emp_id": "1001",
            "email": "alice@company.com",
            "report_to_job_id": "",
            "report_to_name": "",
            "role_priority": "1",
            "job_level": "1",
            "is_root": "yes",
            "created_at": "2024-07-08 02:51:18",
            "updated_at": "2024-07-08 02:51:18"
        }
    ]

2. **Some invalid data to update/add**
    ```bash
    Expectation Message
    "message": "Success with Unsuccessful Data",
    "Sucsessful Data":  [
        {
            "job_id": "ACC-0001",
            "job_title": "CEO",
            "emp_name": "Alice",
            "emp_id": "1001",
            "email": "alice@company.com",
            "report_to_job_id": "",
            "report_to_name": "",
            "role_priority": "1",
            "job_level": "1",
            "is_root": "yes",
            "created_at": "2024-07-08 02:51:18",
            "updated_at": "2024-07-08 02:51:18"
        }
    ]
    "Unsuccessful Data": [
        {
            "job_id": "ACC-0011",
            "job_title": "Engineer",
            "emp_name": "Amir",
            "emp_id": "",
            "email": "amir@company.com",
            "report_to_job_id": "ACC-0002",
            "report_to_name": "Bob",
            "role_priority": "3",
            "job_level": "3",
            "is_root": "no",
            "created_at": "2024-07-08 02:53:40",
            "updated_at": "2024-07-08 02:53:40"
        }
    ]

3. **Invalid data to update/add**
    ```bash
    Expectation Message
    "message": "Unsuccessful",
    "Unsuccessful Data": [
        {
            "job_id": "ACC-0012",
            "job_title": "Engineer",
            "emp_name": "John",
            "emp_id": "1006",
            "email": "",
            "report_to_job_id": "ACC-0002",
            "report_to_name": "Bob",
            "role_priority": "3",
            "job_level": "3",
            "is_root": "no",
            "created_at": "2024-07-08 02:58:46",
            "updated_at": "2024-07-08 02:58:46"
        }
    ]
    ```

4. **All validation failed**
    ```bash
    Expectation Message
    "message": [
        "Unsuccessful (Invalid Data)",
        "Unsuccessful (Duplicate Entries)",
        "Unsuccessful (Duplicate Data in Database)"
    ],
    "Invalid Data": [
        {
            "job_id": "ACC-0015",
            "job_title": "Engineer",
            "emp_name": "Ammari",
            "emp_id": "1015",
            "email": "",
            "report_to_job_id": "ACC-0002",
            "report_to_name": "Bob",
            "role_priority": "3",
            "job_level": "3",
            "is_root": "no",
            "created_at": "2024-07-08 15:41:01",
            "updated_at": "2024-07-08 15:41:01"
        }
    ],
    "Duplicate Entries Data": [
        {
            "job_id": "ACC-0011",
            "job_title": "Engineer",
            "emp_name": "Amir",
            "emp_id": "1013",
            "email": "amir@company.com",
            "report_to_job_id": "ACC-0002",
            "report_to_name": "Bob",
            "role_priority": "3",
            "job_level": "3",
            "is_root": "no",
            "created_at": "2024-07-08 15:41:01",
            "updated_at": "2024-07-08 15:41:01"
        }
    ],
    "Duplicate Data": [
        {
            "job_id": "ACC-0011",
            "job_title": "Engineer",
            "emp_name": "Amir",
            "emp_id": "1013",
            "email": "amir@company.com",
            "report_to_job_id": "ACC-0002",
            "report_to_name": "Bob",
            "role_priority": "3",
            "job_level": "3",
            "is_root": "no",
            "created_at": "2024-07-08 15:41:01",
            "updated_at": "2024-07-08 15:41:01"
        },
        {
            "job_id": "ACC-0011",
            "job_title": "Engineer",
            "emp_name": "Amir",
            "emp_id": "1013",
            "email": "amir@company.com",
            "report_to_job_id": "ACC-0002",
            "report_to_name": "Bob",
            "role_priority": "3",
            "job_level": "3",
            "is_root": "no",
            "created_at": "2024-07-08 15:41:01",
            "updated_at": "2024-07-08 15:41:01"
        },
        {
            "job_id": "ACC-0014",
            "job_title": "Engineer",
            "emp_name": "Ammar",
            "emp_id": "1014",
            "email": "ammar@company.com",
            "report_to_job_id": "ACC-0002",
            "report_to_name": "Bob",
            "role_priority": "3",
            "job_level": "3",
            "is_root": "no",
            "created_at": "2024-07-08 15:41:01",
            "updated_at": "2024-07-08 15:41:01"
        }
    ]
    ```

4. **No data found**
    ```bash
    Expectation Message
    "message": "Unsuccessful",
    "0": "No data found"
    ```

## Validated Column and Rules

1. **job_id**
    ```bash
    required|string
    ```

2. **job_title**
    ```bash
    required|string
    ```

3. **emp_name**
    ```bash
    required|string
    ```

4. **emp_id**
    ```bash
    required|integer
    ```

5. **email**
    ```bash
    required|email
    ```

