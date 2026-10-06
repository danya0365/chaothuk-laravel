---
name: conventions
description: มาตรฐานโค้ด/naming/git ของโปรเจค Laravel (อ่านก่อนเขียนโค้ดใหม่ หรือเมื่อสงสัยว่าควรวางไฟล์/ตั้งชื่อยังไง) — ชี้ไป code-standards rule ที่โหลดตอนแตะโค้ด
metadata:
  type: convention
  status: active
  scope: global
  updated: 2026-10-06
---

# Coding Conventions — Laravel 11 / PHP 8.2

> มาตรฐานเต็ม (โหลดตอนแตะโค้ด) อยู่ที่ [`.claude/rules/code-standards.md`](../../rules/code-standards.md)
> + [`definition-of-done.md`](../../rules/definition-of-done.md) · ไฟล์นี้สรุปสั้นๆ
> ภาพรวมโปรเจคและ pattern ที่ต้องรักษา → [[project-overview]]

## หลักที่ยึดก่อน
- **Typed & strict** — ประกาศ type ทุกที่ (PHP 8.2 strict_types เมื่อเหมาะกับไฟล์ใหม่)
- **No secret in code** — token/secret อยู่ใน `.env` เท่านั้น; `.env*` ถูก gitignore อยู่แล้ว
- **Follow existing patterns ก่อน** — ถ้าโปรเจคทำแบบหนึ่งทั่วโปรเจค ห้ามเปลี่ยนเฉพาะจุด

## Laravel-specific (สำคัญมาก — อย่าฝ่าฝืน)
- **Permission gate ทำใน controller** (`$user->isPermission(...)`) — ไม่ใช้ Policy/Middleware เว้นแต่พี่สั่งเปลี่ยน
- API response เสมอ: `{ status: bool, data }` หรือ `{ status: false, message }`
- Sanctum: `auth('sanctum')->user()` ในทุก API controller
- Pagination ใช้ `limitOffset(request()->all())` (`App\Traits\Scopes`) ไม่ใช้ `paginate()` เอง
- Model หลักใช้ `SoftDeletes` — ใช้ `withTrashed()` / `onlyTrashed()` เมื่อต้องการ
- Form Request แยกไว้ใน `app/Http/Requests/` (ไม่ validate inline ใน controller เกินจำเป็น)
- API คืนค่า ผ่าน `app/Http/Resources/` (มีอยู่แล้ว 36 ตัว)
- Enum ใหม่ → `app/Enums/` (มีอยู่แล้ว 20 ตัว ใช้ backed enum)
- Route ใหม่ → `routes/api.php` / `web.php` พร้อม prefix + name + middleware

## Naming
- PHP: StudlyCase class · camelCase method/variable · snake_case table/column
- Model ตารางเดี่ยว → `Work`, `RecruitBooking` · pivot ตามธรรมชาติ
- Route name: `api.<group>.<action>` (ตามที่มีอยู่) · Blade: kebab-case ไฟล์
- Migration: `yyyy_mm_dd_create_xxx_table` (Laravel convention)

## Test
- PHPUnit ตั้งไว้แล้วแต่ coverage น้อยมาก — เขียนเทสต์เมื่อพี่สั่งหรือแก้บั๊จ logic

## Git
- **ห้าม commit/push เอง — รอคำสั่งพี่เท่านั้น** (กฎเหล็กใน AGENTS.md)
- commit message: **conventional commits** (`feat:`/`fix:`/`chore:`/`docs:`/`refactor:`/`test:`) · เนื้อความอังกฤษ
- ทำงานบน `develop` เป็นหลัก (ดู [[develop-only-workflow]] ถ้ามี)

## ที่ยังไม่ตกลง (TODO — เคาะกับพี่)
- ใช้ Pest หรือ PHPUnit เป็นหลัก
- มาตรฐาน API versioning (`/api/v1` vs `/api`) → [[project-overview]]
- นโยบาย test coverage ขั้นต่ำ
