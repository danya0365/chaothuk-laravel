# AI Workspace Context — Chaothuk Laravel
> Last Updated: 2026-03-02

## Project Identity

| Key | Value |
|-----|-------|
| **App Name** | Chaothuk — เช่าถูก |
| **Type** | Thai Freelance & Hiring Marketplace (REST API Backend + Admin Panel) |
| **Framework** | Laravel 11 |
| **Language** | PHP 8.2 |
| **Database** | MySQL 8 (via Laravel Sail / Docker) |
| **Auth** | Laravel Sanctum (API Token) |
| **Locale** | Thai (`th`) / English fallback (`en`) |
| **Dev Env** | Laravel Sail (Docker Compose) |
| **URL (local)** | http://localhost |

---

## Core Concept

**Chaothuk** เชื่อมต่อ **ฟรีแลนซ์** (Workers) กับ **ผู้ว่าจ้าง** (Employers) ในประเทศไทย:
- Workers สร้าง **Work listings** (บริการที่รับทำ)
- Employers สร้าง **Recruit listings** (งานที่ต้องการจ้าง)
- ทั้งสองฝ่ายจอง → ยืนยัน → ขยับสถานะ
- ระบบ **Issue Points** rewards ให้กับผู้ใช้
- **Messenger** ทั้ง authenticated และ guest (mobile phone channel)

---

## Tech Stack

```
PHP 8.2
Laravel 11
MySQL 8
Laravel Sanctum (API tokens)
Laravel Breeze (auth scaffolding)
Laravel Sail (Docker dev environment)
Tailwind CSS + Vite (frontend build)
dedoc/scramble (auto OpenAPI docs → /docs/api)
intervention/image-laravel (image processing)
milon/barcode (QR + barcode generation)
ibex/crud-generator (dev tool)
spatie/laravel-options
tightenco/ziggy (JS route helper)
```

---

## Directory Structure

```
chaothuk-laravel/
├── .agent/
│   └── ai_workspace_context.md   ← this file
├── app/
│   ├── Console/                  # Artisan commands + Kernel (cron schedule)
│   ├── Enums/                    # 20 PHP 8.1+ backed enums
│   ├── Events/                   # Domain events (4 files)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/              # 18 API controllers
│   │   │   ├── Backend/          # 13 Admin blade controllers
│   │   │   └── Auth/             # 9 Breeze auth controllers
│   │   ├── Requests/             # 19 Form Request validators
│   │   └── Resources/            # 36 API Resource transformers
│   ├── Listeners/                # 4 event listeners
│   ├── Models/                   # 35 Eloquent models
│   ├── Providers/
│   ├── Traits/                   # Scopes trait (limitOffset etc.)
│   └── View/
├── database/
│   ├── migrations/               # 46 migration files
│   ├── seeders/                  # 13 seeders
│   └── factories/
├── lang/
│   ├── th/                       # Thai translations
│   └── en/                       # English translations
├── resources/                    # Blade views + CSS + JS
├── routes/
│   ├── api.php                   # REST API routes
│   ├── web.php                   # Admin web routes
│   ├── auth.php                  # Breeze auth routes
│   └── ajax.php                  # AJAX-only routes
└── docker-compose.yml
```

---

## Models (35 total)

