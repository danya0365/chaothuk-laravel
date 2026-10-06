# AI Workspace Context — Chaothuk Laravel

> **⚠️ อัปเดต 2026-10-06 — ไฟล์นี้ถูกเขียนใหม่ทั้งหมด**
>
> เวอร์ชันก่อน (2026-03-02) ล้าสลัยไป 7 เดือนและคลาดเคลื่อนสูงมาก
> (บอกว่า models 35 ตัว — จริงๆ มี 54 · บอกว่า payment "ยังไม่เริ่ม" — จริงๆ มีครบแล้ว)
> → **สำเนาเดิมเก็บไว้ที่ `.agent/ai_workspace_context.BACKUP-2026-03-02.md`**
>
> 📌 **รายละเอียดเชิงลึกอยู่ใน 3 ไฟล์ที่ root แล้ว ให้อ่านตามความจำเป็น**
>
> | ไฟล์                                        | ใช้เมื่อ                                             |
> | ------------------------------------------- | ---------------------------------------------------- |
> | **[docs/README.md](../docs/README.md)** | ดัชนีเอกสารทั้งหมด — เริ่มที่นี่ |
> | **[SRS.md](../docs/SRS.md)**                     | ต้องการภาพรวมระบบ · ฟีเจอร์ · สถาปัตยกรรม · ข้อจำกัด |
> | **[API-REFERENCE.md](../docs/API-REFERENCE.md)** | ต้องการรายละเอียดทุก endpoint ของ `/api/*`           |
> | **[DATA-MODEL.md](../docs/DATA-MODEL.md)**       | ต้องการ columns ของทุกตาราง (63 ตาราง)               |

---

## Project Identity

| Key             | Value                                            |
| --------------- | ------------------------------------------------ |
| **App Name**    | Chaothuk — เช่าถูก                               |
| **Type**        | ตลาดกลางหาฟรีแลนซ์ + ผู้ว่าจ้าง (Web + REST API) |
| **Framework**   | Laravel 11                                       |
| **Language**    | PHP 8.2+ (local 8.4)                             |
| **Database**    | MySQL 8 (Laravel Sail / Docker)                  |
| **Auth**        | Laravel Sanctum (API token)                      |
| **Locale**      | Thai (`th`) / English fallback (`en`)            |
| **Dev Env**     | Laravel Sail (Docker Compose) · `sail` alias     |
| **URL (local)** | http://localhost                                 |

---

## ตัวเลขจริง (verified 2026-10-06)

| สิ่ง                | จำนวน                                                            |
| ------------------- | ---------------------------------------------------------------- |
| Routes ทั้งหมด      | **228** (api 103 · backend 57 · frontend 39 · ajax 10 · อื่น 19) |
| Migrations          | **70** ไฟล์ → 67 `create` → **63 ตารางจริง**                     |
| Models              | **54**                                                           |
| Enums               | **26**                                                           |
| Livewire components | **98** (Backend 53 · Frontend 44 · Landing 1)                    |
| Blade views         | **177**                                                          |
| API controllers     | **24**                                                           |
| Form Requests       | **19**                                                           |
| API Resources       | **38**                                                           |
| Seeders             | **16**                                                           |
| Factories           | **7**                                                            |
| บรรทัด PHP ทั้งหมด  | ~34,400                                                          |

---

## Core Concept

**Chaothuk** เชื่อม **ผู้ให้บริการ (Workers)** กับ **ผู้ว่าจ้าง (Employers)** ในไทย

- Workers ลงประกาศ **Works** — "ฉันทำงานนี้ได้" (รถรับจ้าง, ช่าง, งานบ้าน)
- Employers ลงประกาศ **Recruits** — "ฉันต้องการคนมาทำ"
- ทั้งสองฝ่าย **จอง → ต่อรอง → ยืนยัน → เริ่มงาน → GPS tracking → รีวิว**
- **Issue Points** rewards + **Reputation** คะแนน 4 มิติ + **Wallet/Payment** (ยังไม่ต่อ gateway)
- **Messenger** ทั้งแบบ login และ guest (เบอร์มือถือ)

---

## Tech Stack

