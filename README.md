# Multi-tenant Inventory (Laravel)

## Requirements
- PHP 8.x
- Composer
- MySQL
- Node.js (optional if using Laravel Mix)
- XAMPP / Valet / Homestead

## Setup
1. Clone repository
2. `cp .env.example .env` and update DB credentials
3. `composer install`
4. `npm install && npm run dev` (if using assets)
5. `php artisan key:generate`
6. Create database and import SQL backup if provided:
   - Using phpMyAdmin or `mysql -u root -p < backup.sql`
7. `php artisan migrate --seed`
8. Storage link: `php artisan storage:link`
9. Queue setup:
   - `php artisan queue:table`
   - `php artisan migrate`
   - set `QUEUE_CONNECTION=database` in `.env`
   - run worker: `php artisan queue:work`
10. Serve: `php artisan serve`

## API
- `POST /api/login` — login (sanctum)
- `GET /api/api-products` — list products for authenticated user
- `POST /api/api-products` — create product
- `POST /api/orders` — place order (API)

## Web UI
- Uses standard session auth.
- `/products` — products CRUD
- `/orders` — orders list (web)
- `/orders/create` — create order (web)

## Testing
Run tests:
```bash
php artisan test
