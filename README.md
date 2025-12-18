# Xtra App - Event Management System

A modern event management application built with Laravel 11, Inertia.js, and Vue 3. This application allows users to create, manage, and register for events with features like capacity management, email notifications, and race condition protection.

## Features

- **Event Management**: Create, edit, delete, and restore events
- **Event Registration**: Users can register for events with capacity limits
- **Email Notifications**: Automatic email confirmations for registrations
- **User Authentication**: Secure authentication using Laravel Jetstream
- **Race Condition Protection**: Database-level locking prevents overbooking
- **Responsive Design**: Modern UI built with Tailwind CSS
- **Image Upload**: Support for event images
- **Soft Deletes**: Events can be deleted and restored

## Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Vue 3 with Inertia.js
- **Authentication**: Laravel Jetstream with Fortify
- **Database**: SQLite (default) or MariaDB (with Docker)
- **Styling**: Tailwind CSS
- **Build Tool**: Vite
- **Email**: Laravel Mail (with Mailpit for development)

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (for local development) or Docker (for containerized setup)

## Installation

### Option 1: Local Development (SQLite)

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd xtra-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Set up environment file**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   
   The application uses SQLite by default. Ensure the database file exists:
   ```bash
   touch database/database.sqlite
   ```
   
   Update your `.env` file:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database/database.sqlite
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   ```
   
   Or for development with hot reload:
   ```bash
   npm run dev
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

### Option 2: Docker Setup (Laravel Sail)

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd xtra-app
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Set up environment file**
   ```bash
   cp .env.example .env
   ```

4. **Configure Docker environment**
   
   Update your `.env` file with Docker settings:
   ```env
   DB_CONNECTION=mariadb
   DB_HOST=mariadb
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=sail
   DB_PASSWORD=password
   ```

5. **Start Docker containers**
   ```bash
   ./vendor/bin/sail up -d
   ```

6. **Generate application key**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

7. **Run migrations**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

8. **Build frontend assets**
   ```bash
   ./vendor/bin/sail npm run build
   ```
   
   Or for development:
   ```bash
   ./vendor/bin/sail npm run dev
   ```

   The application will be available at `http://localhost` (or the port specified in `APP_PORT`)

## Usage

### Creating an Account

1. Navigate to the registration page
2. Fill in your name, email, and password
3. Accept the terms of service and privacy policy
4. Click "Register"

### Creating an Event

1. Log in to your account
2. Click "Manage Events" in the navigation
3. Click "Add Event"
4. Fill in the event details:
   - **Event Name**: Name of the event (minimum 5 characters)
   - **Description**: Detailed description (maximum 5000 characters)
   - **Date & Time**: When the event will occur
   - **Event Image**: Optional image for the event
   - **Total Capacity**: Maximum number of attendees (minimum 1)
5. Click "Create Event"

### Registering for an Event

1. Browse events on the home page
2. Click "Register" on any available event
3. You'll receive a confirmation email with event details
4. The event creator will also receive a notification email

### Managing Your Events

1. Navigate to "Manage Events"
2. View all your created events in a data table
3. Edit events by clicking the edit button
4. Delete events (they can be restored later)
5. Restore deleted events if needed

### Event Capacity

- Events can have a capacity limit or be unlimited
- When an event reaches capacity, it shows as "Full"
- The system prevents overbooking using database-level locking
- Users cannot register for events they're already registered for

## Development

### Running Tests

```bash
php artisan test
```

Or with Docker:
```bash
./vendor/bin/sail artisan test
```

### Code Style

The project uses Laravel Pint for code formatting:

```bash
./vendor/bin/pint
```

### Development Workflow

For a complete development environment with hot reload:

```bash
composer dev
```

This command runs:
- Laravel development server
- Queue worker
- Laravel Pail (logs)
- Vite dev server

### Database Seeding

Seed the database with sample events:

```bash
php artisan db:seed --class=EventSeeder
```

## Email Configuration

### Development (Mailpit)

When using Docker, Mailpit is included and available at:
- SMTP: `localhost:1025`
- Web UI: `http://localhost:8025`

### Production

Update your `.env` file with your mail provider settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Security Features

- **Race Condition Protection**: Database transactions with row-level locking prevent concurrent registration issues
- **Authorization**: Event creators can only edit/delete their own events
- **Authentication**: Secure authentication with Laravel Jetstream
- **CSRF Protection**: Built-in CSRF protection for all forms
- **SQL Injection Protection**: Using Eloquent ORM with parameter binding

## Project Structure

```
xtra-app/
├── app/
│   ├── Http/Controllers/    # Application controllers
│   ├── Mail/                 # Email notification classes
│   ├── Models/               # Eloquent models
│   └── Policies/             # Authorization policies
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   ├── js/
│   │   ├── Components/        # Vue components
│   │   ├── Layouts/          # Layout components
│   │   └── Pages/             # Inertia pages
│   └── views/                # Blade templates
├── routes/
│   └── web.php               # Web routes
└── tests/                    # PHPUnit tests
```

## Troubleshooting

### Database Issues

If you encounter database errors:
1. Ensure the SQLite file exists: `touch database/database.sqlite`
2. Check file permissions
3. Run migrations: `php artisan migrate:fresh`

### Frontend Issues

If assets aren't loading:
1. Clear cache: `php artisan cache:clear`
2. Rebuild assets: `npm run build`
3. Clear Vite cache: `rm -rf node_modules/.vite`

### Permission Issues

On Linux/Mac, you may need to set permissions:
```bash
chmod -R 775 storage bootstrap/cache
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and ensure code style compliance
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues and questions, please open an issue on the repository.

