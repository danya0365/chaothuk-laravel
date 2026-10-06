---
name: project-overview
description: Chaothuk — เช่าถูก คืออะไร — เป้าหมายผลิตภัณฑ์, แนวคิด architecture, stack, roadmap (อ่านตอนเริ่ม session หรือทบทวนภาพรวม)
metadata:
    type: convention
    status: active
    scope: global
    updated: 2026-10-06
---

# Chaothuk — เช่าถูก — ภาพรวม

## โปรเจคนี้คืออะไร

**ตลาดกลางหาฟรีแลนซ์ + ผู้ว่าจ้างในไทย** คนหางาน/ผู้ให้บริการ (Workers) ลงประกาศ **Works**
(ฉันทำงานนี้ได้) ส่วนผู้ว่าจ้าง (Employers) ลงประกาศ **Recruits** (ฉันต้องการคนมาทำ)
ทั้งสองฝั่งจอง → ต่อรอง → ยืนยัน มี GPS ติดตามงานจริง, ระบบแต้ม Issue Points กระตุ้นพฤติกรรม,
Messenger ทั้งแบบล็อกอินและแบบ guest (เบอร์มือถือ) · ภาษาไทยเป็นหลัก

**จุดขาย:** ครอบคลุม 3 หน้าจอ — Livewire frontend (`/frontend`, dark premium UI),
REST API (`/api`, Sanctum) สำหรับ mobile, และ Admin Blade panel (`/backend`)

**เอกสารระบบที่ต้องอ่านก่อนแตะโค้ด** (เขียนจากโค้ดจริง · อัปเดต 2026-10-06):

| เอกสาร | เนื้อหา |
|---|---|
| **[SRS.md](../../../docs/SRS.md)** | ขอบเขต · ฟีเจอร์ · architecture · **ช่องว่างที่ยังขาด** · บั๊จที่ต้องรู้ |
| **[API-REFERENCE.md](../../../docs/API-REFERENCE.md)** | ทุก endpoint ของ `/api/*` (103 ตัว) |
| **[DATA-MODEL.md](../../../docs/DATA-MODEL.md)** | 63 ตารางพร้อม columns |
| [.agent/ai_workspace_context.md](../../../.agent/ai_workspace_context.md) | บริบทสำหรับ AI (สรุป) |

> ⚠️ **ก่อนแก้บั๊จหรือออกแบบ feature ใหม่ ต้องอ่าน [SRS.md §9](../../../docs/SRS.md#9-ข้อจำกัดและบั๊จที่ต้องรู้)**

## แนวคิด architecture

### โครงสร้างสำคัญ

```
app/
├── Console/                  # Artisan commands + Kernel (cron schedule)
├── Enums/                    # 20 PHP 8.1+ backed enums
├── Events/                   # Domain events
├── Http/
│   ├── Controllers/Api/      # 18 API controllers
│   ├── Controllers/Backend/  # 13 Admin blade controllers
│   ├── Controllers/Auth/     # 9 Breeze auth controllers
│   ├── Requests/             # 19 Form Request validators
│   └── Resources/            # 36 API Resource transformers
├── Listeners/  Models/  Providers/  Traits/  View/
routes/                       # api.php · web.php · auth.php · ajax.php
database/                     # 46 migrations · 13 seeders · factories
resources/views/              # Blade (frontend + backend)
lang/th/  lang/en/            # i18n
```

### Stack

| ชั้น     | เทคโนโลยี                                                                     |
| -------- | ----------------------------------------------------------------------------- |
| Backend  | Laravel 11 · PHP 8.2 · MySQL 8                                                |
| Frontend | Livewire 3 + Alpine.js + Tailwind + Vite                                      |
| Auth     | Laravel Sanctum (API token) + Breeze                                          |
| Dev env  | Laravel Sail (Docker Compose) · `sail` alias                                  |
| Docs     | dedoc/scramble → `/docs/api`                                                  |
| เสริม    | intervention/image · milon/barcode · spatie/laravel-options · tightenco/ziggy · maplibre-gl · apexcharts · cropperjs |

### Pattern ที่ต้องรักษา

- API response: `{ status: bool, data }` / `{ status: false, message }`
- **Permission gate ทำใน controller** ไม่ใช้ policy/middleware — pattern นี้ทั้งโปรเจค
- Sanctum ใช้ `auth('sanctum')->user()`
- Pagination: `limitOffset(request()->all())` จาก `App\Traits\Scopes` (ไม่ใช่ของ Laravel)
- Main model ใช้ `SoftDeletes` ทั้งหมด — อย่าลืม `withTrashed()`
- Route group ใช้ prefix + `as` + middleware เสมอ
- ข้อความ user-facing เป็น **ภาษาไทย**
- SoftDeletes/Scopes/enums คือ convention ที่ฝังลึก — อย่า refactor ทิ้งโดยไม่มีเหตุผล

## Roadmap

### 🔴 ควรทำก่อน Production

1. 🔧 `posts.content` → `text` (string จำกัด 255 ตัว สั้นเกินสำหรับรีวิวยาว)
2. 🔧 เพิ่ม `like_count` / `reply_count` / `avg_review_rating` ใน `recruits` (works มีแล้ว แต่ recruits ไม่มี)
3. 🔧 เพิ่ม `price_agreed` + `completed_at` ใน bookings
4. 💰 ตาราง `payments` — booking ยืนยันแล้วไม่มี record การจ่ายเงิน ขาดหลักฐานตอน dispute
5. 💰 ตาราง `wallets` + `wallet_transactions` — ปัจจุบันไม่มีที่เก็บยอดคงเหลือ

### 🟡 สำคัญปานกลาง

6. ⭐ `favorites` — ผู้ใช้ bookmark งานเพื่อเทียบไม่ได้
7. 🛡️ `disputes` — ยืนยันจองแล้วเกิดปัญหา ไม่มีช่องทางอุทธรณ์
8. 📋 `portfolios` — ผู้ให้บริการไม่มีที่แสดงผลงานเก่า
9. 📦 `work_packages` — works มี `price` เดียว ไม่รองรับหลายแพ็กเกจ
10. 📅 `work_availabilities` — ลด booking ที่ถูก cancel เพราะผู้ให้บริการไม่ว่าง

รายละเอียด schema ที่เสนอทั้งหมด → `.agent/task.md`

## ⚠️ ข้อมูลอ่อนไหว

`.claude/settings.local.json` มี API key จริง — **ห้าม** commit (gitignore อยู่แล้ว),
ห้าม paste ค่าลงไฟล์หรือข้อความ, และห้ามอ่านค่ามาแสดงผลในรายงาน