| Model | Table | Key Fields | Relations |
|-------|-------|-----------|-----------|
| `User` | `users` | name, email, password, first_name, last_name, mobile_phone, profile_image, cover_image, biography, theme, role_id | hasMany Works, Recruits, UserPoints, Notifications; BelongsToMany Roles, Permissions |
| `Work` | `works` | code, title, description, details (JSON), images (JSON), price, avg_review_rating, display_priority, work_status, province_id, work_type_id, author_id | BelongsTo User, Province, WorkType; HasMany WorkBookings; BelongsToMany Categories |
| `WorkBooking` | `work_bookings` | work_id, author_id, customer_message, mobile_phone, booking_date, booking_status, worker_confirm_at, customer_confirm_at | BelongsTo Work, User |
| `WorkLike` | `work_likes` | work_id, author_id | — |
| `WorkType` | `work_types` | name | — |
| `Recruit` | `recruits` | title, description, images (JSON), budget, recruit_status, province_id, work_type_id, author_id | BelongsTo User, Province, WorkType; HasMany RecruitBookings; BelongsToMany Categories |
| `RecruitBooking` | `recruit_bookings` | recruit_id, author_id, booking_status, worker_confirm_at, customer_confirm_at | BelongsTo Recruit, User |
| `Role` | `roles` | name, slug | BelongsToMany Users, Permissions |
| `Permission` | `permissions` | name, slug | BelongsToMany Roles, Users |
| `RolePermission` | `role_permissions` | role_id, permission_id, data | pivot |
| `UserPermission` | `user_permissions` | user_id, permission_id, data, desc | pivot |
| `IssuePoint` | `issue_points` | slug, name, desc, points, type, status, cron_task, cron_info (JSON), start_at, end_at | BelongsTo User; morphMany PointTransactionLogs |
| `IssuePointStatusLog` | `issue_point_status_logs` | issue_point_id, status, note | — |
| `UserPoint` | `user_points` | user_id, point_received, point_available, expired_at | BelongsTo User |
| `UserPointLog` | `user_point_logs` | user_id, issue_point_id, points, action | — |
| `PointTransactionLog` | `point_transaction_logs` | user_id, transactionable (morph), points, note | morph |
| `MessengerChannel` | `messenger_channels` | name, type, mobile_phone | HasMany Participants, Conversations |
| `MessengerConversation` | `messenger_conversations` | channel_id, participant_id, message, type, seen_at | BelongsTo Channel |
| `MessengerParticipant` | `messenger_participants` | channel_id, user_id | BelongsTo Channel, User |
| `Notification` | `notifications` | title, body, type, is_pinned | — |
| `UserNotification` | `user_notifications` | author_id, title, details (JSON), notification_type, is_read, notificationable (morph) | BelongsTo User; morph Works/Recruits |
| `Banner` | `banners` | title, image, type, is_pinned, sort_order, start_at, end_at | — |
| `Category` | `categories` | name, slug | BelongsToMany Works, Recruits |
| `Configuration` | `configurations` | key, value, value_type, desc | — |
| `Province` | `provinces` | name_th, name_en, geography_id | — |
| `District` | `districts` | name_th, name_en, province_id | — |
| `SubDistrict` | `sub_districts` | name_th, name_en, district_id | — |
| `Geography` | `geographies` | name | — |
| `Cron` | `crons` | name, command, status | — |
| `CronLog` | `cron_logs` | cron_id, type, message, executed_at | — |
| `UserActivityLog` | `user_activity_logs` | user_id, action, target, note | — |
| `Review` | `reviews` | — | — |
| `ReviewLike` | `review_likes` | — | — |
| `Reply` | `replies` | — | — |
| `ReplyLike` | `reply_likes` | — | — |

---

## Enums (20 total)

| Enum | Values |
|------|--------|
| `Role` | SUPERVISOR=1, BACKEND=2, MEMBER=3, MOBILE_PHONE=4 |
| `Permission` | access_backend, manage_permission, manage_role, create_recruit, create_work, review_work, reply_review |
| `WorkStatus` | stand-by, busy, close |
| `RecruitStatus` | open, close |
| `BookingStatus` | pending, confirm, cancel |
| `ConfirmStatus` | pending, confirm, cancel |
| `IssueType` | one_time, repeat |
| `IssueStatus` | pending, approve |
| `CronRepeatType` | at_weekday_in_week, at_date_in_month |
| `CronLogType` | info, error, success |
| `MessengerConversationType` | text, image, file |
| `NotificationType` | announcement, work-booking-confirm, recruit-booking-confirm, work-like, work-review, review-reply, review-like, recruit-booking, work-booking, general |
| `BannerType` | image, video |
| `ConfigurationValueType` | string, integer, boolean, json |
| `UserActivityType` | login, logout, register, update_profile |
| `Gender` | male, female, other |
| `PersonType` | individual, company |
| `Theme` | light, dark |
| `ThemeScheme` | default, custom |

