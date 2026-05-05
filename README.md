![WellCook Logo](./public/images/WellCook-Logo2.png)

# WellCook

WellCook is a Laravel recipe and nutrition tracker that helps users discover recipes, save favorites, log meals, track daily macro goals, and chat with an AI nutrition assistant.

## Features

- Firebase email/password and Google authentication
- Recipe search and filtering by keyword, category, and cuisine
- Recipe details from TheMealDB, with nutrition enrichment support from Spoonacular
- Favorites saved per authenticated user
- Meal logging with calories, protein, carbs, and fat
- Dashboard progress against daily nutrition goals
- NutriBot chat assistant powered by Gemini for food, recipe, and nutrition help
- Profile updates, account deletion, and Firebase password reset flow

## Tech Stack

- PHP 8.2+
- Laravel 12
- Firebase Authentication and Realtime Database
- Kreait Laravel Firebase
- TheMealDB API
- Spoonacular API
- Google Gemini API
- Vite 7
- Tailwind CSS 4
- Pest / PHPUnit

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- SQLite, MySQL, or another Laravel-supported database
- Firebase project with Authentication and Realtime Database enabled
- API keys for Gemini and Spoonacular

## Setup

Clone the project and install dependencies:

```bash
composer install
npm install
```

Create the environment file and application key:

```bash
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell, use:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Create or configure your database, then run migrations:

```bash
php artisan migrate
```

Build frontend assets:

```bash
npm run build
```

## Environment Variables

Update `.env` with your local app, database, Firebase, and API settings.

```env
APP_NAME=WellCook
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

FIREBASE_API_KEY=
FIREBASE_AUTH_DOMAIN=
FIREBASE_PROJECT_ID=
FIREBASE_DATABASE_URL=
FIREBASE_CREDENTIALS_JSON=

GEMINI_API_KEY=
SPOONACULAR_API_KEY=
```

Firebase server credentials can be provided as `FIREBASE_CREDENTIALS_JSON`, `FIREBASE_CREDENTIALS`, or `GOOGLE_APPLICATION_CREDENTIALS`. For production deploys, prefer environment variables instead of committing credential files.

## Running Locally

Start the Laravel server and Vite dev server in separate terminals:

```bash
php artisan serve
npm run dev
```

Or use the Composer dev script to run the app server, queue listener, and Vite together:

```bash
composer run dev
```

Open the app at:

```text
http://localhost:8000
```

## Main Routes

- `/login` - sign in
- `/register` - create an account
- `/home` - recipe discovery
- `/recipe/{id}` - recipe details
- `/dashboard` - daily nutrition dashboard
- `/meal-log` - meal logging and history
- `/favorites` - saved recipes
- `/profile` - profile settings

Protected routes require an authenticated Firebase session.

## Testing

Run the test suite:

```bash
php artisan test
```

Or use the Composer script:

```bash
composer test
```

## Deployment Notes

The repository includes a `Dockerfile` and `render.yaml` for deployment-oriented configuration. Before deploying, make sure production environment variables are set for:

- `APP_KEY`
- database connection
- Firebase project credentials
- `FIREBASE_DATABASE_URL`
- `GEMINI_API_KEY`
- `SPOONACULAR_API_KEY`

Run these during deployment as needed:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Project Structure

- `app/Http/Controllers` - web controllers for auth, recipes, dashboard, meals, favorites, profile, and chat
- `app/Services` - API service wrappers for recipe data
- `resources/views` - Blade pages, layouts, and partials
- `resources/js` - frontend behavior for auth, recipes, meal logs, navigation, and chat
- `public/css` - page-specific stylesheets
- `routes/web.php` - browser routes
- `config/firebase.php` - Firebase Admin SDK configuration

## License

This project is built on Laravel, which is open-sourced under the MIT license.
