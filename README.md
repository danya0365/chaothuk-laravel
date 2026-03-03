<div align="center">

<h1>🏗️ Chaothuk — เช่าถูก</h1>
<p><strong>Thai Freelance & Hiring Marketplace · Full-Stack Web App + REST API</strong></p>

<p>
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img alt="Livewire" src="https://img.shields.io/badge/Livewire-3-FB70A9?style=for-the-badge&logo=livewire&logoColor=white"/>
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img alt="Docker" src="https://img.shields.io/badge/Docker-Sail-2496ED?style=for-the-badge&logo=docker&logoColor=white"/>
  <img alt="License" src="https://img.shields.io/badge/License-MIT-22C55E?style=for-the-badge"/>
</p>

<p><em>เชื่อมต่อผู้ว่าจ้างกับฟรีแลนซ์ไทย · Connecting Thai employers with skilled freelancers</em></p>

---

</div>

## 📖 Overview

**Chaothuk (เช่าถูก)** is a full-featured **Thai freelance and hiring marketplace** powered by Laravel 11. The platform includes:

- 🌐 **Livewire Frontend** — SPA-like web app with dark premium UI at `/frontend`
- 📱 **REST API** — Sanctum-protected JSON API for mobile apps at `/api`
- 🖥️ **Admin Backend** — Blade-based admin panel for platform management
- 👷 **Workers** list services as **Works**, employers post **Recruits**
- 🤝 Book, negotiate, confirm engagements with **real-time GPS tracking**
- 🎯 Gamified **Issue Points** reward system

---

## ✨ Features

### 🌐 Livewire Frontend (`/frontend`)

A full SPA-like web application built with **Livewire 3** and **Alpine.js**, featuring a premium dark theme with orange accents.

#### Pages & Routes (31 routes)

| Route | Page | Auth |
|-------|------|:----:|
| `/frontend` | 🏠 Home — Featured works, categories, search | |
| `/frontend/works` | 📦 Browse Works — Grid/list with filters & pagination | |
| `/frontend/works/{id}` | 📦 Work Detail — Images, reviews, bookings, sessions | |
| `/frontend/works/create` | ➕ Create Work | ✅ |
| `/frontend/works/{id}/edit` | ✏️ Edit Work | ✅ |
| `/frontend/works/{id}/bookings` | 📋 Work Bookings management | ✅ |
| `/frontend/works/{id}/availability` | 📅 Work Availability management | ✅ |
| `/frontend/recruits` | 👷 Browse Recruits — Job postings with filters | |
| `/frontend/recruits/{id}` | 👷 Recruit Detail — Apply, book | |
| `/frontend/recruits/create` | ➕ Create Recruit | ✅ |
| `/frontend/recruits/{id}/edit` | ✏️ Edit Recruit | ✅ |
| `/frontend/recruits/{id}/bookings` | 📋 Recruit Bookings management | ✅ |
| `/frontend/categories` | 🏷️ Category Browse | |
| `/frontend/map` | 🗺️ MapLibre Interactive Map — Explore by location | |
| `/frontend/search` | 🔍 Full Search — Works & recruits | |
| `/frontend/reputation/{id?}` | ⭐ Reputation Profile | |
| `/frontend/profile` | 👤 My Profile — Info, works, recruits, bookings, reviews | ✅ |
| `/frontend/calendar` | 📅 Calendar | ✅ |
| `/frontend/bookings` | 📋 My Bookings | ✅ |
| `/frontend/messenger` | 💬 Messenger | ✅ |
| `/frontend/favorites` | ⭐ Favorites | ✅ |
| `/frontend/portfolios` | 🎨 Portfolios | ✅ |
| `/frontend/portfolios/create` | ➕ Create Portfolio | ✅ |
| `/frontend/portfolios/{id}/edit` | ✏️ Edit Portfolio | ✅ |
| `/frontend/sessions` | 🕐 My Sessions — Active & completed sessions | ✅ |
| `/frontend/sessions/{id}` | 🕐 Session Detail — **Real-time GPS tracking** | ✅ |
| `/frontend/notifications` | 🔔 Notifications | ✅ |

#### 🔐 Frontend Auth Flow

| Route | Page |
|-------|------|
| `/frontend/auth/login` | เข้าสู่ระบบ — Email/password login with remember me |
| `/frontend/auth/register` | สมัครสมาชิก — Name, email, password registration |
| `/frontend/auth/forgot-password` | ลืมรหัสผ่าน — Password reset via email |
| `POST /frontend/auth/logout` | ออกจากระบบ — Logout with redirect to login |

- Guest users on `/frontend/*` auth-required pages are redirected to `/frontend/auth/login`
- All auth pages use the same dark premium theme

#### 🗺️ Real-Time GPS Session Tracking

- **Dark CartoDB map** tiles (premium look)
- **Live stats overlay**: elapsed time, distance (haversine), GPS point count, speed (km/h)
- **Browser Geolocation API** — `watchPosition` with continuous updates
- **Pulsing current-location marker** with heading arrow
- **Orange route line** with glow effect + point dots
- **Direct JS map updates** — no page reload needed
- Logs to `session_location_logs` table via Livewire

