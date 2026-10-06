# API Reference — Chaothuk (เช่าถูก)

### REST API สำหรับ Mobile App

|                      |                                           |
| -------------------- | ----------------------------------------- |
| **Base URL (local)** | `http://localhost/api`                    |
| **Base URL (prod)**  | `https://{domain}/api`                    |
| **รวม endpoint**     | **103**                                   |
| **Auth**             | Laravel Sanctum (Bearer token)            |
| **เอกสาร auto**      | `/docs/api` (dedoc/scramble)              |
| **อัปเดต**           | 2026-10-06 · จาก `php artisan route:list` |

> 📌 อ่าน [SRS.md](SRS.md) ก่อน — เข้าใจ domain ก่อน แล้วค่อยดู endpoint ที่นี่

---

## การยืนยันตัวตน

```http
Authorization: Bearer <sanctum_token>
```

Token ได้จาก `POST /api/auth/login` หรือ `POST /api/auth/register`

⚠️ **ไม่มี rate limit** — `throttle` ถูก comment ออกจาก `bootstrap/app.php` ต้องจัดการ retry เองฝั่ง client

⚠️ **ไม่มี API versioning** — ใช้ `/api/` ไม่มี `/api/v1`

---

## Format การตอบกลับ

### สำเร็จ (200)

```json
{ "status": true, "data": { "...": "..." } }
```

### ไม่สำเร็จ

```json
{ "status": false, "message": "no permission" }
```

### ⚠️ ข้อยกเว้น — Session endpoints

`/api/sessions/*` คืนคนละรูปแบบ (**ไม่มี field `status`**):

```json
{ "data": { "...": "..." }, "message": "..." }
```

และ create จะได้ **201** แทน 200 — client ต้องรองรับทั้งสองแบบ

---

## สารบัญ

