# RestaurantSystem

Laravel restaurant website with Royal Restaurant public design and admin panel.

## Local setup (Windows PowerShell)

1. Create a MySQL database named `restaurant_system` in phpMyAdmin.
2. Copy `.env.example` to `.env` and set your MySQL username/password.
3. Run:

```powershell
composer install
php artisan key:generate
npm install
php artisan migrate --seed
npm.cmd run build
php artisan storage:link
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Initial accounts

- Super Admin: `superadmin@restaurant.com` / `SuperAdmin@123`
- Admin: `admin@restaurant.com` / `Admin@123`

Change these passwords before putting the site online.

## Production deployment

- Set `APP_ENV=production`, `APP_DEBUG=false`, and the real `APP_URL`.
- Configure production MySQL credentials and mail settings in `.env`.
- Run `composer install --no-dev --optimize-autoloader`.
- Run `npm install` and `npm run build`.
- Run `php artisan migrate --force`.
- Run `php artisan storage:link`.
- Set `storage` and `bootstrap/cache` writable by the web server.
- Point the hosting document root to the project's `public` directory.
- Never upload `.env` to a public repository or expose it.
