# 🧠 Task Management API

A RESTful Task Management API built with Laravel, using UUIDs for all models, pagination, and OpenAPI documentation. Includes full Docker support for local development and deployment to Render.

---

## 🚀 Getting Started

### 🔧 Prerequisites

Ensure you have the following installed:

-   [Docker](https://www.docker.com/)
-   [Docker Compose](https://docs.docker.com/compose/)
-   [Git](https://git-scm.com/)

---

## 📦 Running Locally with Docker

### 1. Clone the Repository

```bash
git clone https://github.com/kolawolejohn/laravel-taskmanagement-api
cd laravel-taskmanagement-api
```

```bash
cp .env.example .env
## please update the .env with your own credentials if on local docker
```

## Build and Run Containers

```bash
# if you want to run the docker in the background run
docker-compose up -d --build
# else run
docker-compose up --build
```

## Install Dependencies (Inside Container)

```bash
docker-compose exec app composer install
```

## Generate App Key

```bash
docker-compose exec app php artisan key:generate
```

## Run Migrations

```bash
docker exec -it laravel_task_app php artisan migrate --force
```

### 🔢 Run Database Seeders

To run seeders inside the running container:

```bash
# If you're using Docker Compose
docker exec -it laravel_task_app php artisan db:seed
```

## NB

❗️We will not seed for production, it is only for local use to test the app

### 📘 API Documentation

Access the Swagger API documentation here:
[http://localhost:8000/api/documentation#/](http://localhost:8000/api/documentation#/)

To regenerate Swagger documentation after modifying annotations:

```bash
php artisan l5-swagger:generate
```

### Production BASE_URL

https://laravel-taskmanagement-api.onrender.com/api/tasks

### Acessing endpoint

You can access the endpoints as so
-- GET http://localhost:8000/api/tasks?status=completed

-- GET http://localhost:8000/api/tasks?fiter=add_a_title

-- POST http://localhost:8000/api/tasks
{
"title": "title",
"description": "description",
"status": "defaulted to pending, so can be omitted"
}

etc