| กลุ่ม                             | Endpoint | Auth |
| --------------------------------- | -------- | ---- |
| [Auth](#auth)                     | 6        | ผสม  |
| [Me](#me)                         | 14       | ✅   |
| [Reference Data](#reference-data) | 7        | ❌   |
| [Works](#works)                   | 13       | ผสม  |
| [Recruits](#recruits)             | 8        | ผสม  |
| [Bookings](#bookings)             | 6        | ผสม  |
| [Posts](#posts)                   | 8        | ผสม  |
| [Sessions (GPS)](#sessions-gps)   | 7        | ✅   |
| [Messenger](#messenger)           | 9        | ผสม  |
| [Notifications](#notifications)   | 3        | ❌   |
| [Banners](#banners)               | 4        | ✅   |
| [Upload](#upload)                 | 5        | ❌   |
| [Misc](#misc)                     | 13       | ผสม  |

---

## Auth

| Method | Path                                | ชื่อ route                         | Auth | คำอธิบาย                          |
| ------ | ----------------------------------- | ---------------------------------- | ---- | --------------------------------- |
| POST   | `/api/auth/register`                | `api.auth.register`                | ❌   | สมัครสมาชิก                       |
| POST   | `/api/auth/login`                   | `api.auth.login`                   | ❌   | เข้าสู่ระบบ → ได้ token           |
| GET    | `/api/auth/user`                    | `api.auth.user`                    | ✅   | ข้อมูลผู้ใช้ปัจจุบัน              |
| POST   | `/api/auth/logout`                  | `api.auth.logout`                  | ✅   | เพิกถอน token ปัจจุบัน            |
| POST   | `/api/auth/revoke-token`            | `api.auth.revokeToken`             | ✅   | เพิกถอน token ที่ระบุ             |
| POST   | `/api/auth/customer-register-login` | `api.auth.customer-register-login` | ❌   | 🔴 **500 — method ไม่มีอยู่จริง** |

### POST /api/auth/login

```json
// Request
{ "email": "user@example.com", "password": "secret" }

// Response 200
{
  "status": true,
  "data": {
    "token": "1|xxxxxxxxxxxxxxxx",
    "user": {
      "id": 1,
      "name": "สมชาย ใจดี",
      "email": "user@example.com",
      "first_name": "สมชาย",
      "last_name": "ใจดี",
      "profile_image": "https://...",
      "cover_image": null,
      "location": "กรุงเทพมหานคร",
      "roles": ["member"],
      "permissions": ["create_work"],
      "available_points": 0,
      "received_points": 0,
      "redeem_points": 0
    }
  }
}
```

### POST /api/auth/register

```json
// Request
{
    "name": "สมชาย ใจดี",
    "email": "user@example.com",
    "password": "secret",
    "password_confirmation": "secret",
    "first_name": "สมชาย",
    "last_name": "ใจดี",
    "profile_image": null
}

// Validation: password min 4, max 20
```

---

## Me

ทุก endpoint ต้องมี token

| Method | Path                               | คำอธิบาย                              |
| ------ | ---------------------------------- | ------------------------------------- |
| GET    | `/api/me`                          | โปรไฟล์ตัวเอง                         |
| POST   | `/api/me`                          | แก้ไขโปรไฟล์                          |
| POST   | `/api/me/password`                 | เปลี่ยนรหัสผ่าน                       |
| GET    | `/api/me/notifications`            | notification ของตัวเอง                |
| GET    | `/api/me/works`                    | works ที่ตัวเองสร้าง                  |
| GET    | `/api/me/work-likes`               | works ที่ไลค์ไว้                      |
| GET    | `/api/me/work-likes/work/{workId}` | เช็คว่าไลค์ work นี้ไหม               |
| GET    | `/api/me/work-bookings`            | bookings ของตัวเอง (ฝั่งผู้ให้บริการ) |
| GET    | `/api/me/recruits`                 | recruits ที่ตัวเองสร้าง               |
| GET    | `/api/me/recruit-bookings`         | recruit bookings ของตัวเอง            |
| GET    | `/api/me/posts`                    | posts/รีวิว ของตัวเอง                 |
| GET    | `/api/me/post-likes`               | posts ที่ไลค์ไว้                      |
| GET    | `/api/me/post-likes/post/{postId}` | เช็คว่าไลค์ post นี้ไหม               |

> ⚠️ `GET /api/me/recruits` คืน `WorkCollection` (ผิด resource — wrap Recruit models)

> ⚠️ `POST /api/me` ใช้ inline `Validator` ไม่ใช่ Form Request และคืน model ดิบ (ไม่ผ่าน Resource)

---

## Reference Data

ข้อมูลสาธารณะ ไม่ต้อง auth — **ควร cache ใน mobile เพราะไม่ค่อยเปลี่ยน**

| Method | Path                              | คืน             |
| ------ | --------------------------------- | --------------- |
| GET    | `/api/provinces`                  | 77 จังหวัด      |
| GET    | `/api/districts/{provinceId}`     | อำเภอในจังหวัด  |
| GET    | `/api/sub-districts/{districtId}` | ตำบลในอำเภอ     |
| GET    | `/api/geographies`                | ภูมิภาค (6)     |
| GET    | `/api/work-types`                 | ประเภทงาน       |
| GET    | `/api/categories`                 | หมวดหมู่        |
| GET    | `/api/configurations`             | ค่า config ระบบ |

```json
// GET /api/provinces
{
    "status": true,
    "data": [{ "id": 1, "title": "กรุงเทพมหานคร", "photo": "https://..." }]
}
```

> ⚠️ Province resource คืน `title` + `photo` ไม่ใช่ `name_th`/`name_en`/`code`

---

## Works

ประกาศงานฝั่งผู้ให้บริการ

### อ่าน (ไม่ต้อง auth)

| Method | Path                                   | คำอธิบาย                                  |
| ------ | -------------------------------------- | ----------------------------------------- |
| GET    | `/api/works`                           | รายการ + filter                           |
| GET    | `/api/works/top-hits`                  | งานเด่น (display_priority สูง)            |
| GET    | `/api/works/{workId}`                  | รายละเอียด                                |
| GET    | `/api/works/{workId}/likes`            | รายชื่อผู้ไลค์                            |
| GET    | `/api/works/{workId}/likes/count`      | จำนวนไลค์                                 |
| GET    | `/api/works/{workId}/bookings`         | bookings ทั้งหมด                          |
| GET    | `/api/works/{workId}/confirm-bookings` | bookings ที่ยืนยันแล้ว (filter วันที่ได้) |
| GET    | `/api/works/{workId}/reviews`          | รีวิว                                     |
| GET    | `/api/works/{workId}/reviews/count`    | จำนวนรีวิว                                |

### เขียน (ต้อง auth)

| Method | Path                           | สิทธิ์               |
| ------ | ------------------------------ | -------------------- |
| POST   | `/api/works`                   | 🔒 `create_work`     |
| POST   | `/api/works/{workId}/bookings` | ✅ login พอ          |
| POST   | `/api/works/{workId}/likes`    | ✅ login พอ          |
| POST   | `/api/works/{workId}/reviews`  | ⚠️ **ไม่ตรวจสิทธิ์** |

### Query parameters (list)

```
limit, offset, keyword, province_id, date, date_start, date_end
```

### POST /api/works

```json
// Request
{
    "code": "ABC123",
    "title": "รถกะบะให้เช่า มีคนขับ",
    "description": "รถสภาพดี ขับสบาย",
    "primary_image": "https://example.com/main.jpg",
    "images": ["https://example.com/1.jpg"],
    "province_id": 1,
    "work_type_id": 1,
    "author_id": 5,
    "price": 500,
    "display_priority": 0
}
```

Response ถ้าไม่มีสิทธิ์:

```json
{ "status": false, "message": "no permission" } // HTTP 403
```

### WorkResource — field ที่คืน

```json
{
    "id": 1,
    "code": "ABC123",
    "title": "รถกะบะให้เช่า มีคนขับ",
    "description": "...",
    "details": null,
    "primary_image": "https://...",
    "images": ["https://..."],
    "province": { "...": "..." },
    "work_type": { "id": 1, "title": "รถกะบะ", "image": null },
    "categories": [],
    "author": { "...": "..." },
    "price": 500,
    "display_priority": 0,
    "work_status": "stand-by",
    "like_count": 12,
    "reply_count": 3,
    "avg_review_rating": 4.5,
    "created_at": "2026-03-01T10:00:00.000000Z"
}
```

> ⚠️ Resource **ไม่คืน** `latitude`, `longitude`, `is_suspended`, `updated_at`
> ถ้าต้องการแสดงบนแผนที่ → ต้องขอ backend เพิ่ม

---

## Recruits

ประกาศหาคนฝั่งผู้ว่าจ้าง

### อ่าน (ไม่ต้อง auth)

| Method | Path                                      |
| ------ | ----------------------------------------- |
| GET    | `/api/recruits`                           |
| GET    | `/api/recruits/{recruitId}`               |
| GET    | `/api/recruits/{recruitId}/bookings`      |
| GET    | `/api/recruits/{recruitId}/reviews`       |
| GET    | `/api/recruits/{recruitId}/reviews/count` |

### เขียน (ต้อง auth)

| Method | Path                                 | สิทธิ์               |
| ------ | ------------------------------------ | -------------------- |
| POST   | `/api/recruits`                      | 🔒 `create_recruit`  |
| POST   | `/api/recruits/{recruitId}/bookings` | ✅ login พอ          |
| POST   | `/api/recruits/{recruitId}/reviews`  | ⚠️ **ไม่ตรวจสิทธิ์** |

### RecruitResource — ต่างจาก WorkResource

```json
{
    "id": 1,
    "title": "ต้องการช่างซ่อมเครื่อง",
    "description": "...",
    "primary_image": null,
    "images": [],
    "province": {},
    "work_type": {},
    "author": {},
    "budget": 5000,
    "recruit_status": "stand-by",
    "display_priority": 0,
    "created_at": "..."
}
```

> ⚠️ **RecruitResource ไม่มี** `like_count` / `reply_count` / `avg_review_rating`
> และ **ไม่มี `code`** (Works มี) — ต่างจาก Works ที่ชัดเจน

> 🔴 **ไม่มี endpoint แก้ไข recruit** — มี method `updateRecruit` ใน controller แต่ไม่ได้ route
> (เหมือนกันกับ `updateWork`)

---

## Bookings

### อ่าน

| Method | Path                         | Auth | คำอธิบาย                   |
| ------ | ---------------------------- | ---- | -------------------------- |
| GET    | `/api/work-bookings/{id}`    | ❌   | รายละเอียด booking         |
| GET    | `/api/recruit-bookings/{id}` | ❌   | รายละเอียด recruit booking |

### ยืนยัน (ต้อง auth + ต้องเป็นเจ้าของ work/recruit)

| Method | Path                                                        |
| ------ | ----------------------------------------------------------- |
| POST   | `/api/work-bookings/{workBookingId}/worker-confirm`         |
| POST   | `/api/work-bookings/{workBookingId}/customer-confirm`       |
| POST   | `/api/recruit-bookings/{recruitBookingId}/worker-confirm`   |
| POST   | `/api/recruit-bookings/{recruitBookingId}/customer-confirm` |

**สิทธิ์:** ต้องเป็นเจ้าของ work/recruit — ถ้าไม่ใช่ได้ 403 `'no permission'`

### POST booking

```json
// POST /api/works/{workId}/bookings
{
    "customer_message": "อยากจองวันศุกร์",
    "mobile_phone": "0812345678",
    "booking_date": "2026-10-10 09:00:00"
}
```

### BookingResource

```json
{
    "id": 1,
    "work": {},
    "author": {},
    "booking_date": "2026-10-10T09:00:00.000000Z",
    "booking_status": "waiting-to-confirm",
    "worker_confirm_status": "waiting-to-confirm",
    "customer_confirm_status": "waiting-to-confirm",
    "customer_message": "...",
    "mobile_phone": "0812345678",
    "created_at": "..."
}
```

> ⚠️ Resource ไม่คืน `latitude`/`longitude` ทั้งที่ booking เก็บไว้

### สถานะ

```
booking_status:         waiting-to-confirm → confirm → close
                        (หรือ → cancel)

worker_confirm_status:  waiting-to-confirm | confirm | rejected
customer_confirm_status: waiting-to-confirm | confirm | rejected
```

เมื่อ **ทั้งสองฝ่าย confirm** → `booking_status` จะเปลี่ยนเป็น `confirm` อัตโนมัติ

---

## Posts (รีวิว + ตอบกลับ)

ระบบรีวิวใช้ตารางเดียว `posts` — รีวิวหลัก (`rating > 0`) และตอบกลับ (`parent_id` ชี้กลับ)

### อ่าน

| Method | Path                          | Auth |
| ------ | ----------------------------- | ---- |
| GET    | `/api/posts`                  | ❌   |
| GET    | `/api/posts/{id}`             | ❌   |
| GET    | `/api/posts/{id}/likes`       | ❌   |
| GET    | `/api/posts/{id}/likes/count` | ❌   |

### เขียน

| Method | Path                    | Auth |
| ------ | ----------------------- | ---- |
| POST   | `/api/posts`            | ✅   |
| POST   | `/api/posts/{id}`       | ✅   |
| DELETE | `/api/posts/{id}`       | ✅   |
| POST   | `/api/posts/{id}/likes` | ✅   |

### POST /api/posts

```json
// สร้างรีวิวหลัก
{ "title": "ดีมาก", "content": "บริการดี รวดเร็ว", "rating": 5 }

// ตอบกลับรีวิว
{ "content": "ขอบคุณค่ะ", "parent_id": 12, "rating": 0 }
```

### PostResource

```json
{
    "id": 1,
    "title": "ดีมาก",
    "content": "บริการดี รวดเร็ว",
    "images": [],
    "author": {},
    "rating": 5,
    "like_count": 3,
    "replies": [],
    "created_at": "..."
}
```

> ⚠️ **ไม่ตรวจสิทธิ์ `create_post`** — แม้ import `Permission` enum ไว้ แต่ไม่ได้ใช้
> ⚠️ ไม่ตรวจว่าเป็นเจ้าของก่อน update/delete

---

## Sessions (GPS)

จับเวลา + ตำแหน่งระหว่างทำงาน

> ⚠️ **ทุก endpoint ในกลุ่มนี้ไม่ตรวจว่าผู้เรียกเป็น worker/customer ของ session นั้นจริง**
> ใครมี token ก็อ่าน/เขียน session ของคนอื่นได้ — **ควรขอ backend แก้ก่อนใช้งานจริง**

| Method | Path                           | คำอธิบาย           |
| ------ | ------------------------------ | ------------------ |
| GET    | `/api/sessions`                | sessions ของตัวเอง |
| POST   | `/api/sessions/start`          | เริ่ม session      |
| GET    | `/api/sessions/{id}`           | รายละเอียด         |
| GET    | `/api/sessions/{id}/locations` | ประวัติ GPS        |
| POST   | `/api/sessions/{id}/location`  | บันทึกจุดปัจจุบัน  |
| POST   | `/api/sessions/{id}/stop`      | หยุด session       |
| POST   | `/api/sessions/{id}/confirm`   | ยืนยัน session     |

### POST /api/sessions/start

```json
// Request
{
    "sessionable_type": "work", // work | recruit
    "sessionable_id": 12,
    "booking_type": "work_booking", // work_booking | recruit_booking
    "booking_id": 34,
    "started_at": "2026-10-10T09:00:00Z",
    "notes": null
}
```

**ผลลัพธ์:** `worker_confirm` ถูกตั้งเป็น `'confirmed'` ทันทีโดยไม่รอกดยืนยันจริง

### POST /api/sessions/{id}/location

```json
{
    "latitude": 13.7563,
    "longitude": 100.5018,
    "accuracy": 12.5,
    "speed": 0.0,
    "heading": 180.0,
    "recorded_at": "2026-10-10T09:15:00Z"
}
```

### สถานะ

```
status:         active | paused | completed | cancelled
worker_confirm: pending | confirmed | disputed
customer_confirm: pending | confirmed | disputed
```

---

## Messenger

รองรับ 2 แบบ — **ผู้ใช้ที่ login** และ **guest ที่มีเบอร์มือถือ**

### Guest — mobile phone channel (ไม่ต้อง auth)

| Method | Path                                                                        |
| ------ | --------------------------------------------------------------------------- |
| POST   | `/api/messenger/mobile-phone-channel/new`                                   |
| GET    | `/api/messenger/mobile-phone-channel/{id}/{mobilePhone}/conversations`      |
| GET    | `/api/messenger/mobile-phone-channel/{id}/{mobilePhone}/conversations/last` |
| POST   | `/api/messenger/mobile-phone-channel/{id}/{mobilePhone}/conversations`      |

### Auth channel (ต้อง token)

| Method | Path                                                    |
| ------ | ------------------------------------------------------- |
| GET    | `/api/messenger/channel/me`                             |
| GET    | `/api/messenger/channel/{channelId}/conversations`      |
| POST   | `/api/messenger/channel/{channelId}/conversations`      |
| GET    | `/api/messenger/channel/{channelId}/conversations/last` |

### Mark seen (ไม่ต้อง auth)

| Method | Path                                                              |
| ------ | ----------------------------------------------------------------- |
| POST   | `/api/messenger/channel/{id}/conversations/{conversationId}/seen` |

> 🔴 **`getChannelConversations` ไม่ตรวจ participant** — ส่ง `channelId` ของคนอื่นก็อ่านได้
> ควรขอ backend เพิ่มการตรวจ

### สร้าง conversation

```json
{
    "local_code_id": "uuid-client-side",
    "content": "สวัสดีค่ะ",
    "type": "text" // text | image | youtube | url | emoticon
}
```

`local_code_id` ใช้ dedupe ฝั่ง client — ส่งซ้ำได้โดยไม่สร้างซ้ำ

---

## Notifications

> 🔴 **ไม่ตรวจว่าเป็นของผู้เรียก** — อ่าน notification ทั้งระบบได้

| Method | Path                             | Auth |
| ------ | -------------------------------- | ---- |
| GET    | `/api/notifications`             | ❌   |
| GET    | `/api/notifications/last-update` | ❌   |
| GET    | `/api/notifications/{id}`        | ❌   |

**Polling:** ใช้ `/last-update` เพื่อเช็คว่ามีของใหม่ไหม (ไม่ต้องดึงทั้งหมด)

> ⚠️ **ระบบนี้คือ notification ระดับระบบ (ประกาศ) ไม่ใช่ราย user**
> ราย user อยู่ที่ `GET /api/me/notifications` (คนละแหล่ง)

---

## Banners

| Method | Path                       | Auth |
| ------ | -------------------------- | ---- |
| GET    | `/api/banners`             | ✅   |
| GET    | `/api/banners/pinned`      | ✅   |
| GET    | `/api/banners/last-update` | ✅   |
| GET    | `/api/banners/{id}`        | ✅   |

**BannerResource:** `id, name, type, image_url, external_url, view_count, is_public, is_pinned, expired_at, content, created_at, updated_at`

**BannerType:** `product` · `promotion` · `external_url`

---

## Upload

> 🔴 **ไม่ต้อง auth** — เปิดสาธารณะ ควรขอ backend ปิดก่อน production

| Method | Path                         | ขนาด  | หมายเหตุ                         |
| ------ | ---------------------------- | ----- | -------------------------------- |
| POST   | `/api/upload/image`          | 10 MB | ต้องเป็น image                   |
| POST   | `/api/upload/original-image` | 10 MB |                                  |
| POST   | `/api/upload/avatar`         | 10 MB | อัปเดต `profile_image` อัตโนมัติ |
| POST   | `/api/upload/cover`          | 10 MB | อัปเดต `cover_image` อัตโนมัติ   |
| POST   | `/api/upload/document`       | 10 MB | ⚠️ **ไม่จำกัด mime**             |

> ⚠️ ถ้า login อยู่ → avatar/cover จะอัปเดตโปรไฟล์ให้เลย ถ้าไม่ login → แค่คืน path

---

## Misc

| Method | Path                                    | Auth | คำอธิบาย                                                            |
| ------ | --------------------------------------- | ---- | ------------------------------------------------------------------- |
| GET    | `/api/issue-points/{id}`                | ❌   | รายละเอียดแต้ม                                                      |
| GET    | `/api/user-logs/point-logs`             | ✅   | ประวัติแต้ม                                                         |
| GET    | `/api/user-logs/point-transaction-logs` | ✅   | ประวัติธุรกรรมแต้ม                                                  |
| GET    | `/api/barcode/qr/{code}`                | ❌   | สร้าง QR                                                            |
| GET    | `/api/barcode/code128/{code}`           | ❌   | Code128                                                             |
| GET    | `/api/barcode/code128a/{code}`          | ❌   |                                                                     |
| GET    | `/api/barcode/code128b/{code}`          | ❌   |                                                                     |
| GET    | `/api/barcode/code128c/{code}`          | ❌   |                                                                     |
| GET    | `/api/barcode-preview/qr/{code}`        | ❌   | preview QR                                                          |
| GET    | `/api/barcode-preview/code128/{code}`   | ❌   | preview Code128                                                     |
| GET    | `/api/barcode-preview/code128a/{code}`  | ❌   |                                                                     |
| GET    | `/api/barcode-preview/code128b/{code}`  | ❌   |                                                                     |
| GET    | `/api/barcode-preview/code128c/{code}`  | ❌   |                                                                     |
| GET    | `/api/link-storage`                     | ❌   | 🔴 **ควรลบ** — รัน `Artisan::call('storage:link')` โดยไม่ตรวจสิทธิ์ |

> ⚠️ `user-logs` ใช้ name prefix `api.customer-logs.` ไม่ใช่ `api.user-logs.` (ไม่กระทบการเรียก HTTP)

---

## สรุปช่องว่าง — ฟีเจอร์ที่ยังไม่มีใน API

ตารางนี้มีในเว็บแต่ **ไม่มีใน API** → ต้องขอ backend เพิ่ม

| ฟีเจอร์             | ตาราง/โมเดล มีแล้ว                          | Livewire page                                             |
| ------------------- | ------------------------------------------- | --------------------------------------------------------- |
| Wallet              | `user_wallets`, `wallet_transactions`       | `/frontend/my-wallet`                                     |
| Payment             | `payments`                                  | `/backend/payments`                                       |
| Dispute             | `disputes`                                  | `/backend/disputes` (admin เท่านั้น — user ยังเปิดไม่ได้) |
| Portfolio           | `portfolios`                                | ครบทั้ง 4 หน้า                                            |
| Favorites           | `favorites`                                 | `/frontend/favorites`                                     |
| Availability        | `work_availabilities`, `work_blocked_dates` | `/frontend/works/{id}/availability`                       |
| Promotion           | `featured_works`                            | `/frontend/works/{id}/promote`                            |
| Reputation          | `user_reputations` ฯลฯ                      | `/frontend/profile/{id}`                                  |
| Verification        | `user_verifications`                        | ผ่าน admin                                                |
| Featured works      | `featured_works`                            | `/backend/featured-works`                                 |
| Province top works  | `province_top_works`                        | หน้าแรก                                                   |
| Update work/recruit | —                                           | method มีอยู่แต่ไม่ได้ route                              |
| Theme (dark/light)  | `users.theme`                               | ใช้ได้แค่ผ่าน `/ajax/me/update-theme`                     |

---

## Checklist ก่อนเริ่มทำ

- [ ] ตัดสินใจเรื่อง **base URL** และ environment (dev/staging/prod)
- [ ] ขอ **token ทดสอบ** จาก backend — มีบัญชี `worker1@chaothuk.test` / `password` ใน dev
- [ ] ทดสอบ `/api/auth/login` → `/api/auth/user` ก่อน
- [ ] ตรวจ `limit`/`offset` ว่า response shape เป็นแบบไหน (array หรือ paginated)
- [ ] อย่าใช้ `/api/sessions/*` จนกว่า backend แก้ permission
- [ ] อย่าเรียก `/api/link-storage`
- [ ] เตรียม retry logic เอง (ไม่มี rate limit)

---

_จัดทำโดย ซาน่า 🌈 · ตรวจจาก `php artisan route:list` จริง · มี.ค. 2026_