---

## API Routes (`/api`)

### Auth (`/api/auth`)
| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/auth/login` | ❌ | Login, returns Sanctum token |
| POST | `/auth/register` | ❌ | Register new user |
| POST | `/auth/customer-register-login` | ❌ | Mobile phone auto-register/login |
| GET | `/auth/user` | ✅ | Get authenticated user |
| POST | `/auth/logout` | ✅ | Logout (revoke current token) |
| POST | `/auth/revoke-token` | ✅ | Revoke specific token |

### Me (`/api/me`) — all require auth
| Method | Path | Description |
|--------|------|-------------|
| GET | `/me` | Get own profile |
| POST | `/me` | Update own profile |
| POST | `/me/password` | Change password |
| GET | `/me/notifications` | Get user notifications |
| GET | `/me/works` | Get own works |
| GET | `/me/work-likes` | Get liked works |
| GET | `/me/work-likes/work/{workId}` | Check if liked a work |
| GET | `/me/work-bookings` | Get own work bookings |
| GET | `/me/recruits` | Get own recruits |
| GET | `/me/recruit-bookings` | Get own recruit bookings |

### Works (`/api/works`)
| Method | Path | Auth | Description |
|--------|------|------|-------------|
| GET | `/works` | ❌ | List works (filter: keyword, province_id, date, limit, offset) |
| GET | `/works/top-hits` | ❌ | Top-priority works |
| GET | `/works/{id}` | ❌ | Work detail |
| GET | `/works/{id}/likes` | ❌ | Work likes users list |
| GET | `/works/{id}/likes/count` | ❌ | Like count |
| GET | `/works/{id}/bookings` | ❌ | All bookings for work |
| GET | `/works/{id}/confirm-bookings` | ❌ | Confirmed bookings (supports date_start/date_end filter) |
| POST | `/works` | ✅ | Create work (requires `create_work` permission) |
| POST | `/works/{id}/bookings` | ✅ | Book a work (requires: customer_message, mobile_phone, booking_date) |
| POST | `/works/{id}/likes` | ✅ | Toggle like on a work |

### Work Bookings (`/api/work-bookings`)
| Method | Path | Auth | Description |
|--------|------|------|-------------|
| GET | `/{id}` | ❌ | Booking detail |
| POST | `/{id}/worker-confirm` | ✅ | Worker confirms booking |
| POST | `/{id}/customer-confirm` | ✅ | Customer confirms booking |

### Recruits (`/api/recruits`)
| Method | Path | Auth | Description |
|--------|------|------|-------------|
| GET | `/recruits` | ❌ | List recruits |
| GET | `/recruits/{id}` | ❌ | Recruit detail |
| GET | `/recruits/{id}/bookings` | ❌ | Bookings for recruit |
| POST | `/recruits` | ✅ | Create recruit |
| POST | `/recruits/{id}/bookings` | ✅ | Apply to recruit |

### Recruit Bookings (`/api/recruit-bookings`)
| Method | Path | Auth | Description |
|--------|------|------|-------------|
| GET | `/{id}` | ❌ | Booking detail |
| POST | `/{id}/worker-confirm` | ✅ | Worker confirms |
| POST | `/{id}/customer-confirm` | ✅ | Customer confirms |

### Messenger (`/api/messenger`)
| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/mobile-phone-channel/new` | ❌ | Create guest phone channel |
| GET | `/mobile-phone-channel/{id}/{phone}/conversations` | ❌ | Guest channel history |
| GET | `/mobile-phone-channel/{id}/{phone}/conversations/last` | ❌ | Last guest message |
| POST | `/mobile-phone-channel/{id}/{phone}/conversations` | ❌ | Send guest message |
| POST | `/channel/{id}/conversations/{convId}/seen` | ❌ | Mark as seen |
| GET | `/channel/me` | ✅ | My channel |
| GET | `/channel/{id}/conversations` | ✅ | Auth channel history |
| POST | `/channel/{id}/conversations` | ✅ | Send auth message |
| GET | `/channel/{id}/conversations/last` | ✅ | Last auth message |

