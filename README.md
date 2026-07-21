<div align="center">

# 🚀 AI-Blogging-Platform

*The Next-Generation AI-Powered Content Management & Blogging Platform*

Elevate your content creation with an intelligent platform that generates SEO-optimized articles, manages your publications, and fosters reader engagement automatically using the power of **Google Gemini AI**.

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](#)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](#)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](#)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](#)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](#)
[![Gemini](https://img.shields.io/badge/Gemini_AI-8A2BE2?style=for-the-badge&logo=google&logoColor=white)](#)
[![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)](#)

</div>

---

## 📖 Overview

**AI-Blogging-Platform** is a highly scalable, robust content management system (CMS) and blogging platform designed for modern creators, authors, and publishers.

Creating high-quality, SEO-optimized blog posts from scratch is time-consuming and often causes writer's block. **AI-Blogging-Platform** solves this by integrating Artificial Intelligence deeply into the drafting process. By simply providing a topic prompt, the platform's AI controller acts as an expert SEO specialist, generating a complete, richly-formatted article—including a catchy title, meta excerpt, HTML content, and relevant tags—in seconds. The platform also offers robust social engagement features, allowing readers to interact with authors through comments, favorites, bookmarks, and a comprehensive follow system.

---

## ✨ Features

### 🤖 AI Features
- **AI Content Generator:** Utilizes Google Gemini 2.5 Flash to automatically draft complete blog posts based on a user-provided prompt.
- **SEO Optimization:** AI automatically generates meta excerpts, tags, and SEO-friendly titles alongside the content.
- **Structured JSON Responses:** Strict prompt engineering ensures the AI returns clean JSON that perfectly maps to the database structure.

### 👥 User & Identity Management
- **Advanced Authentication:** Built using Laravel Fortify with support for standard login, Two-Factor Authentication (2FA), and modern Passkeys.
- **Roles & Permissions:** Secure Role-Based Access Control (RBAC). Differentiates between standard users, authors, and administrators.
- **Profile Management:** Custom user profiles with tracking of account status (Active, Inactive, Suspended) and last activity.

### 📝 Content Management (CMS)
- **Rich Post Creation:** Create articles with cover images, categories, and tags.
- **Post Workflows:** Manage articles through multiple states (Draft, Published, Archived).
- **Soft Deletes:** Accidentally deleted an article? Safely recover it from the trash or permanently force delete it from the dashboard.
- **View Tracking:** Asynchronous event-driven view counters to track article popularity.

### 💬 Social & Engagement Features
- **Follow System:** Users can follow and unfollow their favorite authors to curate their reading experience.
- **Favorites (Likes):** Readers can "like" their favorite articles.
- **Bookmarks:** Readers can save specific articles to their private reading list.
- **Nested Comments:** Interactive comment sections on every published post.

### 🔔 Notifications System
- **Real-Time Alerts:** Database-driven notifications for interactions (when someone comments on your post, favorites it, or follows you).
- **Notification Dashboard:** Mark individual alerts as read/unread or clear all notifications at once.

### ⚡ Background Jobs & Processing
- **Queue-based Mailing:** Background processing of automated emails (e.g., `SendNewPostsSummary`).
- **Async Notifications:** System notifications are dispatched asynchronously via queues to ensure blazing-fast UI response times (`SendNotification` job).

### 🔒 Security Features
- **Route Authorization:** Comprehensive use of Laravel Gates and Policies (`UserPolicy`, `PostPolicy`) to ensure users only modify their own data.
- **Sanctum API Protection:** Built-in support for secure API token generation using Laravel Sanctum (`personal_access_tokens`).
- **CSRF & XSS Protection:** Full cross-site request forgery and cross-site scripting prevention.

---

## 🛠️ Technologies Used

| Technology | Purpose / Role |
|:---|:---|
| **PHP 8.3** | Core backend language utilizing the latest features and types. |
| **Laravel 13.x** | The MVC framework handling routing, ORM, requests, and security. |
| **MySQL** | Relational database management system. |
| **Tailwind CSS v4** | Utility-first frontend styling framework. |
| **Vite** | Lightning-fast frontend build tool and hot module replacement. |
| **Vanilla JavaScript** | DOM manipulation and client-side API requests. |
| **Google Gemini API** | Autonomous AI content generation (`gemini-2.5-flash`). |
| **Laravel Fortify** | Headless backend authentication logic. |
| **Laravel Sanctum** | API authentication and token management. |
| **Composer / NPM** | Package managers for backend and frontend dependencies. |

---

## 🏗️ System Architecture

The application is built on a strictly decoupled architecture, extending beyond standard MVC by utilizing Actions, Services, and Events to keep controllers exceptionally clean and maintainable.

- **MVC Pattern:** The core of the application utilizing Models, Views (Blade), and Controllers.
- **Service Layer:** Abstracted complex business logic (e.g., `PostService`) for creating and managing posts.
- **Actions Pattern:** Single-responsibility classes handling specific tasks like `FileUpload` for handling cover images and `SyncPostTags` for managing many-to-many tag relationships.
- **Events & Listeners:** A robust event-driven architecture. For example, viewing a post triggers a `PostViewed` event, which is handled asynchronously by an `IncrementPostViews` listener.
- **Jobs:** Background tasks like `SendNewPostsSummary` handle heavy lifting off the main web thread.
- **Policies:** Authorization logic is strictly isolated in classes like `PostPolicy` and `UserPolicy`.

---

## 🗄️ Database Design

The relational database is highly normalized and relies heavily on Eloquent relationships (One-to-Many, Many-to-Many, and Polymorphic).

### Main Entities
- `users`: Core identity table (includes Two-Factor Auth, Passkeys, and Activity tracking).
- `posts`: The primary content table (includes metadata, soft deletes, and status tracking).
- `categories` & `tags`: Taxonomy tables for organizing posts.
- `comments`: Relates to both Users (authors) and Posts.
- `roles`: RBAC entity.

### Pivot / Interaction Tables
- `post_tag`: Many-to-Many relationship between posts and tags.
- `favorites`: Many-to-Many mapping for users "liking" posts.
- `bookmarks`: Many-to-Many mapping for users saving posts.
- `followers`: Self-referential Many-to-Many mapping linking users to other users.
- `role_user`: Maps users to specific administrative roles.

---

## 📂 Project Structure

```text
├── app/
│   ├── Actions/            # Single-responsibility logic (FileUpload, SyncPostTags)
│   ├── Events/             # Application events (PostViewed)
│   ├── Http/Controllers/   # Request handlers (AiController, PostController)
│   ├── Http/Requests/      # Form validation and sanitation rules
│   ├── Jobs/               # Queued background tasks (SendNewPostsSummary)
│   ├── Listeners/          # Event handlers (IncrementPostViews)
│   ├── Models/             # Eloquent ORM Models and Scopes
│   ├── Notifications/      # Database notification classes (FollowNotification)
│   ├── Policies/           # Authorization logic (PostPolicy)
│   └── Services/           # Business logic abstraction (PostService)
├── database/               
│   └── migrations/         # Database schema definitions
├── resources/
│   ├── css/                # Tailwind CSS configurations
│   ├── js/                 # Client-side JavaScript
│   └── views/              # Blade templating files (Dashboard, Auth)
├── routes/                 # Web, API, and Console route definitions
└── tests/                  # PHPUnit Feature and Unit tests
```

---

## 🤖 AI Workflow (Under the Hood)

AI-Blogging-Platform integrates seamlessly with Google's Gemini models:

1. **User Prompt:** A user submits a topic via the Dashboard (`AiController`).
2. **System Injection:** The backend formulates a strict system prompt requiring the AI to act as an SEO specialist and format the output exclusively as a specific JSON schema.
3. **Execution:** The request is sent asynchronously to the Gemini API (`gemini-2.5-flash`).
4. **Parsing:** The Controller catches the response, strips markdown wrappers, decodes the JSON, and safely populates the post creation form dynamically on the frontend.

---

## 🚀 Installation Guide

### 1. Clone the repository
```bash
git clone https://github.com/yourusername/write-ai.git
cd AI-Blogging-Platform
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup (MySQL)
Create a new MySQL database for the project. Open your `.env` file and configure the connection:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run Migrations
```bash
php artisan migrate
# Or to include seeders: php artisan migrate --seed
```

### 6. Link Local Storage (Crucial for Images)
```bash
php artisan storage:link
```

### 7. Run the Application
Compile the frontend assets and start the server concurrently:
```bash
# This command runs Laravel Serve, Vite, and the Queue Worker simultaneously
composer dev 
```

Visit `http://localhost:8000` in your browser.

---

## 🔑 Environment Variables

The following variables in the `.env` file are crucial for the application to function:

```env
APP_NAME="AI-Blogging-Platform"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

# Gemini AI Configuration
GEMINI_API_KEY=your_gemini_api_key
GEMINI_BASE_URL=https://generativelanguage.googleapis.com/v1beta
GEMINI_MODEL=gemini-2.5-flash

# Queue & Cache
QUEUE_CONNECTION=database
CACHE_STORE=database
```

---

## ⚡ Performance Optimizations

- **Eager Loading:** Used extensively in controllers (e.g., `Post::with('category', 'user')`) to eradicate the N+1 query problem.
- **Asynchronous Queues:** Email dispatching and notification generation are pushed to the database queue rather than holding up the HTTP response.
- **Event-Driven Counters:** Page views are handled via the `PostViewed` event rather than synchronous database writes during page loads.

---

## 🧪 Testing

The application is thoroughly tested using PHPUnit.
To run the test suite:

```bash
# Clear caches and run tests
composer test

# Or using artisan
php artisan test
```

---

## 🌐 Deployment

For production deployment, run the following commands:

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# Ensure the queue worker is running via Supervisor or similar
php artisan queue:work
```

---

## ⌨️ Available Artisan Commands

```bash
php artisan migrate                # Run database migrations
php artisan migrate:fresh --seed   # Reset database and seed fake data
php artisan storage:link           # Link public storage directory
php artisan queue:listen           # Start processing background jobs
php artisan optimize:clear         # Clear all application caches
php artisan route:list             # View all registered endpoints
```

---

## 🔮 Future Improvements

- Implementation of Redis for faster caching and queue management.
- OAuth Social Login integrations (Google, GitHub) via Laravel Socialite.
- Detailed reporting and analytics dashboard for authors.
- Enhanced AI features including automated image generation for post covers.

---

## 🤝 Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📜 License

Distributed under the MIT License. See `LICENSE` for more information.

---

## 🙏 Credits

- **Framework:** [Laravel](https://laravel.com/)
- **Frontend UI:** [TailwindCSS](https://tailwindcss.com/)
- **AI Engine:** [Google Gemini](https://aistudio.google.com/)
- **Authentication:** [Laravel Fortify](https://laravel.com/docs/fortify)
