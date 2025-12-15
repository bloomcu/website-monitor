# Website Monitor

A Laravel + Vue.js application for monitoring website uptime.

## Getting Started

### Prerequisites

-   PHP 8.2+
-   Node.js & NPM
-   MySQL

### Installation

1. composer install
2. npm install
3. php artisan migrate
4. npm run build
5. php artisan serve
6. php artisan schedule:work
7. php artisan queue:work

### Running Locally

To run the application locally, you need several processes running:

1. npm run build
2. php artisan serve
3. php artisan schedule:work
4. php artisan queue:work

**Uptime Checker**:
The uptime checker relies on the Laravel Scheduler and Queue system.

To run the scheduler (to dispatch checks hourly):

```bash
php artisan schedule:work
```

To process the checks (background jobs):

```bash
php artisan queue:work
```

## Production Deployment (Laravel Forge)

This project is optimized for deployment on Laravel Forge.

### 1. Scheduler

Ensure the Laravel Scheduler is enabled. Forge usually sets this up automatically, but verify you have a Cron job running:

```bash
php /home/forge/your-site.com/current/artisan schedule:run
```

Set the frequency to "Every Minute".

### 2. Queue Workers

You must configure a Queue Worker to process the uptime check jobs.

-   **Connection**: `database` (or `redis` if configured)
-   **Queue**: `default`
-   **Timeout**: `0` or sufficient time (checks have internal timeouts but the job handles multiple)
-   **Processes**: 1 or more depending on load.

### 3. Environment Variables

Ensure `APP_URL` is set correctly in your `.env` file on Forge.
