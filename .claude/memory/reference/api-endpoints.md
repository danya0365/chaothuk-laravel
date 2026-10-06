---
name: api-endpoints
description: แผนที่ route ทั้ง 4 ไฟล์ของ Chaothuk — api / frontend / web / ajax, group และ auth ของแต่ละกลุ่ม (อ่านก่อนเพิ่มหรือแก้ endpoint)
metadata:
    type: reference
    status: active
    scope: routing
    updated: 2026-10-06
---

# Route Map — Chaothuk

ทั้งหมดโหลดจาก `bootstrap/app.php` (หรือ `routes/` ตาม version) — ไฟล์และจำนวนบรรทัด:

| ไฟล์                  | บรรทัด | หน้าที่                          | หน้าจอ        |
| --------------------- | ------ | -------------------------------- | ------------- |
| `routes/api.php`      | 238    | REST API สำหรับ mobile (Sanctum) | `/api/*`      |
| `routes/frontend.php` | 118    | Livewire SPA สำหรับผู้ใช้        | `/frontend/*` |
| `routes/web.php`      | 173    | Backend admin + Breeze auth      | `/backend/*`  |
| `routes/ajax.php`     | 28     | AJAX-only endpoints              | `/ajax/*`     |
| `routes/console.php`  | —      | Cron schedule                    | —             |

## API groups (`routes/api.php`)

| Prefix                                                                                           | `as`                    | Auth             | เนื้อหา                                                                    |
| ------------------------------------------------------------------------------------------------ | ----------------------- | ---------------- | -------------------------------------------------------------------------- |
| `auth`                                                                                           | `api.auth.`             | บางส่วน          | login · register · customer-register-login · user · logout · revoke-token  |
| `me`                                                                                             | `api.me.`               | ✅ ทั้งหมด       | profile · password · notifications · works · work-likes · bookings · posts |
| `works`                                                                                          | `api.works.`            | บางส่วน          | list · top-hits · detail · likes · bookings · **reviews** · create         |
| `recruits`                                                                                       | `api.recruits.`         | บางส่วน          | list · detail · bookings · **reviews** · create                            |
| `work-bookings`                                                                                  | `api.work-bookings.`    | confirm เท่านั้น | show · worker-confirm · customer-confirm                                   |
| `recruit-bookings`                                                                               | `api.recruit-bookings.` | confirm เท่านั้น | show · worker-confirm · customer-confirm                                   |
| `posts`                                                                                          | `api.posts.`            | บางส่วน          | รีวิว + ตอบกลับ (parent_id) · likes                                        |
| `messenger`                                                                                      | `api.messenger.`        | ผสม              | guest mobile-phone channel + auth channel + seen                           |
| `notifications`                                                                                  | `api.notifications.`    | ❌               | list · last-update · show                                                  |
| `banners`                                                                                        | `api.banners.`          | ✅ ทั้งหมด       | list · pinned · last-update                                                |
| `issue-points`                                                                                   | `api.issue-points.`     | ❌               | detail                                                                     |
| `user-logs`                                                                                      | `api.customer-logs.`    | ✅               | point-logs · point-transaction-logs                                        |
| `sessions`                                                                                       | `api.sessions.`         | ✅               | session management                                                         |
| `upload`                                                                                         | `api.upload.`           | ❌               | image · avatar · cover · document · original-image                         |
| `barcode` · `barcode-preview`                                                                    | `api.barcode.`          | ❌               | QR + Code128 generate/preview                                              |
| `configurations` `geographies` `provinces` `districts` `sub-districts` `categories` `work-types` |                         | ❌               | ข้อมูลอ้างอิง                                                              |

> 📌 รายละเอียด path/method แบบเต็มของทุก endpoint → `.agent/ai_workspace_context.md` (หัวข้อ "API Routes")

## Frontend groups (`routes/frontend.php`)

| Prefix                      | หน้าที่                                                            | Auth           |
| --------------------------- | ------------------------------------------------------------------ | -------------- |
| `frontend/auth`             | login · register · forgot-password                                 | guest เท่านั้น |
| `frontend` (หน้าหลัก)       | home · works · recruits · categories · map · search · reputation   | ผสม            |
| `frontend` (หน้าเก็บข้อมูล) | works/create · works/{id}/edit · bookings · availability · profile | ✅             |

## Convention ตอนเพิ่ม route

```php
Route::group(['prefix' => 'x', 'as' => 'api.x.', 'middleware' => ['auth:sanctum']], function () {
    // ชื่อ method ใน route name ต่อท้ายด้วย '.' เสมอ (เช่น ->name('list'))
});
```

- ใส่ `middleware: auth:sanctum` เฉพาะ endpoint ที่ต้องล็อกอิน
- ใช้ `->name()` ใน route ที่ต้องเรียกจาก Blade/Livewire เท่านั้น
- API response ต้องเป็น `{ status: bool, data }` — ดู [[conventions]]

ดู [[project-overview]] · รายละเอียดเต็มที่ `.agent/ai_workspace_context.md`
