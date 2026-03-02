<div align="center">

<h1>🏗️ Chaothuk — เช่าถูก</h1>
<p><strong>Thai Freelance & Hiring Marketplace · REST API Backend</strong></p>

<p>
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img alt="Docker" src="https://img.shields.io/badge/Docker-Sail-2496ED?style=for-the-badge&logo=docker&logoColor=white"/>
  <img alt="License" src="https://img.shields.io/badge/License-MIT-22C55E?style=for-the-badge"/>
</p>

<p><em>เชื่อมต่อผู้ว่าจ้างกับฟรีแลนซ์ไทย · Connecting Thai employers with skilled freelancers</em></p>

---

</div>

## 📖 Overview

**Chaothuk (เช่าถูก)** is a full-featured **Thai freelance and hiring marketplace** powered by Laravel 11. The platform enables:

- 👷 **Workers** (freelancers) to list their services as **Works**
- 🏢 **Employers** to post job openings as **Recruits**
- 🤝 Both parties to **book, negotiate, and confirm** engagements
- 🎯 A gamified **Issue Points** reward system to incentivize participation
- 💬 Real-time **Messenger** communication between users

The backend exposes a **RESTful JSON API** (Laravel Sanctum) consumed by mobile apps and web frontends, alongside a **Blade-based Admin Panel** for platform management.

---

## ✨ Features

### 🔐 Authentication & Authorization
- **Sanctum Token** API authentication (login, register, logout, token revoke)
- **Mobile Phone** quick-registration (no email required)
- **Role-Based Access Control (RBAC)** — custom Roles & Permissions system
- **Soft Deletes** on user accounts
- Permissions: `access_backend`, `manage_role`, `manage_permission`, `create_work`, `create_recruit`, `review_work`, `reply_review`

### 👤 User Profiles
- Full profile: name, avatar, cover photo, bio, mobile phone, location, birth date
- Theme customization support
- Avatar auto-generation via `ui-avatars.com`
- Activity log tracking per user

### 🛠️ Work Marketplace (Freelancers)
- Post service listings with title, description, images, price, and location
- Filter by **WorkType**, **Province**, and status
- Status flow: `stand-by` → `busy` → `close`
- **Work Bookings** — customers book a worker; both sides confirm
- **Work Likes** — social engagement on listings
- **Reviews & Replies** with like support
- Category tagging
- Top-hits ranking
- Average review rating

### 📋 Recruit Marketplace (Employers)
- Post job/recruit listings with budget, description, and work type
- Status management: `open` / `close`
- **Recruit Bookings** — workers apply; mutual confirmation flow
- Category tagging
- Province-based filtering

### 🎯 Issue Points & Rewards
- Configurable point-issuing missions (`IssuePoint`)
- Supports **one-time** and **repeating** point events
  - Repeat by **weekday** (`AT_WEEKDAY_IN_WEEK`)
  - Repeat by **date in month** (`AT_DATE_IN_MONTH`)
- **Point transaction logs** for full audit trail
- User point balance (received, available, redeemed)
- Schedule list view for admins
- Status flow: `pending` → `approve`

### 💬 Messenger
- **Channel-based messaging** (authenticated users)
- **Mobile Phone channels** (guest users, no login required)
- Conversation history with pagination
- "Last message" endpoint for polling/sync
- `seen_at` tracking per conversation

### 🔔 Notifications
- In-app notification system with types:
  `announcement`, `work-booking`, `recruit-booking`, `work-like`, `work-review`, `review-reply`, `review-like`, `general`
- `last-update` endpoint for efficient polling

### 🖼️ Banners
- Admin-managed promotional banners
- Pinned banners endpoint
- Banner types & scheduling

### 🏷️ Barcode & QR Generation
- Generate **QR codes** and **Code128 (a/b/c)** barcodes via API
- Browser-preview endpoints separate from raw output

### 🗺️ Thai Geographic Data
- Full hierarchy: **Geography → Province → District → Sub-District**
- 77 provinces pre-seeded for location filtering

### 📊 Admin Backend
| Module | Features |
|--------|----------|
| Users | List, create, edit, delete, manage roles & permissions |
| Roles | Custom role management |
| Works | Moderate all work listings |
| Recruits | Moderate all recruit posts |
| Notifications | Broadcast announcements |
| Banners | Create and manage app banners |
| Configurations | System-wide key-value config store |
| Messenger Channels | Monitor and manage chat channels |
| Issue Points | Define and schedule reward missions |
| Reports | Point logs, issue point reports, status logs, activity logs, cron logs, transaction logs |

### 📁 File Uploads
- Upload images, avatars, documents, and original-size images
- `intervention/image-laravel` for image processing
- Public storage symlink support