#### 📱 Responsive Navigation

| Platform | Design |
|----------|--------|
| **Desktop** | Fixed left sidebar (70px): 5 main items + "⋯ เพิ่มเติม" popup with remaining items + Alpine.js profile dropdown with logout |
| **Mobile** | Bottom tab bar: 4 items (Home, Works, Favorites, Chat) + "⋯ เพิ่มเติม" slide-up panel with all items |

---

### 📡 REST API (`/api`)

| Group | Base Path | Auth |
|-------|-----------|:----:|
| Auth | `/api/auth` | Partial |
| Me (Profile) | `/api/me` | ✅ |
| Works | `/api/works` | Partial |
| Work Bookings | `/api/work-bookings` | Partial |
| Recruits | `/api/recruits` | Partial |
| Recruit Bookings | `/api/recruit-bookings` | Partial |
| Sessions | `/api/sessions` | ✅ |
| Messenger | `/api/messenger` | Partial |
| Notifications | `/api/notifications` | ❌ |
| Banners | `/api/banners` | ✅ |
| Issue Points | `/api/issue-points` | ❌ |
| Barcode | `/api/barcode` | ❌ |
| Provinces | `/api/provinces` | ❌ |
| Uploads | `/api/upload` | ❌ |
| User Logs | `/api/user-logs` | ✅ |

**Session API Endpoints:**
- `POST /api/sessions/start` — Start a work session (auto-confirms worker)
- `POST /api/sessions/{id}/location` — Log GPS coordinates
- `GET /api/sessions/{id}/locations` — Get location history
- `POST /api/sessions/{id}/stop` — Stop session
- `POST /api/sessions/{id}/confirm` — Confirm completed session