### Other APIs
| Group | Path | Auth | Description |
|-------|------|------|-------------|
| Notifications | `/notifications` `/notifications/last-update` `/notifications/{id}` | ❌ | Notification list + last-update polling |
| Banners | `/banners` `/banners/pinned` `/banners/last-update` `/banners/{id}` | ✅ | Banner management |
| Issue Points | `/issue-points/{id}` | ❌ | Issue point detail |
| Provinces | `/provinces` | ❌ | All provinces list |
| Work Types | `/work-types` | ❌ | All work types |
| Configurations | `/configurations` | ❌ | App configurations |
| Barcode | `/barcode/qr/{code}` `/barcode/code128{a/b/c}/{code}` | ❌ | Generate barcode/QR |
| Barcode Preview | `/barcode-preview/qr/{code}` `/barcode-preview/code128{...}/{code}` | ❌ | Preview barcode |
| Upload | `/upload/image` `/upload/avatar` `/upload/document` `/upload/original-image` | ❌ | File uploads |
| User Logs | `/user-logs/point-logs` `/user-logs/point-transaction-logs` | ✅ | Point history |

---

## Admin Backend Routes (`/backend`) — all require `auth` + `IsCanAccessBackend` middleware

| Resource | URL | Controller |
|----------|-----|-----------|
| Dashboard | `/backend` | `BackendController@index` |
| Users | `/backend/users` | `Backend\UserController` |
| Roles | `/backend/roles` | `Backend\RoleController` |
| Works | `/backend/works` | `Backend\WorkController` |
| Recruits | `/backend/recruits` | `Backend\RecruitController` |
| Banners | `/backend/banners` | `Backend\BannerController` |
| Notifications | `/backend/notifications` | `Backend\NotificationController` |
| Configurations | `/backend/configurations` | `Backend\ConfigurationController` |
| Messenger Channels | `/backend/messenger-channels` | `Backend\MessengerChannelController` |
| Issue Points | `/backend/issue-points` | `Backend\IssuePointController` |
| Categories | `/backend/categories` | `Backend\CategoryController` |
| Reports | `/backend/reports/point-logs` `/backend/reports/issue-points` `/backend/reports/user-activity-logs` `/backend/reports/cron-logs` etc. | `Backend\ReportController` |

---

## Key Patterns & Conventions

### API Response Format
```json
{ "status": true, "data": { ... } }
{ "status": false, "message": "error description" }
```

### Pagination / Filtering
- `limitOffset(request()->all())` — custom Scopes trait method
- Query params: `limit`, `offset`, `keyword`, `province_id`, `date`, `date_start`, `date_end`

### Permission Check in Controller
```php
$user = auth('sanctum')->user();
if (!$user->isPermission(Permission::CREATE_WORK->value)) {
    return response()->json(['status' => false, 'message' => 'no permission'], 403);
}
```

### Soft Deletes
All main models (`User`, `Work`, `Recruit`, `IssuePoint`) use `SoftDeletes`

### Notification (in-app)
```php
$notification = new UserNotification();
$notification->title = "...";
$notification->details = ['count' => 1];
$notification->notification_type = NotificationType::WORK_LIKE->value;
$notification->notificationable()->associate($work);
$work->author->notifications()->save($notification);
```

### Seeder Order
`GeographySeeder → ProvinceSeeder → DistrictSeeder → SubDistrictSeeder → RoleSeeder → PermissionSeeder → UserSeeder → WorkTypeSeeder → CategorySeeder → ConfigurationSeeder → NotificationSeeder → MockSeeder`

---

## Implementation Progress

