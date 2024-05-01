# Symfony CRUD Test Application
This project is a Symfony-based CRUD application.

### Prerequisites

Before running the application, make sure you have the following installed on your system:

- PHP (>= 8.0)
- Composer
- MySql
- Node (npm)

### Installation

1. Clone the repository to your local machine:

   ```bash
    git clone https://github.com/ArenGr/symfony-panelist-survey-crud

2. Install PHP dependencies using Composer:

   ```bash
    composer install

3. Copy the .env.example file to .env.local and configure your environment variables:

   ```bash
    cp .env .env.local

4. Generate an application key:

   ```bash
   symfony secrets:generate-keys
   
5. Run migrations:
    ```bash
   symfony doctrine:migrations:migrate
   
6. Run builder and start the Symfony development server:
   ```bash
   npm run build
   ```
   ```bash
   symfony server:start

The service will be accessible at http://localhost:8000.