```
PHP 8.2+ · Laravel 11 · MySQL 8
Laravel Sanctum 4      (API tokens)
Livewire 4.2          ⚠️ ไม่ใช่ 3 + Alpine.js 3
Tailwind CSS 3 + Flowbite 2   ⚠️ ไม่ใช่ 4 (darkMode: class)
Laravel Sail          (Docker dev)
dedoc/scramble        (auto OpenAPI → /docs/api)
intervention/image-laravel 3
milon/barcode 11      (QR + Code128)
spatie/laravel-options
tightenco/ziggy 2
maplibre-gl 4         (แผนที่)
apexcharts 5          (กราฟ)
cropperjs 1.6         (crop รูปก่อนอัปโหลด)
sweetalert2 11        (alert)
Noto Sans Thai        (ฟอนต์หลัก)
```

---

## Directory Structure

```
chaothuk-laravel/
├── docs/                        ← เอกสารประกอบทั้งหมด
│   ├── README.md               ← ดัชนี (หน้าประตู)
│   ├── SRS.md · API-REFERENCE.md · DATA-MODEL.md
│   └── DEV-DOC.md · DEPLY_TO_VPS.md · TODO.md
├── .agent/                ไฟล์นี้ + backup + task.md
├── app/
│   ├── Livewire/
│   │   ├── Backend/       53 component (ผู้ดูแลระบบ)
│   │   ├── Frontend/      44 component (ผู้ใช้ — UI หลัก)
│   │   └── Landing.php
│   ├── Http/
│   │   ├── Controllers/Api/   24 controller
│   │   ├── Controllers/       ⚠️ ไม่มี Backend/ และ Auth/ แล้ว
│   │   ├── Middleware/        IsCanAccessBackend
│   │   ├── Requests/          19
│   │   └── Resources/         38
│   ├── Models/            54
│   ├── Enums/             26
│   ├── Services/          (เช่น ReputationService, MockImageService)
│   ├── Events/ Listeners/
│   └── Traits/Scopes.php
├── database/
│   ├── migrations/        70
│   ├── seeders/           16
│   └── factories/         7
├── resources/
│   ├── views/
│   │   ├── livewire/{backend,frontend}/   ← view ของ Livewire
│   │   ├── layouts/                        7 (app · backend · frontend)
│   │   ├── components/                     ⚠️ มี ⚡-prefixed ที่เป็น dead code
│   │   └── frontend/layout.blade.php      ← dark premium shell
│   └── js/ · css/
├── routes/
│   ├── api.php       103 routes
│   ├── frontend.php   39 routes (Livewire)
│   ├── web.php        57 backend + landing (Livewire)
│   ├── ajax.php       10 routes
│   └── console.php    cron schedule
├── lang/th/ lang/en/
└── tests/             9 test files (coverage ต่ำ)
```

---

## Key Patterns & Conventions

### API Response Format

```json
{ "status": true, "data": { ... } }
{ "status": false, "message": "no permission" }
```

⚠️ **ข้อยกเว้น:** `/api/sessions/*` คืน `{ data, message }` (ไม่มี `status`)

### Permission Check — ทำใน controller ไม่ใช้ Policy

```php
if (!$user->isPermission(Permission::CREATE_WORK->value)) {
    return response()->json(['status' => false, 'message' => 'no permission'], 403);
}
```

⚠️ **มีแค่ 2 จุด** ในทั้ง API — `createWork` และ `createRecruit` (endpoint อื่นไม่ตรวจ)

### Middleware

- `auth:sanctum` — API
- `auth` — session
- `IsCanAccessBackend` — alias เดียวที่ custom → ต้องเป็น User + มีสิทธิ์ `access_backend`

### Sanctum

`auth('sanctum')->user()` ในทุก API controller

### Pagination

`limitOffset(request()->all())` จาก `App\Traits\Scopes` — **ไม่ใช้ `paginate()`**

### SoftDeletes

Main model ใช้ `SoftDeletes` — ต้อง `withTrashed()` เมื่อต้องการ
⚠️ **ไม่มี** ใน: `permissions`, `user_reputations`, `user_badges`, `user_verifications`,
`user_reputation_logs`, `favorites`, `work_availabilities`, `work_blocked_dates`,
`session_location_logs`, `province_top_works`, `wallet_transactions`

### Route Group Pattern

```php
Route::group(['prefix' => 'x', 'as' => 'api.x.', 'middleware' => ['auth:sanctum']], function () {
    // ...
});
```

### ภาษาไทย

User-facing string เป็น **ภาษาไทย** (notifications, validation messages)

### Backend = Livewire ไม่ใช่ Controller

