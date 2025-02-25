[![Test Workflow Status](https://img.shields.io/github/actions/workflow/status/aranes-rc/HRIS-2025/laravel-test.yml?style=for-the-badge&link=https%3A%2F%2Fgithub.com%2Faranes-rc%2FHRIS-2025%2Factions%2Fworkflows%2Flaravel-test.yml)](https://github.com/aranes-rc/HRIS-2025/actions/workflows/laravel-test.yml)

_(temporary repository only. will be transferred to the organization soon)_

# ROC.PH - HRIS

## Prerequisites

Ensure you have the following installed:
- PHP (>=8.0 recommended)
- Composer
- Node.js & npm (for frontend assets)
- MySQL or PostgreSQL (or any database of choice)
- Laravel dependencies (installed via Composer)

## Contributing

1. Fork the repository.
2. Create a new branch (`feature-branch`).
3. Commit changes.
4. Push to your fork and submit a pull request.

## Clone the Repository

```sh
git clone https://github.com/aranes-rc/HRIS-2025
cd HRIS-2025
```

## Install Dependencies

```sh
composer install
npm install
```

## Configure Environment

Copy the example environment file:

```sh
cp .env.example .env
```

Generate the application key:

```sh
php artisan key:generate
```

## Set Up Database

Ensure your database is running and have already made a `.env` copy!

Run migrations:

```sh
php artisan migrate --seed
```

## Run the Development Server

```sh
npm run build
composer run dev
```

## Run Tests

To execute the test suite, run:

```sh
php artisan test
```

You can also run specific tests:

```sh
./vendor/bin/pest ./tests/Unit/ExampleTest.php
```

## Additional Commands

Clear cache and config (if needed):

```sh
php artisan config:clear
php artisan cache:clear
```

## Common Issues & Fixes

- If permissions are incorrect for `storage` and `bootstrap/cache`, run:
  ```sh
  chmod -R 775 storage bootstrap/cache
  ```
- If `.env` changes are not taking effect:
  ```sh
  php artisan config:clear
  ```