### ✅ Fully Implemented
- Auth (login, register, logout, token revoke, mobile-phone quicklogin)
- User profile CRUD (avatar, cover, bio, location, theme)
- RBAC — Roles, Permissions (user-level + role-level)
- Work listings (CRUD, filter, top-hits, like toggle, review-rating field)
- Work Bookings (create, worker-confirm, customer-confirm, confirmed-booking filter by date range)
- Recruit listings (CRUD, filter)
- Recruit Bookings (create, worker-confirm, customer-confirm)
- Messenger — auth channels + guest mobile-phone channels + seen_at tracking
- In-app Notifications with polymorphic relations + last-update polling
- Banners (pinned, last-update)
- Issue Points — admin CRUD, one-time & repeating (weekday/date), approve workflow
- User Points — balance (received, available, redeemed)
- Point transaction logs + user point logs
- File upload (image, avatar, document, original-image)
- Barcode & QR generation + preview
- Thai geographic data (77 provinces, districts, sub-districts)
- Categories (for Works & Recruits)
- Admin Backend panel (Users, Roles, Works, Recruits, Banners, Config, Messenger, IssuePoints)
- Reports (point logs, issue logs, status logs, activity logs, cron logs, transaction logs)
- CronJob framework (Kernel scheduling + CronLog model)
- User Activity Logs
- App-wide Configuration key-value store
- API documentation via Scramble (`/docs/api`)
- Docker/Sail dev environment

### 🔄 Partial / Migrations Exist but Not Wired Up
- **Posts** — `posts` and `post_likes` tables exist (migration: 2024-10-25) but no Model, Controller, or API routes
- **Works Reviews** — `works_reviews` and `recruits_reviews` tables exist but no dedicated API endpoints
- **Reviews & Replies** — Models exist (`Review`, `Reply`, `ReviewLike`, `ReplyLike`) but no API routes
- **Notification Broadcasting** — UserNotification model is in-app only; no push/FCM delivery implemented

### ❌ Not Started
- Payment gateway (PromptPay / Omise / Stripe)
- `avg_review_rating` auto-calculation (field exists, no aggregation job)
- Full-text search (no Scout / Elasticsearch)
- Real-time WebSockets (BROADCAST_CONNECTION=log; no Pusher/Reverb)
- Email templates (MAIL_MAILER=log in dev)
- SMS / OTP verification for mobile phone users
- Admin analytics dashboard (charts/stats)
- Feature & Unit Tests (PHPUnit configured but minimal coverage)
- CI/CD pipeline (no GitHub Actions)

---

## Development Commands

```bash
# Start Docker
sail up -d
sail stop

# Database
sail php artisan migrate
sail php artisan db:seed
sail php artisan migrate:fresh --seed
sail php artisan db:seed --class=MockSeeder

# Assets
sail yarn
sail yarn dev

# Storage
sail artisan storage:link

# Cron
sail php artisan schedule:work    # local only
sail php artisan schedule:run     # force run once
sail php artisan schedule:list    # show all scheduled tasks

# Generate CRUD boilerplate
sail php artisan make:migration create_xxx_table
sail php artisan make:crud xxx

# Tinker
sail php artisan tinker
```

---

## Environment Config (key values)

```dotenv
APP_NAME="Chaothuk - เช่าถูก"
APP_LOCALE=th
APP_FALLBACK_LOCALE=en
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=chaothuk
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
FILESYSTEM_DISK=local
BROADCAST_CONNECTION=log   # ← not real-time yet
MAIL_MAILER=log             # ← no real emails yet
```

---

## Notes for AI Assistant

- **ภาษาไทย** ใช้ใน string notifications (เช่น `"มีคนชื่นชอบงานของคุณ"`) และ lang files → ควรรักษา pattern นี้ไว้
- **Route prefix pattern**: API ใช้ `Route::group(['prefix' => 'x', 'as' => 'api.x.', 'middleware' => [...]])` เสมอ
- **Permission gate** ทำ in-controller ไม่ใช้ middleware/policy — pattern นี้ใช้ทั้ง project
- **Sanctum** ใช้ `auth('sanctum')->user()` ใน API controllers
- **SoftDeletes** ใช้กับทุก main model — อย่าลืม `withTrashed()` หรือ `onlyTrashed()` เมื่อต้องการ
- **Scopes Trait** — `limitOffset()` มาจาก `App\Traits\Scopes` ไม่ใช่ built-in Laravel
- **API Docs** อยู่ที่ `http://localhost/docs/api` (Scramble auto-gen)
- **CRUD Generator**: `sail php artisan make:crud {table}` สำหรับ boilerplate ใหม่