ทุกหน้า `/backend/*` เป็น **full-page Livewire component** ที่ `#[Layout('layouts.backend')]`
ไม่มี `app/Http/Controllers/Backend/` — เอกสารเดิมบอกผิด

---

## ระบบที่มี (ครบแล้ว — เอกสารเดิมบอกว่าไม่มี)

| ระบบ                   | ตาราง                                                                                                                                             |
| ---------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Reputation**         | `user_reputations` (user_id เป็น PK) · `user_reputation_reviews` · `user_reputation_logs` · `user_badges` · `user_verifications` · `user_reports` |
| **Wallet/Payment**     | `user_wallets` · `wallet_transactions` · `payments`                                                                                               |
| **Dispute**            | `disputes` (morph bookingable)                                                                                                                    |
| **Portfolio**          | `portfolios`                                                                                                                                      |
| **Favorite**           | `favorites` (morph)                                                                                                                               |
| **Work Session + GPS** | `work_sessions` (morph) · `session_location_logs`                                                                                                 |
| **Availability**       | `work_availabilities` · `work_blocked_dates`                                                                                                      |
| **Promotion**          | `featured_works` · `province_top_works`                                                                                                           |
| **Moderation**         | `is_suspended` บน works/recruits/posts · `user_reports` (polymorphic)                                                                             |

> 📖 รายละเอียดทุกตาราง → [DATA-MODEL.md](../docs/DATA-MODEL.md)

---

## สิ่งที่ยังไม่มี / ทำไม่ครบ

| ฟีเจอร์                     | สถานะ                                                        |
| --------------------------- | ------------------------------------------------------------ |
| Payment gateway integration | ❌ มีตารางแต่ไม่ต่อ Omise/Stripe/PromptPay                   |
| Withdraw flow               | ❌ มี type `withdrawal` แต่ไม่มี logic                       |
| Email verification          | ❌                                                           |
| Password reset (ขั้นตอนจบ)  | ❌ `ForgotPassword` ส่งลิงก์แล้วจบ ไม่มี reset-confirm route |
| Email จริง                  | ❌ `MAIL_MAILER=log`                                         |
| Real-time (WebSocket)       | ❌ `BROADCAST_CONNECTION=log`                                |
| Push notification           | ❌ in-app เท่านั้น                                           |
| Coupon / Promotion code     | ❌                                                           |
| Service packages            | ❌                                                           |
| Recruit counters            | ❌ ไม่มี like_count/reply_count/avg_review_rating (Works มี) |
| Search endpoint             | ❌ มี `keyword` param แต่ไม่มี `/search` route               |
| Update work/recruit API     | ❌ มี method แต่ไม่ได้ route                                 |

---

## ⚠️ บั๊จที่ทำให้ระบบพัง

| ปัญหา                                                       | ผล                                            |
| ----------------------------------------------------------- | --------------------------------------------- |
| `GET /api/auth/customer-register-login` → method ไม่มี      | **500**                                       |
| `GET /api/link-storage`                                     | unauth + รัน `Artisan::call('storage:link')`  |
| `/api/upload/*` ไม่มี auth                                  | อัปโหลดไฟล์ได้โดยไม่ login                    |
| `/ajax/upload/*` ไม่มี auth                                 | เหมือนกัน                                     |
| `MessengerController::getChannelConversations($id)`         | อ่านแชทคนอื่นได้ (ไม่ตรวจ participant)        |
| `SessionController` ทุก method                              | ใคร auth ก็เข้าถึง session คนอื่นได้          |
| `NotificationController::list`                              | อ่าน notification ทั้งระบบ ไม่ scope ต่อ user |
| `routes/web.php` — `settings.index` ซ้ำ 2 ครั้ง             | route name ชน                                 |
| `IssuePoint::customer()` / `userMission()` → class ไม่มี    | **fatal**                                     |
| `UserSeeder` import `UserBackend` → class ไม่มี             | seed พัง                                      |
| `Cron` model → ไม่มีตาราง `crons`                           | query error                                   |
| `SubDistrict` fillable `amphure_id` ≠ column `district_id`  | mass-assign ไม่ได้                            |
| `layouts/app.blade.php` include `layouts.navigation`        | ไฟล์ไม่มี                                     |
| `me-explorer.blade.php` เรียก `route('frontend.dashboard')` | route ไม่มี                                   |

