# API Turno - Shipping Label Management System

System developed with Laravel 12, Vue.js 3, and Inertia.js for managing shipping labels using the EasyPost API.

## 🏗️ Architecture

This project uses **Domain-Driven Design (DDD)** and **Clean Architecture**, following best practices for separation of concerns:

```
app/
├── Application/          # Application layer (use cases, DTOs, services)
├── Domain/              # Domain layer (entities, value objects, exceptions)
├── Infrastructure/      # Infrastructure layer (repositories, external services)
└── Http/               # Presentation layer (controllers, requests, middleware)
```

## 🚀 Technologies

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Vue.js 3, Inertia.js, Tailwind CSS 4
- **Database:** MySQL/PostgreSQL/SQLite
- **Queue:** Laravel Queues (for asynchronous label processing)
- **Broadcasting:** Pusher (for real-time updates)
- **External API:** EasyPost (for shipping label creation)

## 📋 Features

- ✅ User authentication (login, register, logout)
- ✅ User management (full CRUD)
- ✅ Shipping label creation
- ✅ Asynchronous label processing
- ✅ Real-time updates via WebSocket
- ✅ Dashboard with statistics
- ✅ Address validation
- ✅ Permissions and authorization system (Policies)

## 🔧 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and NPM
- MySQL/PostgreSQL or SQLite

### Steps

1. Clone the repository:
```bash
git clone <repository-url>
cd api-turno
```

2. Install PHP dependencies:
```bash
composer install
```

3. Configure the `.env` file:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure environment variables in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=turno
DB_USERNAME=root
DB_PASSWORD=

EASYPOST_API_KEY=your_easypost_api_key
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
```

> **Note:** Pusher tokens and EasyPost API key configuration examples can be found in the `.env.example` file.

5. Run migrations:
```bash
php artisan migrate
```

6. Install frontend dependencies:
```bash
npm install
```

7. Build assets:
```bash
npm run build
```

## 🏃 Development

To run the complete development environment (server, queue, logs, and vite):

```bash
composer dev
```

Or run individually:

```bash
# Terminal 1 - PHP Server
php artisan serve

# Terminal 2 - Queue Worker
php artisan queue:listen

# Terminal 3 - Vite (frontend)
npm run dev

# Terminal 4 - Logs (optional)
php artisan pail
```

## 📝 Testing

Run tests with:

```bash
composer test
# or
php artisan test
```

## 🔒 Security

- **Authorization:** Policy system for access control
- **Authentication:** Laravel Session Authentication
- **Validation:** Form Requests for data validation
- **Sanitization:** Strict input validation
- **Rate Limiting:** Configured on routes when necessary

## 📚 Domain Structure

### Domain: User
- Entities: `User`
- Value Objects: `Email`, `Name`
- Exceptions: `UserNotFoundException`, `InvalidCredentialsException`, etc.

### Domain: ShippingLabel
- Entities: `ShippingLabel`
- Value Objects: `Address`, `Package`, `ShippingLabelStatus`, `USState`, etc.
- Exceptions: `ShippingLabelNotFoundException`, `UnauthorizedAccessException`, etc.

## 🔄 Shipping Label Creation Flow

1. User creates a label through the form
2. Controller validates data and creates a DTO
3. Service creates the domain entity
4. `ProcessShippingLabelJob` is dispatched to the queue
5. Job processes the label through a Pipeline
6. Provider (EasyPost) creates the label on external API
7. `ShippingLabelProcessed` event is fired
8. Frontend receives update via WebSocket

## 📦 Shipping Providers

The system supports multiple shipping providers through the Strategy pattern:

- **EasyPost** (implemented)

To add a new provider:
1. Implement `ShippingProviderInterface`
2. Register it in `AppServiceProvider`

## 🗄️ Database

### Main Tables

- `users` - System users
- `shipping_labels` - Shipping labels
- `request_logs` - HTTP request logs for external APIs
- `jobs` - Queue jobs
- `sessions` - User sessions

## 🤝 Contributing

1. Follow established code standards (PSR-12)
2. Write tests for new features
3. Document significant changes
4. Use descriptive commits

## 📄 License

This project is licensed under the MIT license.

## 👥 Team

Developed for TURNO company.
