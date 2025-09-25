# ABC Cars Backend API Documentation

## Authentication
The API uses Laravel Sanctum for authentication. Register/Login endpoints are already available via Laravel Breeze.

### Auth Endpoints (Already implemented by Breeze)
- `POST /register` - Register new user
- `POST /login` - Login user
- `POST /logout` - Logout user

## API Endpoints

### Profile Management
- `GET /api/profile` - Get current user profile
- `PUT /api/profile` - Update user profile

### Car Management
- `GET /api/cars` - List all cars (public)
- `GET /api/cars/{id}` - Get car details (public)
- `POST /api/cars` - Create new car listing (auth required)
- `PUT /api/cars/{id}` - Update car listing (owner only)
- `DELETE /api/cars/{id}` - Delete car listing (owner only)

### Bidding System
- `GET /api/bids` - Get user's bids (auth required)
- `POST /api/bids` - Place a bid on a car (auth required)
- `GET /api/bids/{id}` - Get bid details
- `PUT /api/bids/{id}` - Accept/reject bid (car owner only)
- `DELETE /api/bids/{id}` - Delete bid (bidder only)

### Car Rental
- `GET /api/rentals` - Get user's rentals (auth required)
- `POST /api/rentals` - Create rental booking (auth required)
- `GET /api/rentals/{id}` - Get rental details
- `PUT /api/rentals/{id}` - Update rental status (car owner only)
- `DELETE /api/rentals/{id}` - Cancel rental

## Request Examples

### Create Car Listing
```json
POST /api/cars
{
    "title": "2020 Toyota Camry",
    "description": "Well maintained sedan",
    "brand": "Toyota",
    "model": "Camry",
    "year": 2020,
    "price": 25000,
    "rental_price_per_day": 50,
    "type": "both",
    "image_url": "https://example.com/car.jpg"
}
```

### Place Bid
```json
POST /api/bids
{
    "car_id": 1,
    "amount": 23000
}
```

### Create Rental
```json
POST /api/rentals
{
    "car_id": 1,
    "start_date": "2024-01-15",
    "end_date": "2024-01-20"
}
```

### Update Profile
```json
PUT /api/profile
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "address": "123 Main St, City, State",
    "avatar": "https://example.com/avatar.jpg"
}
```

## Database Schema

### Cars Table
- id, user_id, title, description, brand, model, year
- price, rental_price_per_day, type (sale/rental/both)
- status (available/sold/rented), image_url, timestamps

### Bids Table
- id, car_id, user_id, amount
- status (pending/accepted/rejected), timestamps

### Rentals Table
- id, car_id, user_id, start_date, end_date
- total_amount, status (pending/confirmed/completed/cancelled), timestamps

### Users Table (Extended)
- id, name, email, password, phone, address, avatar, timestamps