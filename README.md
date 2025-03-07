<h1 align="center">
   SwiftBank - Your Personal Transaction Partner
</h1>

<p align="center">A powerful and comprehensive admin dashboard template built with Bootstrap 5 and Laravel!</p>

<p align="center">
  <a href="#introduction">Introduction</a> •
  <a href="#features">Features</a> •
  <a href="#installation">Installation</a> •
  <a href="#database-setup">Database Setup</a> •
  <a href="#seed-data">Seed Data</a> •
  <a href="#testing">Testing</a> •
  <a href="#deployment">Deployment</a> •
  <a href="#contributing">Contributing</a> •
  <a href="#license">License</a>
</p>

Screenshots

![image](https://github.com/user-attachments/assets/e3c2984f-7853-48cd-863a-f0e89c32cfd9)

![image](https://github.com/user-attachments/assets/faa2add6-298d-40d8-871f-55d8482f6721)

![image](https://github.com/user-attachments/assets/cfc45f39-49bb-45ee-b6be-562a084dc7df)

![image](https://github.com/user-attachments/assets/3e148b77-ff44-44cd-aaf2-2bb5ca80e09a)

![image](https://github.com/user-attachments/assets/75521499-3679-47ab-9301-ff5245011981)

![image](https://github.com/user-attachments/assets/1e87a2df-9f6a-4e82-8a8b-7351d9774c5a)

![image](https://github.com/user-attachments/assets/aa9a2804-3ea7-4fa3-83c3-6e0e43e88f00)

![image](https://github.com/user-attachments/assets/eb2d7734-d361-40d2-bc13-9f3cc7ac57d8)

![image](https://github.com/user-attachments/assets/65b9d81e-df67-40c8-a719-0d39744eb8cf)

![image](https://github.com/user-attachments/assets/dc254354-73ea-4d42-a97c-7e0314d1fc38)

![image](https://github.com/user-attachments/assets/fb25b7db-90a5-4722-bc21-d3de27737bf9)


Video



https://github.com/user-attachments/assets/ef477fb6-0b0a-40ca-ba26-900d31be2d79




## Introduction 🚀

Welcome to **SwiftBank**, your personal transaction partner! This powerful and comprehensive admin dashboard template built with Bootstrap 5 and Laravel is designed to help you manage your finances efficiently. With features like real-time balance tracking, transaction history, and insightful analytics, SwiftBank provides a user-friendly experience for managing your financial activities.

## Features ✨

- **User Management**: Create, update, and manage user accounts with different permission levels
- **Activity Tracking**: Monitor user logins and system activities with detailed audit logs
- **System Backup**: Create and manage system backups to ensure data safety
- **Responsive Design**: Fully responsive interface that works on desktop, tablet, and mobile devices
- **Role-Based Access Control**: Different access levels for users, admins, and super admins
- **Modern UI**: Clean and intuitive user interface built with Bootstrap 5
- **Transaction Management**: Track and categorize financial transactions with detailed history
- **Account Management**: Manage multiple account types with different features and interest rates
- **Card Management**: Issue and manage virtual and physical payment cards
- **Beneficiary Management**: Save and manage transfer recipients for quick transactions

## Installation ⚒️

### Prerequisites

- PHP 8.0 or higher
- Composer
- Node.js and npm/yarn
- MySQL or PostgreSQL

### Setup Steps

1. Clone the repository:

```bash
git clone https://github.com/yourusername/swiftbank.git
cd swiftbank
```

2. Install Composer dependencies:

```bash
composer install
```

3. Copy the environment file and generate application key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in the `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=banking
DB_USERNAME=root
DB_PASSWORD=
```

5. Set up your database and tables (see [Database Setup](#database-setup) section below)

6. Seed the database with sample data (see [Seed Data](#seed-data) section below)

7. Install Node.js dependencies and build assets:

```bash
yarn install
yarn build
```

8. Start the development server:

```bash
php artisan serve
```

9. Visit `http://localhost:8000` in your browser to access the application.

## Database Setup 🗄️

You have two options for setting up the database and tables:

### Option 1: Using Laravel Migrations

This method uses Laravel's built-in migration system to create the database tables:

```bash
# Create the database first (using MySQL command line)
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS banking"

# Run migrations to create tables
php artisan migrate
```

### Option 2: Using the SQL File (Recommended)

This method uses the provided SQL file to create both the database and tables in one step:

1. Make sure your MySQL server is running
2. Run the SQL file:

```bash
# Using MySQL command line
mysql -u root -p < database/swift_bank_create_db_with_tables.sql

# Or using a tool like phpMyAdmin, import the SQL file
```

> **Note**: If you use the SQL file method, you should skip the `php artisan migrate` step as the tables are already created by the SQL file.

## Seed Data 🌱

After setting up the database and tables, you can populate them with sample data using the seeders.

### Available Seeders

- **UserSeeder**: Creates admin and regular users with profiles and preferences
- **AccountTypeSeeder**: Sets up various account types (Savings, Checking, Money Market, etc.)
- **AccountSeeder**: Creates accounts for each user with realistic balances
- **TransactionCategorySeeder**: Establishes a hierarchical category system for transactions
- **TransactionGroupSeeder**: Creates transaction groups for organizing transactions
- **TransactionSeeder**: Generates realistic transaction history for each account
- **BeneficiarySeeder**: Creates beneficiaries for transfers between accounts
- **CardSeeder**: Sets up debit and credit cards for checking accounts
- **SystemSettingSeeder**: Configures system-wide settings and parameters

### Running Seeders

To populate your database with all seed data:

```bash
php artisan db:seed
```

To run a specific seeder:

```bash
php artisan db:seed --class=UserSeeder
```

### Complete Setup in One Step

If you're using Laravel migrations, you can run migrations and seeders in one command:

```bash
php artisan migrate --seed
```

### Demo Credentials

After seeding, you can log in with the following demo credentials:

- **Admin User**:

  - Email: admin@swiftbank.com
  - Password: password

- **Regular User**:
  - Email: john@example.com
  - Password: password

## Testing 🧪

SwiftBank includes a comprehensive test suite to ensure the application functions correctly. The tests cover both feature and unit testing aspects of the application.

### Available Tests

#### Feature Tests

- **UserAuthTest**: Tests user authentication functionality (login, logout)
- **AccountManagementTest**: Tests account viewing and management features
- **TransactionTest**: Tests transaction creation and viewing functionality

#### Unit Tests

- **AccountBalanceTest**: Tests account balance calculations for deposits, withdrawals, and transfers
- **TransactionCategoryTest**: Tests transaction category relationships and hierarchy

### Running Tests

To run all tests:

```bash
php artisan test
```

To run a specific test:

```bash
php artisan test --filter=UserAuthTest
```

To run tests with coverage report (requires Xdebug):

```bash
php artisan test --coverage
```

## Deployment Instructions 🚀

To deploy your SwiftBank application to a production environment, follow these steps:

1. **Prepare the Environment**: Ensure your server meets the requirements for running Laravel applications (PHP, Composer, Node.js).

2. **Clone the Repository**: Use the following command to clone your repository to the server:

```bash
git clone https://github.com/yourusername/swiftbank.git
```

3. **Install Dependencies**: Navigate to the project directory and run:

```bash
composer install --optimize-autoloader --no-dev
yarn install
```

4. **Set Environment Variables**: Copy the `.env.example` file to `.env` and configure your environment variables:

```bash
cp .env.example .env
```

5. **Generate Application Key**: Run the following command to generate the application key:

```bash
php artisan key:generate
```

6. **Set Up Database and Tables**: Either use migrations or the SQL file as described in the [Database Setup](#database-setup) section.

7. **Seed the Database** (Optional): After creating the database and tables, you can seed the database with sample data:

```bash
php artisan db:seed
```

8. **Build Assets**: Compile your assets for production:

```bash
yarn build
```

9. **Configure Web Server**: Set up your web server (Apache/Nginx) to point to the `public` directory.

10. **Set Proper Permissions**: Ensure storage and cache directories are writable:

```bash
chmod -R 775 storage bootstrap/cache
```

11. **Set Up Caching (Optional)**: For better performance, set up route and config caching:

```bash
php artisan route:cache
php artisan config:cache
php artisan view:cache
```

## Project Structure 📁

```
swiftbank/
├── app/                  # Application code
│   ├── Http/             # Controllers, Middleware, Requests
│   ├── Models/           # Eloquent models
│   └── Services/         # Business logic services
├── config/               # Configuration files
├── database/             # Migrations and seeders
│   ├── migrations/       # Database structure definitions
│   ├── seeders/          # Sample data generators
│   └── swift_bank_create_db_with_tables.sql  # SQL file for database creation
├── public/               # Publicly accessible files
├── resources/            # Views, assets, and language files
│   ├── js/               # JavaScript files
│   ├── css/              # CSS files
│   └── views/            # Blade templates
├── routes/               # Route definitions
└── tests/                # Automated tests
    ├── Feature/          # Feature tests
    └── Unit/             # Unit tests
```

## Contributing 🤝

Contributions are welcome and appreciated! Here's how you can contribute:

1. **Fork the repository**
2. **Create a feature branch**:

```bash
git checkout -b feature/amazing-feature
```

3. **Commit your changes**:

```bash
git commit -m 'Add some amazing feature'
```

4. **Push to the branch**:

```bash
git push origin feature/amazing-feature
```

5. **Open a Pull Request**

Please make sure your code follows the project's coding standards and includes appropriate tests.

## License 📝

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Available Tasks 🧑‍💻

**Building for Production:** To run the project in production mode, execute the following command in the root directory:

```bash
yarn prod
```
