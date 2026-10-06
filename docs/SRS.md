# SRS — Chaothuk (เช่าถูก)

### Software Requirements Specification · ฉบับยึดตาม Codebase จริง

|                      |                                                                    |
| -------------------- | ------------------------------------------------------------------ |
| **ระบบ**             | Chaothuk — ตลาดกลางหาฟรีแลนซ์ + ผู้ว่าจ้างในไทย                    |
| **เอกสารนี้**        | สัญญาระหว่าง backend (Laravel) กับทีมพัฒนา Mobile App              |
| **วันที่จัดทำ**      | 2026-10-06                                                         |
| **ฐานข้อมูลอ้างอิง** | `develop` @ commit ล่าสุด                                          |
| **ตรวจสอบจาก**       | โค้ดจริง + `php artisan route:list` (228 routes) + migrations จริง |

> ⚠️ **สำคัญสำหรับทีม Mobile:** เอกสารนี้เขียนจาก **โค้ดที่มีอยู่จริง** ไม่ใช่จากเอกสารเดิม
> ซึ่งล้าสลัยไป 7 เดือน (นับจาก 2026-03-02) และคลาดเคลื่อนสูง — ดู [ความต่างจากเอกสารเดิม](#ภาคผนวก-ค-ความต่างจากเอกสารเดิม)

---

## สารบัญ

1. [ภาพรวมระบบ](#1-ภาพรวมระบบ)
2. [ผู้ใช้งานและบทบาท](#2-ผู้ใช้งานและบทบาท)
3. [ฟีเจอร์ที่มีอยู่จริง](#3-ฟีเจอร์ที่มีอยู่จริง)
4. [โครงสร้างข้อมูล](#4-โครงสร้างข้อมูล)
5. [API สำหรับ Mobile](#5-api-สำหรับ-mobile)
6. [หน้าจอ Web](#6-หน้าจอ-web)
7. [กฎทางธุรกิจ](#7-กฎทางธุรกิจ)
8. [สิ่งที่ยังไม่มี / ต้องรู้ก่อนใช้](#8-สิ่งที่ยังไม่มี--ต้องรู้ก่อนใช้)
9. [ข้อจำกัดและบั๊จที่ต้องรู้](#9-ข้อจำกัดและบั๊จที่ต้องรู้)

**ภาคผนวก** → [DATA-MODEL.md](DATA-MODEL.md) (63 ตารางแบบละเอียด) ·
[API-REFERENCE.md](API-REFERENCE.md) (ทุก endpoint)

---

## 1. ภาพรวมระบบ

### 1.1 ระบบนี้ทำอะไร

เชื่อม **ผู้ให้บริการ (Workers)** กับ **ผู้ว่าจ้าง (Employers)** ในไทย ผ่านการประกาศ + จอง + ยืนยัน

- **ฝั่งผู้ให้บริการ** ลงประกาศ **Works** — "ฉันทำงานนี้ได้" (รถรับจ้าง, ช่าง, งานบ้าน)
- **ฝั่งผู้ว่าจ้าง** ลงประกาศ **Recruits** — "ฉันต้องการคนมาทำ"
- ทั้งสองฝ่าย **จอง → ต่อรอง → ยืนยัน** → เริ่มงาน → ติดตาม GPS → จ่ายเงิน → รีวิว

### 1.2 สถาปัตยกรรม

```
┌─────────────┐   ┌─────────────┐   ┌─────────────┐
│  Frontend   │   │ REST API    │   │   Admin     │
│  Livewire   │   │  Sanctum    │   │  Livewire   │
│  (39 route) │   │ (103 route) │   │ (57 route)  │
└──────┬──────┘   └──────┬──────┘   └──────┬──────┘
       │                 │                 │
       └────────►  Laravel 11 / PHP 8.2  ◄┘
                    │
              ┌─────┴─────┐
              │  MySQL 8  │
              └───────────┘
```

**3 หน้าจอที่ต้องรู้:**

| หน้าจอ                     | เทคโนโลยี                         | ใครใช้                              |
| -------------------------- | --------------------------------- | ----------------------------------- |
| **Frontend** `/frontend/*` | Livewire full-page (44 component) | ผู้ใช้ทั่วไป — UI หลักของระบบ       |
| **REST API** `/api/*`      | Laravel Sanctum (24 controller)   | **Mobile app** ← ขอบเขตของเอกสารนี้ |
| **Admin** `/backend/*`     | Livewire full-page (53 component) | ผู้ดูแลระบบ                         |

> ⚠️ **ข้อสังเกตสำคัญ:** API เป็น "ร่องรอยเก่า" — หลายฟีเจอร์ที่มีในเว็บ (wallet, disputes, portfolios, sessions)
> **ยังไม่มีใน API** ดู [ภาคผนวก ก](#ภาคผนวก-ก-ฟีเจอร์ที่-mobiledต้องขอเพิ่ม)

### 1.3 Stack

| ชั้น       | เทคโนโลยี                      | เวอร์ชัน                        |
| ---------- | ------------------------------ | ------------------------------- |
| Backend    | Laravel                        | 11.x                            |
| PHP        |                                | 8.2+ (local 8.4)                |
| DB         | MySQL                          | 8.0                             |
| Auth (API) | Laravel Sanctum                | 4.x                             |
| Frontend   | Livewire + Alpine.js           | **4.2**                         |
| CSS        | Tailwind CSS + Flowbite        | **3.x** (dark mode class-based) |
| Map        | MapLibre GL                    | 4.x                             |
| Charts     | ApexCharts                     | 5.x                             |
| Image      | cropperjs + Intervention Image |                                 |
| Docs       | dedoc/scramble → `/docs/api`   |                                 |

> ⚠️ เอกสารเดิมบอก Livewire 3 + Tailwind 4 — **จริงคือ 4.2 + Tailwind 3**

---

## 2. ผู้ใช้งานและบทบาท

### 2.1 Role (จาก `app/Enums/Role.php`)

| Role           | ID  | ความหมาย                               | สิทธิ์ที่ได้                |
| -------------- | --- | -------------------------------------- | --------------------------- |
| `SUPERVISOR`   | 1   | ผู้ดูแลสูงสุด                          | 7 สิทธิ์ (ทั้งหมด)          |
| `BACKEND`      | 2   | ผู้ดูแลหลังบ้าน                        | 5 สิทธิ์                    |
| `MEMBER`       | 3   | สมาชิกทั่วไป                           | 4 สิทธิ์                    |
| `MOBILE_PHONE` | 4   | ผู้ใช้จากเบอร์มือถือ (Messenger guest) | **0 สิทธิ์** — ใช้ได้แค่แชท |

### 2.2 Permission (7 สิทธิ์จาก `app/Enums/Permission.php`)

| Permission slug     | ใช้ทำอะไร                                      |
| ------------------- | ---------------------------------------------- |
| `access_backend`    | เข้าหลังบ้าน (middleware `IsCanAccessBackend`) |
| `manage_permission` | จัดการสิทธิ์ (หน้า `/backend/permissions`)     |
| `manage_role`       | จัดการบทบาท (หน้า `/backend/roles`)            |
| `create_work`       | ลงประกาศ Works                                 |
| `create_recruit`    | ลงประกาศ Recruits                              |
| `review_work`       | ตอบรีวิว (admin reply)                         |
| `reply_review`      | ตอบกลับรีวิว                                   |

**กลไกการผสมสิทธิ์:** สิทธิ์มาจาก 2 ทาง — ผ่าน **Role** (role-level) และผ่าน **การ assign ให้ user โดยตรง** (user-level)
ทั้งสองทางใช้ pivot table ที่มี column `data` (bool) เพื่อเปิด/ปิดสิทธิ์เฉพาะจุด

### 2.3 Middleware

| Middleware           | ทำอะไร                                                                                        |
| -------------------- | --------------------------------------------------------------------------------------------- |
| `auth:sanctum`       | ต้องมี Bearer token                                                                           |
| `auth`               | ต้อง login (session)                                                                          |
| `IsCanAccessBackend` | ต้องเป็น `User` + มีสิทธิ์ `access_backend` ไม่งั้น → redirect ไป `/backend/auth/unauthorize` |

---

## 3. ฟีเจอร์ที่มีอยู่จริง

### 3.1 ระบบประกาศ (Core Marketplace)

| ฟีเจอร์          | Works                                    | Recruits                            |
| ---------------- | ---------------------------------------- | ----------------------------------- |
| ข้อมูลหลัก       | code, title, description, price, lat/lng | title, description, budget, lat/lng |
| รูปภาพ           | primary_image + images (JSON)            | primary_image + images (JSON)       |
| หมวดหมู่         | ✅ (many-to-many)                        | ✅ (many-to-many)                   |
| จังหวัด          | ✅                                       | ✅                                  |
| ประเภทงาน        | ✅                                       | ✅                                  |
| สถานะ            | `stand-by` / `busy` / `close`            | `stand-by` / `busy` / `close`       |
| นับไลค์          | ✅ `like_count`                          | ❌ **ไม่มี**                        |
| นับความคิดเห็น   | ✅ `reply_count`                         | ❌ **ไม่มี**                        |
| คะแนนเฉลี่ย      | ✅ `avg_review_rating`                   | ❌ **ไม่มี**                        |
| ถูกระงับ         | ✅ `is_suspended`                        | ✅ `is_suspended`                   |
| display_priority | ✅                                       | ✅                                  |

> ⚠️ **ความไม่สมมาตร** — Works มี counter 3 ตัว แต่ Recruits ไม่มีเลย
> ทีม Mobile ต้องคิดว่าจะทำอย่างไร (ดู [ภาคผนวก ก](#ภาคผนวก-ก-ฟีเจอร์ที่-mobiledต้องขอเพิ่ม))

### 3.2 การจอง (Booking)

**Flow:** จอง → ผู้ให้บริการยืนยัน → ลูกค้ายืนยัน → เริ่มงาน

| สถานะ                     | ค่า                                           | ความหมาย              |
| ------------------------- | --------------------------------------------- | --------------------- |
| `booking_status`          | `waiting-to-confirm`                          | รอยืนยัน              |
|                           | `confirm`                                     | ทั้งสองฝ่ายยืนยันแล้ว |
|                           | `close`                                       | ปิดงาน                |
|                           | `cancel`                                      | ยกเลิก                |
| `worker_confirm_status`   | `waiting-to-confirm` / `confirm` / `rejected` | ฝั่งผู้ให้บริการ      |
| `customer_confirm_status` | เหมือนกัน                                     | ฝั่งลูกค้า            |

**Booking เก็บ:** customer_message, mobile_phone, booking_date, latitude/longitude

**สิทธิ์เข้าถึง:** ผู้อ่านต้องเป็นเจ้าของ works/recruits — ถ้าไม่ใช่จะได้ 403 `'no permission'`

### 3.3 ระบบรีวิว (Post-based)

รีวิวทั้งหมดใช้ตารางเดียว `posts` กับ pivot `works_reviews` / `recruits_reviews`

```
Post (รีวิว)  ──< works_reviews >──  Work
     │
     └── parent_id ──> Post (ตอบกลับ, ซ้อนกันได้)
```

- `posts.rating` = 0–5 (รีวิวหลัก) หรือ 0 (ตอบกลับ)
- `post_likes` = กดไลค์รีวิว
- รีวิวของ Works ดึงจาก `GET /api/works/{workId}/reviews`

### 3.4 Work Session + GPS Tracking

เมื่อ booking คืนชัด → สร้าง `work_sessions` เพื่อจับเวลา + ตำแหน่ง

| ข้อมูล                                | รายละเอียด                                                            |
| ------------------------------------- | --------------------------------------------------------------------- |
| `sessionable`                         | morph → `works` หรือ `recruits`                                       |
| `bookingable`                         | morph → `work_bookings` หรือ `recruit_bookings` (nullable)            |
| `price_agreed`                        | ราคาที่ตกลงกัน (decimal 10,2)                                         |
| `total_duration_minutes`              | นาทีที่ใช้จริง                                                        |
| `status`                              | `active` / `paused` / `completed` / `cancelled`                       |
| `worker_confirm` / `customer_confirm` | `pending` / `confirmed` / `disputed`                                  |
| `session_location_logs`               | จุด GPS: lat/lng decimal(10,7), accuracy, speed, heading, recorded_at |

**ต่างจาก API คาด:** ตาราง location log ไม่มี FK ระหว่าง session กับ user (session_id มี cascade) — ดูรายละเอียดใน DATA-MODEL.md

### 3.5 ระบบเสียงชื่อเสียง (Reputation)

คะแนน 4 มิติ + trust level 6 ระดับ

| มิติ             | คอลัมน์                                |
| ---------------- | -------------------------------------- |
| คุณภาพงาน        | `quality_score`                        |
| ตรงเวลา          | `timeliness_score`                     |
| การสื่อสาร       | `communication_score`                  |
| ความเป็นมืออาชีพ | `professionalism_score`                |
| รวม              | `overall_score` (ทั้งหมด decimal(3,2)) |

**Trust Level:** `new` → `bronze` → `silver` → `gold` → `platinum` → `diamond`

**ที่เก็บข้อมูล:**

- `user_reputations` — สรุป 1 แถวต่อ user (ใช้ `user_id` เป็น primary key)
- `user_reputation_reviews` — รีวิวราย booking (booking_id ไม่มี FK)
- `user_reputation_logs` — ประวัติการเปลี่ยนคะแนน
- `user_badges` — เหรียญตรา 8 ประเภท × 3 ระดับ (bronze/silver/gold)
- `user_verifications` — ยืนยันตัวตน 7 ประเภท
- `user_reports` — รายงานผู้ใช้ 7 ประเภท (polymorphic reportable)

### 3.6 Wallet + Payment

| ตาราง                 | เก็บ                                                                     |
| --------------------- | ------------------------------------------------------------------------ |
| `user_wallets`        | balance (dec 12,2), status (`active`/`suspended`)                        |
| `wallet_transactions` | type, amount, balance_after, morph reference                             |
| `payments`            | amount, currency (default `THB`), payment_method, status, gateway fields |

**Transaction types:** `deposit` / `payment` / `refund` / `withdrawal` / `adjustment`
**Payment statuses:** `pending` / `successful` / `failed` / `refunded`

> ⚠️ **ยังไม่ต่อกับ payment gateway จริง** — ไม่มี integration ใดๆ (Omise/Stripe/PromptPay)
> ดู [ภาคผนวก ก](#ภาคผนวก-ก-ฟีเจอร์ที่-mobiledต้องขอเพิ่ม)

### 3.7 ฟีเจอร์อื่น

| ฟีเจอร์            | ตาราง/โมเดล                                              |
| ------------------ | -------------------------------------------------------- |
| Favorites          | `favorites` (morph → work/recruit)                       |
| Portfolios         | `portfolios` (ผลงานเก่า, ผูก work_type)                  |
| Featured Works     | `featured_works` (โปรโมตแบบจ่ายเงิน + approval)          |
| Province Top Works | `province_top_works` (อันดับงานรายจังหวัดรายเดือน)       |
| Work Availability  | `work_availabilities` + `work_blocked_dates`             |
| Issue Points       | `issue_points` + `user_points` + logs                    |
| Messenger          | channels + participants + conversations (รองรับ guest)   |
| Notifications      | `notifications` (ระบบ) + `user_notifications` (ราย user) |
| Activity Logs      | `user_activity_logs`                                     |
| Configurations     | `configurations` (key-value + group)                     |
| Banners            | `banners` (product/promotion/external_url)               |

---

## 4. โครงสร้างข้อมูล

**63 ตารางที่ใช้งานจริง** (67 ครั้งที่ `Schema::create` − 4 ตาราง legacy ที่ถูก drop)

รายละเอียดครบทุกตาราง → **[DATA-MODEL.md](DATA-MODEL.md)**

### 4.1 ภาพรวมตาม domain

| Domain        | ตาราง                                                                                                                               |
| ------------- | ----------------------------------------------------------------------------------------------------------------------------------- |
| **Auth/RBAC** | users, roles, permissions, users_roles, user_permissions, role_permissions, personal_access_tokens, sessions, password_reset_tokens |
| **Geography** | geographies, provinces, districts, sub_districts                                                                                    |
| **Works**     | works, work_types, work_likes, work_bookings, work_availabilities, work_blocked_dates, works_categories, works_reviews              |
| **Recruits**  | recruits, recruit_bookings, recruits_categories, recruits_reviews                                                                   |
| **Reviews**   | posts, post_likes                                                                                                                   |
| **Sessions**  | work_sessions, session_location_logs                                                                                                |
| **Trust**     | user_reputations, user_reputation_reviews, user_reputation_logs, user_badges, user_verifications, user_reports                      |
| **Money**     | user_wallets, wallet_transactions, payments                                                                                         |
| **Points**    | issue_points, user_points, user_point_logs, point_transaction_logs, issue_point_status_logs                                         |
| **Messenger** | messenger_channels, messenger_participants, messenger_conversations                                                                 |
| **Discovery** | favorites, portfolios, featured_works, province_top_works                                                                           |
| **System**    | notifications, user_notifications, banners, configurations, user_activity_logs, cron_logs                                           |
| **Infra**     | cache, cache_locks, jobs, job_batches, failed_jobs                                                                                  |

### 4.2 Legacy tables ที่ถูกลบ

`reviews`, `replies`, `review_likes`, `reply_likes` — ถูก drop ใน migration `2026_03_02_053000`
แทนที่ด้วยระบบ `posts` รวม (Post + parent_id)

---

## 5. API สำหรับ Mobile

**103 endpoint** ที่ `/api/*` · รายละเอียดทุกตัว → **[API-REFERENCE.md](API-REFERENCE.md)**

### 5.1 การยืนยันตัวตน

```http
POST /api/auth/register
POST /api/auth/login          → { status: true, data: { token, user } }
GET  /api/auth/user           (Bearer token)
POST /api/auth/logout
POST /api/auth/revoke-token
```

Token เป็น Sanctum personal access token → ส่งเป็น `Authorization: Bearer <token>`

### 5.2 Format การตอบกลับ

```json
// สำเร็จ
{ "status": true, "data": { ... } }

// ไม่สำเร็จ
{ "status": false, "message": "no permission" }
```

> ⚠️ **ข้อยกเว้น:** `SessionController` คืน `{ data, message }` เปล่า (ไม่มี `status`) และ 201 สำหรับ create
> ทีม Mobile ต้องรองรับทั้งสองรูปแบบ

### 5.3 Endpoint หลัก

| กลุ่ม            | Path                                                                                                                                    | Auth |
| ---------------- | --------------------------------------------------------------------------------------------------------------------------------------- | ---- |
| ข้อมูลอ้างอิง    | `GET /api/provinces`, `/districts/{id}`, `/sub-districts/{id}`, `/work-types`, `/categories`, `/configurations`                         | ❌   |
| Works            | `GET /api/works`, `/works/top-hits`, `/works/{id}`, `/works/{id}/reviews`                                                               | ❌   |
| Works (เขียน)    | `POST /api/works`, `/works/{id}/bookings`, `/works/{id}/likes`, `/works/{id}/reviews`                                                   | ✅   |
| Recruits         | `GET /api/recruits`, `/recruits/{id}`, `/recruits/{id}/reviews`                                                                         | ❌   |
| Recruits (เขียน) | `POST /api/recruits`, `/recruits/{id}/bookings`, `/recruits/{id}/reviews`                                                               | ✅   |
| Booking          | `GET /api/work-bookings/{id}`                                                                                                           | ❌   |
| Booking (ยืนยัน) | `POST /api/work-bookings/{id}/worker-confirm` · `/customer-confirm`                                                                     | ✅   |
| Posts            | `GET /api/posts`, `/posts/{id}`, `/posts/{id}/likes`                                                                                    | ❌   |
| Posts (เขียน)    | `POST /api/posts`, `/{id}` · `PUT` · `DELETE /posts/{id}` · like toggle                                                                 | ✅   |
| Sessions         | `GET /api/sessions` · `POST /api/sessions/start` · `/{id}` · `/{id}/locations` · `POST /{id}/location` · `/{id}/stop` · `/{id}/confirm` | ✅   |
| Messenger        | auth + guest mobile-phone channel                                                                                                       | ผสม  |
| Me               | `GET/POST /api/me` · `/me/password` · `/me/works` · `/me/bookings` · `/me/posts`                                                        | ✅   |
| Upload           | `POST /api/upload/{image,avatar,cover,document,original-image}`                                                                         | ❌   |

### 5.4 Parameter สำหรับ list

```
limit, offset, keyword, province_id, date, date_start, date_end
```

### 5.5 ข้อควรระวังสำหรับ Mobile

| ประเด็น                      | รายละเอียด                                            |
| ---------------------------- | ----------------------------------------------------- |
| **ไม่มี rate limit**         | `throttle` ถูก comment ออก — ต้องจัดการเองฝั่ง client |
| **ไม่มี versioning**         | ใช้ `/api/` ตรงๆ ไม่มี `/api/v1`                      |
| **Upload ไม่ต้อง auth**      | `/api/upload/*` เปิดสาธารณะ (⚠️ ช่องโหว่)             |
| **Session ไม่ตรวจสิทธิ์**    | ใครก็ตามที่ auth ก็ยิง session id ของคนอื่นได้        |
| **Resource ไม่ครบ**          | 18 Collection ไม่มี `$collects` → ไม่ shape field     |
| **Permission check น้อยมาก** | มีแค่ 2 จุดในทั้ง API (createWork, createRecruit)     |

---

## 6. หน้าจอ Web

### 6.1 Frontend `/frontend/*` (39 route)

| กลุ่ม           | หน้า                                                                                             | Auth  |
| --------------- | ------------------------------------------------------------------------------------------------ | ----- |
| Home            | `/`                                                                                              | ❌    |
| Browse          | `/works` · `/recruits` · `/categories` · `/search` · `/map`                                      | ❌    |
| Detail          | `/works/{id}` · `/recruits/{id}` · `/portfolios/{id}` · `/profile/{id}`                          | ❌    |
| Auth            | `/auth/login` · `/auth/register` · `/auth/forgot-password`                                       | guest |
| สร้าง           | `/works/create` · `/recruits/create`                                                             | ✅    |
| จัดการของตัวเอง | `/my-works` · `/my-recruits` · `/my-orders` · `/my-hires` · `/bookings`                          | ✅    |
|                 | `/my-wallet` · `/sessions` · `/sessions/{id}` · `/favorites`                                     | ✅    |
|                 | `/notifications` · `/calendar` · `/messenger` · `/profile/edit`                                  | ✅    |
| แก้ไข           | `/works/{id}/edit` · `/works/{id}/bookings` · `/works/{id}/availability` · `/works/{id}/promote` | ✅    |
|                 | `/recruits/{id}/edit` · `/recruits/{id}/bookings`                                                | ✅    |
| Portfolio       | `/portfolios` · `/portfolios/create` · `/portfolios/{id}/edit`                                   | ✅    |

### 6.2 Admin `/backend/*` (57 route)

ทุกหน้าต้อง login + มีสิทธิ์ `access_backend`

| กลุ่ม       | Path                                                                                                                        |
| ----------- | --------------------------------------------------------------------------------------------------------------------------- |
| Dashboard   | `/backend`                                                                                                                  |
| Users       | `/backend/users` (+ create, `{user}`, `{user}/edit`)                                                                        |
| IAM         | `/backend/roles` · `/backend/permissions`                                                                                   |
| Content     | `/backend/works` · `/backend/recruits` · `/backend/posts` · `/backend/featured-works`                                       |
| Trust       | `/backend/reputations` · `/backend/verifications` · `/backend/reports` · `/backend/disputes`                                |
| Money       | `/backend/wallets` (+ transactions) · `/backend/payments`                                                                   |
| Points      | `/backend/points/issues`                                                                                                    |
| Messenger   | `/backend/messenger`                                                                                                        |
| Sessions    | `/backend/sessions` · `/backend/logs/work-sessions`                                                                         |
| Logs        | `/backend/logs/activity` · `/backend/logs/crons`                                                                            |
| Master data | `/backend/categories` · `/backend/work-types` · `/backend/banners` · `/backend/portfolios` · `/backend/locations/provinces` |
| System      | `/backend/notifications` · `/backend/settings`                                                                              |
| Auth        | `/backend/auth/login` · `/backend/auth/unauthorize`                                                                         |

### 6.3 UI

- **Frontend เป็น dark-only** — hardcode `bg-gray-950 text-gray-100` (ไม่มี light mode)
- **สีเน้น** — Frontend: ส้ม · Admin: rose
- **ฟอนต์** Noto Sans Thai
- **ไอคอน** เป็น emoji ไม่ใช่ icon set
- **Nav** — rail ซ้าย 70px (desktop) + bottom bar (mobile)

---

## 7. กฎทางธุรกิจ

### 7.1 Permission gate

ตรวจใน controller (ไม่ใช้ Policy/Gate) — pattern ของโปรเจค:

```php
$user = auth('sanctum')->user();
if (!$user->isPermission(Permission::CREATE_WORK->value)) {
    return response()->json(['status' => false, 'message' => 'no permission'], 403);
}
```

**มีแค่ 2 จุด:** `createWork` และ `createRecruit` — endpoint อื่นไม่ตรวจ

### 7.2 Booking ownership

```php
WorkBooking::whereHas('work', fn ($q) => $q->whereBelongsTo($user, 'author'))
```

ถ้าไม่เจอ → 403 `'no permission'`

### 7.3 Pagination

ใช้ `limitOffset(request()->all())` จาก `App\Traits\Scopes` — **ไม่ใช้ `paginate()`**

### 7.4 SoftDeletes

Main model ใช้ `SoftDeletes` — ต้อง `withTrashed()` เมื่อต้องการ

### 7.5 ภาษา

User-facing string เป็น **ภาษาไทย** (notifications, validation messages)

---

## 8. สิ่งที่ยังไม่มี / ต้องรู้ก่อนใช้

### 8.1 ยังไม่มีเลย

| ฟีเจอร์                          | สถานะ                                                       |
| -------------------------------- | ----------------------------------------------------------- |
| Payment gateway integration      | ❌ มีตารางแต่ไม่ต่อ Omise/Stripe/PromptPay                  |
| Withdraw flow                    | ❌ มี transaction type `withdrawal` แต่ไม่มี logic          |
| Email verification               | ❌ ไม่มี route                                              |
| Password reset (จบ)              | ❌ `ForgotPassword` ส่งลิงก์แล้วจบ ไม่มี reset-confirm      |
| Email จริง                       | ❌ `MAIL_MAILER=log`                                        |
| Real-time (WebSocket)            | ❌ `BROADCAST_CONNECTION=log`                               |
| Push notification                | ❌ in-app เท่านั้น                                          |
| Coupon / Promotion code          | ❌                                                          |
| Service packages (ราคาหลายระดับ) | ❌                                                          |
| Dispute สำหรับทุกประเภท          | ⚠️ มีตาราง + admin UI แต่ยังไม่มี API ให้ user เปิด dispute |
| Search history                   | ❌                                                          |
| 2FA                              | ❌                                                          |

### 8.2 มีในโค้ดแต่ทำไม่ครบ

| ฟีเจอร์          | ปัญหา                                                     |
| ---------------- | --------------------------------------------------------- |
| Recruit counters | ไม่มี like_count/reply_count/avg_review_rating (Works มี) |
| Wallet           | มี `testDeposit()` เรียกได้จากหน้า production             |
| Payment          | มีตาราง แต่ไม่มี logic เชื่อม booking → payment           |
| Session API      | มีครบ แต่ไม่ตรวจว่าผู้เรียกเป็น worker/customer จริง      |

---

## 9. ข้อจำกัดและบั๊จที่ต้องรู้

### 9.1 บั๊จที่ทำให้ระบบพังได้

| ปัญหา                                                       | ผลกระทบ                                                                     |
| ----------------------------------------------------------- | --------------------------------------------------------------------------- |
| `GET /api/auth/customer-register-login` → method ไม่มี      | **500 error** ทุกครั้งที่เรียก                                              |
| `GET /api/link-storage`                                     | unauthenticated + รัน `Artisan::call('storage:link')` — เปิดให้ใครก็ได้สั่ง |
| `routes/web.php` ประกาศ `settings.index` ซ้ำ 2 ครั้ง        | route name ชนกัน                                                            |
| `IssuePoint::customer()` / `userMission()` → class ไม่มี    | **fatal error** ถ้าเรียก                                                    |
| `UserSeeder` import `UserBackend` → class ไม่มี             | seed จะพัง                                                                  |
| `Cron` model → ไม่มีตาราง `crons`                           | query จะ error                                                              |
| `SubDistrict` fillable `amphure_id` ≠ column `district_id`  | mass-assign ไม่ได้                                                          |
| `layouts/app.blade.php` include `layouts.navigation`        | ไฟล์ไม่มี → **Blade error**                                                 |
| `me-explorer.blade.php` เรียก `route('frontend.dashboard')` | route ไม่มี                                                                 |
| `messenger/register.blade.php` เรียก route ที่ไม่มี         | error                                                                       |

### 9.2 ช่องโหว่ด้านสิทธิ์

| ปัญหา                                               | ทำอะไรได้                                                            |
| --------------------------------------------------- | -------------------------------------------------------------------- |
| `/api/upload/*` ไม่มี auth                          | อัปโหลดไฟล์ได้โดยไม่ต้อง login (document ไม่จำกัด mime)              |
| `/ajax/upload/*` ไม่มี auth                         | เหมือนกัน                                                            |
| `MessengerController::getChannelConversations($id)` | อ่านแชทของคนอื่นได้ (ไม่ตรวจ participant)                            |
| `SessionController` ทุก method                      | ใคร auth ก็เข้าถึง session ของคนอื่นได้                              |
| `NotificationController::list`                      | อ่าน notification ทั้งระบบ ไม่ scope ต่อ user                        |
| `Api\MessengerController` ใช้ 2 ระบบ auth           | Sanctum ที่ `/api/*` และ session ที่ `/ajax/*` — ใช้ method เดียวกัน |

### 9.3 โค้ดตายที่ยังอยู่

| ประเภท      | รายการ                                                                                                                                   |
| ----------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| Controllers | `MessengerController` · `MessengerConversationController` · `MessengerParticipantController` · `UserActivityLogController` (ไม่มี route) |
| Livewire    | `Frontend\{Dashboard, MeExplorer, WorkExplorer, RecruitExplorer}`                                                                        |
| Blade       | `components/backend/⚡*` (~49 ไฟล์) · `components/frontend/⚡*` (3)                                                                      |
| Requests    | `WorkRequest` (ซ้ำกับ `Api/WorkRequest`) · `ProfileUpdateRequest` · `UserPointRequest` (ว่าง)                                            |
| Resources   | ที่เรียก parent แล้ว leak raw model: `IssuePointResource` · `RoleResource` · `UserPointLogResource`                                      |

### 9.4 ข้อมูลที่ไม่ตรงกัน

| จุด                  | ปัญหา                                                                                                     |
| -------------------- | --------------------------------------------------------------------------------------------------------- |
| `SubDistrict`        | model ใช้ `amphure_id` · migration ใช้ `district_id`                                                      |
| Geo columns          | `works`/`recruits` ใช้ `decimal(10,7)` · bookings ใช้ `string`                                            |
| `WorkBookingFactory` | states เขียน `'pending'`/`'completed'` ซึ่งไม่มีใน enum (`waiting-to-confirm`/`confirm`/`close`/`cancel`) |
| `CategoryRequest`    | พิมพ์ field ผิด: `image_ulr` (ต้อง `image_url`)                                                           |
| `UserNotification`   | ไม่ fillable `notificationable_type/id` — MockSeeder ต้องใช้ `unguard()`                                  |

---

## ภาคผนวก ก — ฟีเจอร์ที่ Mobile ต้องขอเพิ่ม

จาก [§8.2](#82-มีในโค้ดแล้ว-แต่ทำไม่ครบ) — สิ่งที่มีในเว็บแต่ **ยังไม่มีใน API**:

| ฟีเจอร์                  | มีในเว็บ                      | มีใน API                                        |
| ------------------------ | ----------------------------- | ----------------------------------------------- |
| Wallet (ดูยอด/ธุรกรรม)   | ✅ `/my-wallet`               | ❌                                              |
| Payment                  | ✅ `/backend/payments`        | ❌                                              |
| Dispute (เปิด/ติดตาม)    | ❌                            | ❌                                              |
| Portfolio (CRUD)         | ✅ ครบ                        | ❌                                              |
| Favorites                | ✅ `/favorites`               | ❌                                              |
| Work Availability        | ✅ `/works/{id}/availability` | ❌                                              |
| Work Promotion           | ✅ `/works/{id}/promote`      | ❌                                              |
| Province Top Works       | ✅ ในหน้าแรก                  | ❌                                              |
| Featured Works           | ✅                            | ❌                                              |
| Reputation               | ✅ `/profile`                 | ❌                                              |
| Verification             | ✅ `/backend/verifications`   | ❌                                              |
| Notification แบบราย user | ✅ `/notifications`           | ⚠️ มีแต่ global                                 |
| Update Work/Recruit      | ✅ `/works/{id}/edit`         | ❌ มี method แต่ไม่ได้ route                    |
| Calendar                 | ✅ `/calendar`                | ❌                                              |
| Search                   | ✅ `/search`                  | ⚠️ มี keyword param แต่ไม่มี `/search` endpoint |

---

## ภาคผนวก ข — ความต่างจากเอกสารเดิม

เอกสารเดิม `.agent/ai_workspace_context.md` (2026-03-02) ล้าสลัยไปมาก:

| หัวข้อ              | เอกสารเดิมบอก                | ความจริง                      |
| ------------------- | ---------------------------- | ----------------------------- |
| migrations          | 46                           | **70**                        |
| models              | 35                           | **54**                        |
| enums               | 20                           | **26**                        |
| seeders             | 13                           | **16**                        |
| API controllers     | 18                           | **24**                        |
| Backend             | 13 Blade controllers         | **Livewire 53 component**     |
| Auth                | Breeze 9 controllers         | **ไม่มี — เป็น Livewire**     |
| Payment             | "ยังไม่เริ่ม"                | **มี 3 ตาราง + model + enum** |
| Posts               | "ไม่มี Model/Controller/API" | **มีครบแล้ว**                 |
| Livewire            | ไม่พูดถึง                    | **98 component**              |
| Livewire version    | 3                            | **4.2**                       |
| Tailwind            | 4                            | **3.x**                       |
| Review/Reply models | มี                           | **ถูกลบแล้ว** ใช้ `posts` แทน |

**Enum ที่เอกสารเดิมบอกผิดเกือบทั้งหมด:**

| Enum                   | เดิม                                    | จริง                                               |
| ---------------------- | --------------------------------------- | -------------------------------------------------- |
| RecruitStatus          | open, close                             | **stand-by, busy, close**                          |
| BookingStatus          | pending, confirm, cancel                | **waiting-to-confirm, confirm, close, cancel**     |
| IssueStatus            | pending, approve                        | **submit, approve, reject**                        |
| BannerType             | image, video                            | **product, promotion, external_url**               |
| ConfigurationValueType | string, integer, boolean, json          | **text, textarea, option, boolean, url**           |
| UserActivityType       | login, logout, register, update_profile | **login, open_url, logout, api_login, api_logout** |
| Gender                 | male, female, other                     | **male, female, unknown**                          |
| PersonType             | individual, company                     | **natural, juristic**                              |
| ThemeScheme            | default, custom                         | **green, yellow**                                  |

---

## ภาคผนวก ค — ข้อเสนอ

### สำหรับทีม Mobile

1. **อ่าน `API-REFERENCE.md` ก่อน** — มีทุก endpoint พร้อม request/response
2. **ระวัง 4 endpoint ที่พัง** — ดู [§9.1](#91-บั๊จที่ทำให้ระบบพังได้)
3. **Session API ยังไม่มี permission check** — ถ้าใช้งานจริงต้องขอ backend เพิ่มก่อน
4. **อย่าเชื่อว่า wallet/payment/dispute พร้อมใช้** — ยังไม่มี API
5. **Upload เปิดสาธารณะ** — ควรขอ backend ปิดก่อน production

### สำหรับ backend

| ลำดับ | งาน                                                                              |
| ----- | -------------------------------------------------------------------------------- |
| 1     | 🔴 แก้ 4 bug ที่ทำให้ route พัง (§9.1)                                           |
| 2     | 🔴 ปิด upload/link-storage + ปิด messenger/session/notification ที่ไม่ตรวจสิทธิ์ |
| 3     | 🟡 สร้าง API สำหรับ wallet/payment/dispute/portfolio/favorites                   |
| 4     | 🟡 เพิ่ม `like_count`/`reply_count`/`avg_review_rating` ใน Recruits              |
| 5     | 🟢 ตั้ง GitHub Actions + PHPStan                                                 |

---

_จัดทำโดย ซาน่า 🌈 · ตรวจสอบจากโค้ดจริง · มี.ค. 2026_