### ⏰ Scheduled Jobs / Cron
- CronJob framework built in (`Kernel.php`)
- Cron log model for tracking execution history
- `schedule:work` for local testing

---

## 🏛️ Architecture

```
chaothuk-laravel/
├── app/
│   ├── Console/          # Artisan commands & Kernel schedule
│   ├── Enums/            # 20 typed PHP enums (Status, Roles, Types…)
│   ├── Events/           # Domain events
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/      # 18 API controllers (Sanctum-protected)
│   │   │   ├── Backend/  # 13 Admin panel controllers
│   │   │   └── Auth/     # Breeze auth scaffolding
│   │   ├── Requests/     # 19 Form Request validators
│   │   └── Resources/    # 36 API Resource transformers
│   ├── Listeners/        # Event listeners
│   ├── Models/           # 35 Eloquent models
│   ├── Providers/        # Service providers
│   ├── Traits/           # Reusable Eloquent scopes
│   └── View/             # View composers / components
├── database/
│   ├── migrations/       # 46 migration files
│   ├── seeders/          # 13 seeders including geo & mock data
│   └── factories/        # Model factories
├── resources/            # Blade views, CSS, JS assets
├── routes/
│   ├── api.php           # RESTful API routes
│   ├── web.php           # Admin web routes
│   ├── auth.php          # Breeze auth routes
│   └── ajax.php          # AJAX-only routes
└── lang/                 # Multilingual strings (th/en)
```

---

## 🧱 Tech Stack

| Layer | Technology |
|-------|-----------|
| Language | **PHP 8.2** |
| Framework | **Laravel 11** |
| Database | **MySQL 8** (via Laravel Sail) |
| Auth | **Laravel Sanctum** (API tokens) |
| Frontend (Admin) | **Blade** + **Tailwind CSS** + **Vite** |
| API Docs | **Dedoc Scramble** (auto OpenAPI) |
| Barcode | `milon/barcode` |
| Image | `intervention/image-laravel` |
| CRUD Generator | `ibex/crud-generator` |
| Dev Environment | **Laravel Sail** (Docker) |
| Testing | **PHPUnit 10** |
| Localization | Thai (`th`) · English (`en`) fallback |

---

## ✅ Implementation Progress

### Done ✔️

- [x] **Authentication** — Login, Register, Logout, Token Revoke, Mobile-phone registration
- [x] **User Profiles** — Full CRUD, avatar, cover image, bio, location, theme
- [x] **Role & Permission system** — Custom RBAC with user/role-level permissions
- [x] **Work listings** — Create, list, detail, top-hits, status management
- [x] **Work Bookings** — Create, worker-confirm, customer-confirm
- [x] **Work Likes** — Toggle, count, list
- [x] **Recruit listings** — Create, list, detail
- [x] **Recruit Bookings** — Create, worker-confirm, customer-confirm
- [x] **Issue Points** — Admin CRUD, scheduling (one-time & repeating), status workflow
- [x] **User Points** — Balance tracking (received, available, redeemed)
- [x] **Point Transaction Logs** — Full audit trail
- [x] **Messenger (auth users)** — Channels, conversations, last message, seen-at
- [x] **Messenger (guest/mobile)** — Phone-based channel creation & messaging
- [x] **Notifications** — System notifications with typed events, last-update polling
- [x] **Banners** — Pinned banners, admin CRUD
- [x] **Barcode/QR** — QR, Code128 a/b/c generation and preview
- [x] **File Uploads** — Image, avatar, document, original image
- [x] **Thai Geographic Data** — Province, District, Sub-District seeded
- [x] **Categories** — Works & Recruits category tagging
- [x] **Admin Backend** — Full panel: Users, Roles, Works, Recruits, Banners, Configs, Messenger, Issue Points
- [x] **Reports** — Point logs, issue point reports, status logs, activity logs, cron logs
- [x] **Cron/Schedule** — Kernel scheduling, CronLog tracking
- [x] **User Activity Logs** — Track user actions
- [x] **Configurations** — Key-value system config store
- [x] **API Documentation** — Auto-generated via Dedoc Scramble
- [x] **Docker / Sail** — Full containerized development environment
- [x] **Reviews & Replies** — Review model and reply model with likes (DB migrations exist)

### In Progress / Partial 🔄

- [ ] **Posts** — `posts` & `post_likes` migration exists (2024-10-25) but no Model, Controller, or Routes yet
- [ ] **Works Reviews** — `works_reviews` & `recruits_reviews` migration exists but dedicated Review API endpoints not wired up
- [ ] **Recruit Categories browsing** — Category model exists but no API filter endpoint for recruits by category
- [ ] **Notification Dispatch** — `UserNotification` model exists but no push/FCM delivery service implemented

### Pending / Not Started ❌

