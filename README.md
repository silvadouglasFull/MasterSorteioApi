# 🎁 Lumen Giveaway API

This is a RESTful API built with [Lumen](https://lumen.laravel.com/) (Laravel's micro-framework) that allows users to register for a giveaway. Once a configurable number of users have registered, the API will automatically trigger a draw to determine the winners.

## 📌 Features

-   Register users to participate in a giveaway
-   Automatically trigger the draw once the configured limit is reached
-   Retrieve all participants or individual entries
-   Update and delete participant records
-   Count the total entries with a stylized image response

## 🐳 Dockerized Setup

This project is fully dockerized using Docker Compose. It includes:

-   PHP 8.2 with Lumen API
-   MySQL 8.0 for the database
-   Nginx as reverse proxy
-   PhpMyAdmin for database inspection

## 🚀 Getting Started

### Prerequisites

Make sure you have installed:

-   Docker
-   Docker Compose

### Running the API

1. Clone the repository:

```bash
git clone https://github.com/silvadouglasFull/MasterSorteioApi
cd MasterSorteioApi
```

2. Create a .env file using the example:

```bash
cp .env.example .env
```

3. Update the database environment variables in .env:

```ini
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

5. Start the containers:

```bash
docker-compose up -d --build
```

6. Access the application:

API: http://localhost

PhpMyAdmin: http://localhost:8080

### API Endpoints

Method | Endpoint | Description
GET | /forms | List all registered participants
GET | /forms/{id} | Get details of a specific entry
POST | /forms | Register a new user
PUT | /forms/{id} | Update a user's entry
DELETE | /forms/{id} | Delete a user record
GET | /forms-count | Get total number of entries (with images)
GET | /Awarded/wasAwarded | get the lucky user

### Example Payload for POST /forms

```json
{
    "form_doc": "12345678900",
    "form_email": "example@email.com",
    "form_name": "John Doe",
    "form_name_doc": "RG 1234567"
}
```

### ⚙️ Configuration

The number of users required to trigger the giveaway draw is configurable through the environment or a configuration file in the service layer (not shown here, assumed to be handled in Awarded logic).

### 🧪 Testing

You can use tools like Postman or cURL to test the API locally.
