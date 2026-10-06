---
paths:
    - "app/**"
    - "routes/**"
    - "database/**"
    - "resources/views/**"
    - "config/**"
    - "tests/**"
---

# Code Standards — Chaothuk — เช่าถูก (Laravel 11 / PHP 8.2)

> โหลดตอนแตะโค้ดจริง · ปรับใช้: **กฎที่ tool บังคับแล้ว ไม่ต้องท่องจำ — รู้ว่ามี gate อะไร + รันยังไง ก็พอ**
> 🚦 **ก่อนบอกว่า "เสร็จ" ต้องผ่าน [Definition of Done](definition-of-done.md)** ทุกครั้ง

## 🚦 Gate ที่มีจริง

```bash
# lint (code style)
./vendor/bin/pint --test              # หรือ ./vendor/bin/pint เพื่อแก้อัตโนมัติ

# static analysis — ยังไม่ได้ติดตั้งในโปรเจค
# ./vendor/bin/phpstan analyse          # แนะนำเปิด (level 5+)

# test
./vendor/bin/phpunit                   # หรือ sail php artisan test
./vendor/bin/phpunit --filter=XxxTest  # เจาะจง

# syntax check ไฟล์เดียว (เร็ว)
php -l app/Models/Work.php
```

> ❗ **ยังไม่มี CI workflow สำหรับ lint/test** — gate ข้างบนรันมือเท่านั้น
> เมื่อพี่ต้องการ → ตั้ง GitHub Actions + `.github/workflows/` (ดู ADR-0002 เรื่อง branch)

## Laravel conventions (สำคัญ — pattern ทั้งโปรเจค)

- **Permission gate ทำใน controller** — `$user->isPermission(Permission::CREATE_WORK->value)`
  ตรวจกับทุกโปรเจคว่าเป็น pattern นี้ → **ห้าม** เปลี่ยนเป็น Policy/Gate โดยไม่มีคำสั่ง
- API response: `{ status: bool, data: ... }` หรือ `{ status: false, message: '...' }` — ไม่มีข้อยกเว้น
- Sanctum: `auth('sanctum')->user()` — ไม่ใช้ `auth()->user()` ใน API controller
- Pagination: `limitOffset(request()->all())` (`App\Traits\Scopes`) — ไม่เรียก `paginate()` เอง
- Model หลักใช้ `SoftDeletes` → ใช้ `withTrashed()` / `onlyTrashed()` เมื่อต้องการ
- Validation แยกใน `app/Http/Requests/` — ไม่ validate inline ยาวๆ ใน controller
- API คืนค่าผ่าน `app/Http/Resources/` — อย่า return model ตรงๆ
- Enum → `app/Enums/` (backed enum) อย่าใช้ string ที่ไม่มี enum ครอบ
- Route group เสมอ: `['prefix' => x, 'as' => 'api.x.', 'middleware' => [...]]`

## หลักการทั่วไป (ยึดด้วยวินัย — ยังไม่มี automated gate)

- **No secret in code** — token/คีย์อยู่ใน `.env` เท่านั้น · `.env*` ถูก gitignore แล้ว
  ⚠️ `.claude/settings.local.json` มี API key จริง — ห้าม commit ห้าม paste ค่า
- **ภาษาไทยใน user-facing string** — notification, validation message, label
- **Error handling ชัด** — คืน `{ status: false, message }` พร้อม HTTP code ที่เหมาะสม
- **ข้อมูลอ่อนไหว** — ห้าม log PII/password/token เป็น plaintext (`storage/logs` เข้า git ไม่ได้แต่ก็ยังระวัง)
- **N+1 query** — ใช้ `with()` / `load()` เมื่อ loop ข้อมูลสัมพันธ์ · ถ้า query เยอะให้ `limitOffset`

## Naming

| สิ่ง                             | รูปแบบ                             | ตัวอย่าง                           |
| -------------------------------- | ---------------------------------- | ---------------------------------- |
| Class (Model/Controller/Request) | StudlyCase                         | `WorkBooking`, `MeController`      |
| Method / variable                | camelCase                          | `getWorkBookings()`, `$workId`     |
| Table / column                   | snake_case                         | `work_bookings`, `booking_status`  |
| Migration                        | `yyyy_mm_dd_create_xxx_table`            | `2024_10_25_create_posts_table`    |
| Route name                       | `api.<group>.<action>`             | `api.works.list`                   |
| Blade                            | kebab-case                         | `work-detail.blade.php`            |
| Enum case                        | StudlyCase พร้อม value เป็น string | `WorkStatus::StandBy = 'stand-by'` |

## Test conventions

- `tests/Feature/` — ทดสอบ endpoint/HTTP flow (ใช้ `RefreshDatabase`)
- `tests/Unit/` — ทดสอบ logic ล้วน
- PHPUnit ตั้งค่าไว้ใน `phpunit.xml` แล้ว (sqlite in-memory) — **ยัง coverage น้อยมาก**
- เขียนเทสต์เมื่อพี่สั่ง หรือแก้ business logic ที่ซับซ้อน (payment/status transition)

## Git & commit

- **ทำงานบน `develop` เท่านั้น** — `chaothuk` / `dev.chaothuk` เป็น deploy-trigger (ดู ADR-0002)
- commit เมื่อพี่สั่งเท่านั้น (กฎเหล็กใน AGENTS.md)
- conventional commits: `feat:`/`fix:`/`chore:`/`docs:`/`refactor:`/`test:` — **เนื้อความอังกฤษ**
- ปิดท้ายด้วย `Co-Authored-By: Claude Code <noreply@anthropic.com>`

## Doc/Decision

- กฎใหม่ที่ตกลงกัน → เติมที่นี่ + [conventions.md](../memory/core/conventions.md)
- ตัดสินใจใหญ่ (เปลี่ยน schema, เพิ่ม payment, เปลี่ยน pattern) → ADR ใน `decisions/` (`/new-adr`)
