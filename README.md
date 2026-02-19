# NeuraCore — Event-Driven Workflow Engine (Pure PHP)

NeuraCore is a backend system built **entirely in pure PHP** that allows users to define workflows which execute asynchronously through a custom event and queue system.

The goal of this project is to demonstrate **senior-level backend engineering concepts** without relying on frameworks.

---

## ✨ Features

* Authentication system (login flow)
* Domain-driven architecture
* Workflow management
* Event-driven design
* Repository pattern with MySQL
* Service layer (use-case driven)
* Foundation for async jobs & queue
* Clean separation of concerns
* API endpoints testable via Postman

---

## 🏗️ Architecture

This project follows a layered architecture inspired by Clean Architecture and DDD.

```
HTTP Layer (Routes / Controllers)
        ↓
Application Layer (Services / Use Cases)
        ↓
Domain Layer (Entities / Interfaces)
        ↓
Infrastructure Layer (MySQL Repositories)
```

### Key Patterns Used

* Repository Pattern
* Dependency Injection (manual wiring)
* Domain Entities
* Service Layer
* Event-Driven Design (foundation)

---

## 📂 Project Structure

```
bin/
 |
config/
 |
logs/
 |
public/
 |
src/
 ├── Application/
 │    ├── Auth/
 │    ├── Workflow/
 │    ├── Event/
 │    └── Job/
 │
 ├── Domain/
 │    ├── User/
 │    ├── Workflow/
 │    ├── Event/
 │    └── Job/
 │
 ├── Infrastructure/
 │    └── Persistence/
 │
 ├── Controllers/
 │
 └── Core/
 │    ├── Router
 │    ├── Request
 │    ├── Response
 │    └── App
 |    ├── Contrller
 │    └── View
 |
 ├── Database/
 |
views/
    ├── Auth/
 
```

---

## 🚀 Getting Started

### 1️⃣ Clone the repo

```bash
git clone https://github.com/yourusername/neuracore.git
cd neuracore
```

---

### 2️⃣ Install dependencies

```bash
composer install
```

---

### 3️⃣ Configure database

Create a MySQL database:

```
neuracore
```

Update connection settings if needed:

```
src/Database/Connection.php
```

---

### 4️⃣ Run locally

Using PHP built-in server:

```bash
cd public
php -S localhost:8000
```

Open:

```
http://localhost:8000
```

---

## 🗄️ Database Example (Workflows)

```sql
CREATE TABLE workflows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    steps JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🔌 API Endpoints

### Auth

```
POST /login
```

### Workflows

```
GET  /workflows            → list workflows
POST /workflows/create     → create workflow
POST /workflows/start      → start workflow
POST /workflows/complete   → complete workflow
```

---


## 🛠️ Tech Stack

* PHP 8+
* MySQL
* Composer
* PSR-4 Autoloading

---

---

## 🔮 Roadmap

* [ ] Event dispatcher
* [ ] Queue worker
* [ ] Job processing system
* [ ] Retry & failure handling
* [ ] REST API responses (JSON)
* [ ] Docker setup
* [ ] Automated tests

---

## 👤 Author

**Zahir Zohair**

Backend engineer passionate about system design, architecture, and building scalable backend systems from scratch.

