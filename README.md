# Transport Units Management System

A scalable fleet management solution for handling trucks and trailers, built with Laravel (backend API) and Vue.js (frontend).

## Features

### Core Functionality
- Dual interface tabs for Trucks (≥3.5t) and Trailers
- Full-text search across transport unit names
- Creation form for new transport units
- Optimized performance for large datasets (>100,000 units)

### Technical Implementation
- Type-safe Enum handling (Truck/Trailer)
- Comprehensive API test coverage
- Reactive Vue.js interface
- PostgreSQL database with query optimization
- Redis caching layer
- Paginated API responses
- Database indexing strategy

## Technology Stack
**Backend**
- Laravel 10.x
- PostgreSQL 14+
- Redis 6+
- PHPUnit 10+

**Frontend**
- Vue.js 3.x
- Vue Router 4.x
- Axios 1.x
- Bootstrap 5.x

## System Architecture
    Frontend[Vue.js Frontend] -->|REST API| Backend[Laravel API]
    Backend --> Database[PostgreSQL]
    Backend --> Cache[Redis]
    Database --> Indexes[Optimized Indexes]
    Indexes --> TypeIndex[Type Filtering]
    Indexes --> NameIndex[Name Search]


## API Specification
### Endpoints

| Method | Endpoint               | Parameters                  | Description                     |
|--------|------------------------|-----------------------------|---------------------------------|
| GET    | `/api/transport-units` | `type`, `search`, `per_page`| Filtered unit listing           |
| POST   | `/api/transport-units` | `name`, `type`              | Create new transport unit       |

**Parameters Explanation:**
- `type`: Filter by transport unit type (`truck` or `trailer`)
- `search`: Search term for name matching (partial matches)
- `per_page`: Number of items per page (default: 50, max: 100)
- `name`: Unique name for new transport unit (required)

### Response Example

```json
{
  "data": [
    {
      "id": 1,
      "name": "Heavy Duty Trailer",
      "type": "trailer",
      "created_at": "2023-07-21T09:45:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 204,
    "total_trucks": 51234,
    "total_trailers": 48921
  }
}
```
# Transport Units Management System

## API Specification

### Endpoints

| Method | Endpoint               | Parameters                  | Description                     |
|--------|------------------------|-----------------------------|---------------------------------|
| GET    | `/api/transport-units` | `type`, `search`, `per_page`| Filtered unit listing           |
| POST   | `/api/transport-units` | `name`, `type`              | Create new transport unit       |

### Response Example

```json
{
  "data": [
    {
      "id": 1,
      "name": "Heavy Duty Trailer",
      "type": "trailer",
      "created_at": "2023-07-21T09:45:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 204,
    "total_trucks": 51234,
    "total_trailers": 48921
  }
}
```

## Installation Guide

### Clone Repository
```bash
git clone https://github.com/mikiv19/transport-units.git
cd transport-units
```

### Install Dependencies
```bash
composer install
npm install
```

### Configure Environment
```bash
cp .env.example .env
# Configure database and cache settings
```

### Initialize Database
```bash
php artisan migrate
php artisan db:seed
```

### Generate Application Key
```bash
php artisan key:generate
```

### Build Frontend Assets
```bash
npm run build
```

### Start Development Server
```bash
php artisan serve
```

## Testing Suite

Execute backend tests with:
```bash
php artisan test
```

### Test Coverage Includes:
- API endpoint validation
- Database operations
- Error handling workflows