**รายละเอียดทั้งหมด** → [SRS.md §9](../docs/SRS.md#9-ข้อจำกัดและบั๊จที่ต้องรู้)

---

## โค้ดตายที่ยังอยู่

| ประเภท      | รายการ                                                                                                                                   |
| ----------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| Controllers | `MessengerController` · `MessengerConversationController` · `MessengerParticipantController` · `UserActivityLogController` (ไม่มี route) |
| Livewire    | `Frontend\{Dashboard, MeExplorer, WorkExplorer, RecruitExplorer}`                                                                        |
| Blade       | `components/backend/⚡*` (~49) · `components/frontend/⚡*` (3)                                                                           |
| Requests    | `WorkRequest` (ซ้ำ `Api/WorkRequest`) · `ProfileUpdateRequest` · `UserPointRequest` (ว่าง)                                               |
| Models      | `Cron` (ไม่มีตาราง) · `Permission` (มี method ชี้ column ที่ไม่มี)                                                                       |
| Resources   | `IssuePointResource` · `RoleResource` · `UserPointLogResource` (leak raw model)                                                          |

---

## Development Commands

```bash
# Docker
sail up -d / sail stop

# Database
sail php artisan migrate
sail php artisan migrate:fresh --seed      # DatabaseSeeder เรียก 10 ตัว
sail php artisan db:seed --class=MockSeeder # dev เท่านั้น (เรียก DemoSeeder อัตโนมัติ)
sail php artisan db:seed --class=DemoSeeder
sail php artisan db:seed --class=ReputationSeeder   # ไม่ถูก DatabaseSeeder เรียก
sail php artisan db:seed --class=NotificationSeeder  # ไม่ถูก DatabaseSeeder เรียก

# Assets
sail yarn / sail yarn dev
sail artisan storage:link

# Cron
sail php artisan schedule:run
sail php artisan schedule:list

# Gate (ก่อนบอกว่าเสร็จ)
./vendor/bin/pint --test
./vendor/bin/phpunit

# เอกสาร
php artisan route:list --json
```

### ⚠️ Seeder ที่ DatabaseSeeder **ไม่เรียก**

`StarterSeeder` · `NotificationSeeder` · `ReputationSeeder` · `MockSeeder` · `DemoSeeder`

### ⚠️ `ProductionSeeder` ไม่มีจริง

`DEV-DOC.md` อ้างถึง แต่ไม่มีไฟล์

---

## บัญชีทดสอบ (จาก DemoSeeder / MockSeeder)

| ประเภท   | บัญชี                                                                                                     |
| -------- | --------------------------------------------------------------------------------------------------------- |
| Demo     | `worker1@chaothuk.test` · `worker2@chaothuk.test` · `employer1@chaothuk.test` · `employer2@chaothuk.test` |
| Mock     | `user1..user50@mock.test` (1-30 = worker, 31-50 = employer)                                               |
| Password | `password` (ทั้งหมด)                                                                                      |

---

## Notes for AI Assistant

- **ภาษาไทย** ใช้ใน string ที่ user เห็น (notifications, validation) — คง pattern นี้ไว้
- **Permission gate ทำใน controller** ไม่ใช้ Policy — pattern ทั้งโปรเจค
- **Sanctum** ใช้ `auth('sanctum')->user()`
- **SoftDeletes** ใช้กับ main model — อย่าลืม `withTrashed()`
- **Scopes Trait** — `limitOffset()` จาก `App\Traits\Scopes` ไม่ใช่ของ Laravel
- **Route group** ใช้ prefix + `as` + middleware เสมอ
- **Livewire 4.2** — ตรวจว่า syntax ที่ใช้เข้ากับเวอร์ชันนี้
- **ห้าม commit** ถ้าพี่ไม่สั่ง · ทำงานบน `develop` เท่านั้น (ดู `.claude/memory/decisions/0002-develop-only-workflow.md`)
- **API docs** ที่ `/docs/api` (Scramble auto-gen)
- **CRUD generator**: `sail php artisan make:crud {table}`
- เอกสารหลักอยู่ที่ `docs/` — เริ่มที่ `docs/README.md` แล้วเลือกเปิดตามความจำเป็น
  (`SRS.md` 594 · `API-REFERENCE.md` 649 · `DATA-MODEL.md` 901 บรรทัด — อย่าจำทุกอย่างพร้อมกัน)

---

_อัปเดต 2026-10-06 โดย ซาน่า 🌈 · สำเนาเดิม: `.agent/ai_workspace_context.BACKUP-2026-03-02.md`_