API docs auto-generated via [Dedoc Scramble](https://scramble.dedoc.co/) at `http://localhost/docs/api`.

---

### 🔐 Authentication & Authorization
- **Sanctum Token** API authentication (login, register, logout, token revoke)
- **Livewire Frontend** auth (login, register, forgot-password, logout)
- **Mobile Phone** quick-registration (no email required)
- **Role-Based Access Control (RBAC)** — custom Roles & Permissions system
- **Soft Deletes** on user accounts

### 🛠️ Work Marketplace
- Post service listings with title, description, images, price, and location
- Filter by **WorkType**, **Province**, and status
- Status flow: `stand-by` → `busy` → `close`
- **Work Bookings** — customers book a worker; both sides confirm
- **Work Likes** — social engagement on listings
- **Reviews & Replies** with like support
- **Work Sessions** with real-time GPS tracking
- **Work Availability** scheduling
- Category tagging & top-hits ranking

### 📋 Recruit Marketplace
- Post job/recruit listings with budget, description, and work type
- **Recruit Bookings** — workers apply; mutual confirmation flow
- Category tagging & province-based filtering

### 🎯 Issue Points & Rewards
- Configurable point-issuing missions
- **One-time** and **repeating** point events (weekday/date-based)
- Point transaction logs for full audit trail
- User point balance tracking

### 💬 Messenger
- **Channel-based messaging** (authenticated users)
- **Mobile Phone channels** (guest users, no login required)
- Conversation history with pagination & `seen_at` tracking

### 🔔 Notifications
- In-app notification system with types: `announcement`, `work-booking`, `recruit-booking`, `work-like`, `work-review`, `review-reply`, `review-like`, `general`
- `last-update` endpoint for polling

### 🖼️ Banners & QR Generation
- Admin-managed promotional banners with pinning
- QR codes and Code128 (a/b/c) barcode generation via API

### 🗺️ Thai Geographic Data
- Full hierarchy: Geography → Province → District → Sub-District
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
| Reports | Point logs, issue point reports, status logs, activity logs, cron logs |

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
│   │   │   ├── Api/      # 19 API controllers (Sanctum-protected)
│   │   │   ├── Backend/  # 13 Admin panel controllers
│   │   │   └── Auth/     # Breeze auth scaffolding
│   │   ├── Requests/     # 19 Form Request validators
│   │   └── Resources/    # 36 API Resource transformers
│   ├── Livewire/
│   │   └── Frontend/     # 32 Livewire components
│   │       ├── Auth/     # Login, Register, ForgotPassword
│   │       ├── Home.php, WorkBrowse.php, WorkDetail.php, ...
│   │       └── SessionDetail.php  # GPS tracking
│   ├── Models/           # 35+ Eloquent models
│   ├── Providers/        # Service providers
│   └── Traits/           # Reusable Eloquent scopes
├── database/
│   ├── migrations/       # 50+ migration files
│   ├── seeders/          # 13 seeders including geo & mock data
│   └── factories/        # Model factories
├── resources/
│   └── views/
│       ├── frontend/     # Frontend layout (dark theme)
│       └── livewire/
│           └── frontend/ # 32 Livewire blade views
│               └── auth/ # Login, Register, ForgotPassword views
├── routes/
│   ├── api.php           # RESTful API routes
│   ├── frontend.php      # Livewire frontend routes (31 routes)
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
| Frontend Engine | **Livewire 3** + **Alpine.js** |
| Database | **MySQL 8** (via Laravel Sail) |
| Auth | **Laravel Sanctum** (API tokens) + **Livewire** (frontend sessions) |
| Maps | **MapLibre GL JS** + **CartoDB Dark Tiles** |
| CSS | **Tailwind CSS** |
| Build | **Vite** |
| Admin Frontend | **Blade** + Tailwind CSS |
| API Docs | **Dedoc Scramble** (auto OpenAPI) |
| Barcode | `milon/barcode` |
| Image | `intervention/image-laravel` |
| Dev Environment | **Laravel Sail** (Docker) |
| Testing | **PHPUnit 10** |
| Localization | Thai (`th`) · English (`en`) fallback |

---

## ✅ Implementation Progress

### Done ✔️

- [x] **Authentication** — Login, Register, Logout (API + Frontend Livewire)
- [x] **Frontend Auth Flow** — `/frontend/auth/login`, register, forgot-password with dark theme
- [x] **User Profiles** — Full CRUD, avatar, cover image, bio, location, theme
- [x] **Role & Permission system** — Custom RBAC with user/role-level permissions
- [x] **Work listings** — Create, list, detail, edit, top-hits, status management
- [x] **Work Bookings** — Create, worker-confirm, customer-confirm
- [x] **Work Availability** — Scheduling management
- [x] **Work Likes** — Toggle, count, list
- [x] **Work Sessions** — Start, pause, resume, stop, confirm with GPS tracking
- [x] **Real-time GPS Tracking** — Browser Geolocation + MapLibre dark map + live stats
- [x] **Session Location Logs** — GPS data logging via Livewire & API
- [x] **Recruit listings** — Create, list, detail, edit
- [x] **Recruit Bookings** — Create, worker-confirm, customer-confirm
- [x] **Issue Points** — Admin CRUD, scheduling (one-time & repeating), status workflow
- [x] **User Points** — Balance tracking (received, available, redeemed)
- [x] **Messenger** — Auth channels & guest/mobile phone channels
- [x] **Notifications** — System notifications with typed events
- [x] **Favorites** — Work favorites
- [x] **Portfolios** — Create, list, edit
- [x] **Categories** — Category browsing & tagging
- [x] **Map Explore** — Interactive MapLibre map for works & recruits
- [x] **Search** — Full search for works & recruits
- [x] **Reputation Profiles** — View user reputation
- [x] **Responsive Navigation** — Desktop sidebar (5+more) + mobile bottom tab (4+more)
- [x] **Banners** — Pinned banners, admin CRUD
- [x] **Barcode/QR** — QR, Code128 generation
- [x] **File Uploads** — Image, avatar, document
- [x] **Thai Geographic Data** — Province, District, Sub-District seeded
- [x] **Admin Backend** — Full panel
- [x] **Reports** — Point logs, activity logs, cron logs
- [x] **API Documentation** — Auto-generated via Dedoc Scramble
- [x] **Docker / Sail** — Containerized development

### Pending ❌

- [ ] **Payment Integration** — No payment gateway implemented
- [ ] **Real-time WebSockets** — Broadcasting set to `log`
- [ ] **Email Notifications** — Mailer set to `log`
- [ ] **SMS/OTP Verification** — No OTP challenge
- [ ] **Admin Analytics Dashboard** — No charts/stats
- [ ] **Unit & Feature Tests** — Minimal test coverage
- [ ] **CI/CD Pipeline** — No GitHub Actions
- [ ] **Production Deployment Guide** — No deployment docs

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

### 3. Start Docker Containers

```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
sail up -d
```

Application: **http://localhost** · Frontend: **http://localhost/frontend**

### 4. Database Setup

```bash
sail php artisan migrate
sail php artisan db:seed
```

### 5. Frontend Assets

```bash
sail yarn
sail yarn dev
```

### 6. Storage Link

```bash
sail artisan storage:link
```

---

## 🛠️ Development Commands

```bash
# Fresh database with re-seeding
sail php artisan migrate:fresh --seed

# Run scheduled tasks
sail php artisan schedule:work

# Laravel Tinker (REPL)
sail php artisan tinker

# List all routes
sail php artisan route:list --name=frontend
sail php artisan route:list --path=api
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
| `refactor` | Code restructure |
| `test` | Adding or fixing tests |
| `chore` | Build, tooling, or config changes |

---

## 🌏 Localization

- `APP_LOCALE=th` · `APP_FALLBACK_LOCALE=en`
- Translation files in `lang/th/` and `lang/en/`

---

## 📄 License

This project is licensed under the **MIT License**.
See the [LICENSE](LICENSE) file for details.

---

<div align="center">

Built with ❤️ in Thailand · Powered by [Laravel](https://laravel.com) + [Livewire](https://livewire.laravel.com)

</div>
