# How NeuraCore Works (Step by Step)

This document explains the flow of the NeuraCore event-driven workflow engine.

---

## Step 1: Request Entry

Every HTTP request hits `public/index.php`:

```
1. session_start()        → Enables session for auth
2. vendor/autoload.php    → Composer PSR-4 autoloading
3. new App()              → Creates the application
4. $app->run()            → Bootstraps and dispatches the request
```

The `public/` directory is the web server document root.

---

## Step 2: Bootstrap (App.php)

`App::run()` wires everything together:

| Component | Purpose |
|-----------|---------|
| **PDO** | Database connection via `Connection::make()` |
| **EventDispatcher** | Event listeners → dispatch flow |
| **Repositories** | MySQL implementations for Workflow, Event, User, Job |
| **Services** | Auth, Workflow, Event, Job — application use cases |
| **Routes** | Loaded from `config/routes.php` |

---

## Step 3: Event Listener Registration

Before routes are loaded, listeners are registered on `workflow.created`:

```php
// Listener 1: Logs the workflow ID
$dispatcher->listen('workflow.created', function ($event) {
    error_log('Workflow created ID: ' . $event->payload()['workflow_id']);
});

// Listener 2: Creates a background job (send_notification)
$dispatcher->listen('workflow.created', function ($event) use ($jobService) {
    $jobService->dispatch('send_notification', $event->payload());
});
```

---

## Step 4: Routing

The router matches `METHOD + URI` to a handler:

| Method | URI | Handler |
|--------|-----|---------|
| GET | `/` | Closure (welcome message) |
| GET | `/login` | AuthController::showLoginForm |
| POST | `/login` | AuthController::login |
| GET | `/workflows` | WorkflowController::index |
| POST | `/workflows/create` | WorkflowController::create |
| POST | `/workflows/start` | WorkflowController::start |
| POST | `/workflows/complete` | WorkflowController::complete |

---

## Step 5: Data Flow — Creating a Workflow

When `POST /workflows/create` is called:

```
1. Router → WorkflowController::create($request)
2. WorkflowController → WorkflowService::create(name, userId, steps)
3. WorkflowService:
   a. Creates Workflow entity
   b. WorkflowRepository::save() → persisted to MySQL
   c. EventService::fire('workflow.created', [workflow_id, user_id, name])
4. EventService:
   a. Creates Event entity
   b. EventRepository::save() → persisted to MySQL
   c. EventDispatcher::dispatch($event)
5. EventDispatcher runs all 'workflow.created' listeners:
   a. Listener 1: error_log(workflow_id)
   b. Listener 2: JobService::dispatch('send_notification', payload)
6. JobService::dispatch:
   a. Creates Job entity (type=send_notification, status=pending)
   b. JobRepository::save() → persisted to MySQL
7. Response sent to client
```

---

## Step 6: Authentication Flow

```
POST /login (email, password)
  → AuthController::login()
  → AuthService::attempt(email, password)
     → UserRepository::findByEmail()
     → User::verifyPassword()
  → If valid: $_SESSION['user_id'] = $user->id()
  → Response::redirect('/')
```

---

## Step 7: Layered Architecture

```
HTTP Layer      → Controllers (AuthController, WorkflowController)
Application     → Services (Auth, Workflow, Event, Job)
Domain          → Entities + Repository interfaces
Infrastructure  → MySQL*Repository implementations
```
