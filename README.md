## CRUD Simple API Movie

This is a Movie application project using Laravel that implements Create, Read, Update, and Delete (CRUD) for basic operations on movie data.

### Table of Contents

-   [Project Overview](#project-overview)
-   [Installation](#installation)
-   [API Documentation](#api-documentation)
-   [Linter & Coding Style](#linter-coding-style)
-   [Unit Testing & Feature Testing](#unit-testing-feature-testing)
-   [Installation with docker](#installation-with-docker)
-   [Structure of the project](#structure-of-the-project)


### Project Overview

This project has implemented tools and principles such as:

-   Linter & Coding Style priset (PSR-2 & PSR-4) with [Pint](https://github.com/kenjis/pint)
-   Unit Testing & Feature Testing with [phpunit](https://phpunit.de) and has implemented with CI in github action.
-   Documentation for the API with [Swagger](https://github.com/justinrainmaker/laravelswagger)
-   Approach Service, Repository Pattern for separately the presentation, business logic and data layers
-   Git Hook for pre-commit linting & testing with [Husky](https://github.com/typicode/husky)
-   Dockerized with [Docker Compose](https://docs.docker.com/compose/)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/zerocode/api-movie-crud-laravel
cd api-movie-crud-laravel

# 2. Install dependencies
composer install

# 3. Copy .env.example to .env and configure it for your database
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Run migrations
php artisan migrate

# 6. Install npm dependencies for husky
npm install

# 7. Run the application (application will be available at http://localhost:8000)
php artisan serve
```

### API Documentation

For API documentation, we use [Laravelswagger](https://github.com/justinrainmaker/laravelswagger), and it will be available at http://localhost:8000/api/documentation

You can add Swagger annotations in the api controller to generate API documentation.
To generate api documentation using the command:

```bash
php artisan l5-swagger:generate
```

### Linter & Coding Style

We use [Pint](https://github.com/kenjis/pint) to lint and fix code style.

To lint and fix code style using the command:

```bash
./vendor/bin/pint
```

To test code style using the command:

```bash
./vendor/bin/pint --test
```

### Unit Testing & Feature Testing

We use [phpunit](https://phpunit.de) to test and feature test the application.
For unit testing, the testing file location is there in `tests/Unit` folder.
Only tested in isolation to ensure that each unit functions correctly according to predetermined specifications.
To test the application using the command:

```bash
php artisan test --testsuite Unit
```

For feature testing, the testing file location is there in `tests/Feature` folder.
This testing uses for intagration test like api test, database test, etc.

To feature test the application using the command:

```bash
php artisan test --testsuite Feature
```

To test all testing using the command:

```bash
php artisan test
```

### Installation with docker

We provide an alternative installation with docker, if you want to install using docker follow these steps

```bash
# 1. Clone the repository
git clone https://github.com/zerocode/api-movie-crud-laravel api-movie-crud-laravel-docker
cd api-movie-crud-laravel-docker

# 2. Copy .env.example-docker to .env
cp .env.example-docker .env

# 3. Run docker compose
docker compose up -d

# 4. Install npm & composer dependencies
docker compose exec app composer install

# 5. Generate application key
docker compose exec app php artisan key:generate

# 6. Run migrations
docker compose exec app php artisan migrate

# 7. Application will be running at http://localhost:8080
```

### Structure of the project

We use approach service, repository pattern for separately the presentation, business logic and data layers.
Below is a brief explanation for the project structure we are using.

```
app
├── Services        # Service layer focused on business logic
├── Http
    ├── Controllers     # Controller layer focused on presentation for request and response handling
    ├── Resources       # Resource layer focused on presentation for tranforming data to json
    └── ...
├── Models          # Model layer focused on data model definitions
├── Repositories    # Repository layer focused on source data layer & abstraction
├── ...
```
