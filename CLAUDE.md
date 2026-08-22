# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Architecture and Structure

This project follows a Laravel-based **Domain-Driven Design (DDD)** structure.

- **`app/Domains/`**: The core business logic is partitioned here by domain.
  - Each domain (e.g., `Announcements`, `ElectronicServices`) is structured as:
    - `Actions/`: Single-action classes for business processes.
    - `Contracts/`: Interfaces for repositories and services.
    - `DTOs/`: Data Transfer Objects for structured data.
    - `Models/`: Eloquent models representing domain entities.
    - `Repositories/`: Eloquent implementations of domain repositories.
    - `Services/`: Domain-specific business logic.
    - `Providers/`: Domain-specific `ServiceProvider` that binds repositories and singletons.
- **`app/Livewire/`**: Contains Livewire components for the frontend.
- **`app/Providers/AppServiceProvider.php`**: The main entry point that registers all domain service providers.
- **`routes/web.php`**: Maps URLs to Livewire components.

## Development Commands

### Setup
- **`composer setup`**: Install dependencies, generate APP_KEY, run migrations, install NPM packages, and build assets.

### Development
- **`composer dev`**: Run server, queue, and vite (concurrently).

### Testing
- **`composer test`**: Run config:clear, lint, types:check, and Pest tests.
- **`php artisan test`**: Run all tests.
- **`php artisan test --filter NameOfTest`**: Run a specific test.

### Linting and Static Analysis
- **`composer lint`**: Run Pint for code style.
- **`composer types:check`**: Run PHPStan for static analysis.

## Conventions

- **Strict Typing**: Use `declare(strict_types=1);` in all classes, interfaces, and migration files.
- **Transactions**: Always wrap database modifications in `DB::transaction()` within repository implementations to ensure data integrity.
- **Dependency Injection**: Repositories must be bound to their interfaces in the corresponding domain `ServiceProvider`.