- [ ] **Payment Integration** — No payment gateway (PromptPay, Omise, Stripe) implemented
- [ ] **Rating Aggregation** — `avg_review_rating` field exists on Work but no automated calculation job
- [ ] **Search & Full-text Filter** — No ElasticSearch or Laravel Scout integration
- [ ] **Real-time WebSockets** — Broadcasting set to `log`; no Pusher/Reverb configuration
- [ ] **Email Notifications** — Mailer set to `log`; no real email templates
- [ ] **SMS/OTP Verification** — Mobile phone registration exists but no OTP challenge
- [ ] **Admin Analytics Dashboard** — No charts/stats on the admin home page
- [ ] **Unit & Feature Tests** — PHPUnit configured but test coverage is minimal
- [ ] **CI/CD Pipeline** — No GitHub Actions or deployment workflow
- [ ] **Production Deployment Guide** — No Forge/Vapor/server setup documentation

---

## 🚀 Getting Started

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- PHP 8.2+ & Composer (for initial install only)

### 1. Clone & Install

```bash
git clone <repository-url> chaothuk-laravel
cd chaothuk-laravel
composer install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials if needed (defaults work with Sail).

### 3. Start Docker Containers

```bash
# Add Sail alias (recommended)
echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.zshrc
source ~/.zshrc

# Start all containers in background
sail up -d
```

Application will be available at **http://localhost**

### 4. Database Setup

```bash
# Run migrations
sail php artisan migrate

# Seed with initial data (geographic data + mock data)
sail php artisan db:seed

# Or seed a specific class
sail php artisan db:seed --class=MockSeeder
```

### 5. Frontend Assets

```bash
# Install dependencies
sail yarn

# Development build (with hot reload)
sail yarn dev
```

### 6. Storage Link

```bash
sail artisan storage:link
```

---

## ⚙️ Database Management

Access **PGAdmin** (if using PostgreSQL) or MySQL Workbench via:

| Tool | URL |
|------|-----|
| App | http://localhost |
| PGAdmin | http://localhost:5050 |

**PGAdmin connection details:**
- Host: `host.docker.internal`
- Port: `5432`
- User: `sail`
- Password: `password`

---

## 📡 API Reference

API documentation is **auto-generated** by [Dedoc Scramble](https://scramble.dedoc.co/).

Once the app is running, visit:
```
http://localhost/docs/api
```

### Key Endpoints Summary

| Group | Base Path | Auth Required |
|-------|-----------|:---:|
| Auth | `/api/auth` | Partial |
| Me (Profile) | `/api/me` | ✅ |
| Works | `/api/works` | Partial |
| Work Bookings | `/api/work-bookings` | Partial |
| Recruits | `/api/recruits` | Partial |
| Recruit Bookings | `/api/recruit-bookings` | Partial |
| Messenger | `/api/messenger` | Partial |
| Notifications | `/api/notifications` | ❌ |
| Banners | `/api/banners` | ✅ |
| Issue Points | `/api/issue-points` | ❌ |
| Barcode | `/api/barcode` | ❌ |
| Provinces | `/api/provinces` | ❌ |
| Uploads | `/api/upload` | ❌ |
| User Logs | `/api/user-logs` | ✅ |

---

## 🛠️ Development Commands

```bash
# Generate a new CRUD resource
sail php artisan make:migration create_mymodel_table
sail php artisan make:crud mymodel

# Run scheduled tasks (local)
sail php artisan schedule:work

# Force-run all scheduled tasks once
sail php artisan schedule:run

# View all scheduled tasks
sail php artisan schedule:list

# Fresh database with re-seeding
sail php artisan migrate:fresh --seed

# Laravel Tinker (REPL)
sail php artisan tinker

# Show PHP version
./vendor/bin/sail php --version
```

---

## 📝 Commit Convention

This project follows [Conventional Commits](https://www.conventionalcommits.org/):

```
<type>(<scope>): <subject>
```

| Type | Description |
|------|-------------|
| `feat` | New feature |
| `fix` | Bug fix |
| `docs` | Documentation only |
| `style` | Formatting, no logic change |
| `refactor` | Code restructure without feature change |
| `test` | Adding or fixing tests |
| `chore` | Build, tooling, or config changes |

**Example:**
```
feat(recruits): add category filter endpoint
fix(auth): handle expired Sanctum token gracefully
```

---

## 🌏 Localization

The application is localized with **Thai as the primary language**:

- `APP_LOCALE=th`
- `APP_FALLBACK_LOCALE=en`
- Translation files located in `lang/th/` and `lang/en/`

---

## 📄 License

This project is licensed under the **MIT License**.  
See the [LICENSE](LICENSE) file for details.

---

<div align="center">

Built with ❤️ in Thailand · Powered by [Laravel](https://laravel.com)

</div>
