# Multi-Tenant Inventory System

## Setup

1. Clone repo
2. `composer install`
3. `cp .env.example .env`
4. Set DB credentials
5. `php artisan migrate`
6. `php artisan serve`

## Features

- Multi-tenancy with shop isolation
- Product CRUD (API + UI)
- Order system with stock validation
- Queue-based confirmation (mocked)

## API Endpoints

- `POST /api/login`
- `GET /api/products`
- `POST /api/orders`

## Testing

```bash
php artisan test
