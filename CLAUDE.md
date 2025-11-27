# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

MyWeeklyAllowance is a virtual pocket money management system for teenagers. The project is designed as a TDD (Test-Driven Development) exercise where tests are written first, followed by implementation.

**Core Features:**
- Create accounts for teenagers
- Deposit money into accounts
- Record expenses
- Set up automatic weekly allowances

## Project Structure

This is a PHP project organized using MVC architecture:

```
src/
├── model/       # Domain models and business logic
├── controller/  # Application controllers
└── view/        # Presentation layer
tests/           # PHPUnit test files
public/          # Web-accessible files (index.php entry point)
```

**PSR-4 Autoloading:** Classes in `src/` use the namespace `Julesbourin\MyWeeklyAllowance\`.

## Development Commands

### Running Tests

```bash
# Run all tests
vendor/bin/phpunit

# Run tests with coverage report (requires Xdebug)
vendor/bin/phpunit --coverage-html coverage

# Run a specific test file
vendor/bin/phpunit tests/YourTestFile.php

# Run a specific test method
vendor/bin/phpunit --filter testMethodName
```

### Dependency Management

```bash
# Install dependencies
composer install

# Update dependencies
composer update

# Dump autoloader (after adding new classes)
composer dump-autoload
```

## TDD Workflow

This project follows strict TDD methodology:

1. **RED Phase:** Write failing tests first
2. **GREEN Phase:** Implement minimal code to pass tests
3. **REFACTOR Phase:** Clean up code while keeping tests green
4. **Coverage Phase:** Verify test coverage is adequate

When implementing features, always write tests before writing implementation code.

## Architecture Notes

The project uses MVC pattern with the following responsibilities:

- **Model:** Contains domain entities (e.g., Teenager account, Transaction records) and business logic
- **Controller:** Handles user requests, coordinates between models and views
- **View:** Manages presentation logic and output formatting

The entry point is `public/index.php`.