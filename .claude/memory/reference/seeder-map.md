---
name: seeder-map
description: แผนที่ seeder ทั้งหมดของ Chaothuk — อะไรทำอะไร, ลำดับ, ENV ที่ใช้ (อ่านก่อน seed dev/prod หรือเพิ่มข้อมูลตัวอย่าง)
metadata:
    type: reference
    status: active
    scope: database
    updated: 2026-10-06
---

# Seeder Map — Chaothuk

ทั้งหมดอยู่ที่ `database/seeders/` — 16 ไฟล์ = **15 seeder** + `DatabaseSeeder` (ตัว orchestrator)
อ่านไฟล์ตัวเองก่อนแก้

## ลำดับการรัน — `DatabaseSeeder::run()` เรียกจริง 10 ตัว

```
1. GeographySeeder     → ภูมิภาค
2. ProvinceSeeder      → 77 จังหวัด (name_th/name_en)
3. DistrictSeeder      → อำเภอ
4. SubDistrictSeeder   → ตำบล

5. PermissionSeeder    → permissions
6. RoleSeeder          → roles (admin/user)

7. UserSeeder          → ผู้ใช้ระบบ
8. ConfigurationSeeder → ค่า config key-value

9. WorkTypeSeeder      → ประเภทงาน (รถรับจ้าง, ช่างไฟฟ้า ฯลฯ)
10. CategorySeeder     → หมวดหมู่ (ใช้ร่วม works + recruits)
```

> ⚠️ **`NotificationSeeder` และ `ReputationSeeder` มีไฟล์แต่ไม่ถูก `DatabaseSeeder` เรียก**
> ถ้าต้องการข้อมูลชุดนี้ → รันแยว: `sail php artisan db:seed --class=NotificationSeeder`

## Seeder เฉพาะทาง

| Seeder               | ประเภท      | ทำอะไร                                                                    | ENV              |
| -------------------- | ----------- | ------------------------------------------------------------------------- | ---------------- |
| `DatabaseSeeder`     | Master data | เรียก 10 ตัวข้างบนตามลำดับ                                                | ทุก env          |
| `StarterSeeder`      | Starter     | Master/reference data (WorkType, Category)                                | ทุก env        |
| `DemoSeeder`         | Demo        | ผู้ใช้ 4 คน + works/recruits ตัวอย่าง                                     | dev, staging     |
| `MockSeeder`         | Volume      | 50 users · 100 works · 450 posts · 500 notifications                      | **dev เท่านั้น** |
| `NotificationSeeder` | ระบบ        | ข้อมูล notification เริ่มต้น — **ไม่ถูก DatabaseSeeder เรียก**            | รันแยว           |
| `ReputationSeeder`   | Reputation  | user_reputations, badges, verifications — **ไม่ถูก DatabaseSeeder เรียก** | รันแยว           |

> ⚠️ **`MockSeeder` เรียก `DemoSeeder` อัตโนมัติ** (บรรทัด 65) — ไม่ต้องรัน Demo แยก
> ⚠️ `MockSeeder` ท้ายสุดยังเรียก `top-works:calculate` ด้วย

## คำสั่งที่ใช้บ่อย

```bash
sail php artisan migrate                      # migrate
sail php artisan migrate:fresh --seed         # ล้าง + seed master data
sail php artisan db:seed --class=MockSeeder   # seed ข้อมูลเยอะ (dev)
sail php artisan db:seed --class=DemoSeeder   # seed ข้อมูล demo
sail php artisan db:seed --class=NotificationSeeder   # notification เริ่มต้น
```

## Console commands ที่เกี่ยว

| Command                                       | หน้าที่                                                        |
| --------------------------------------------- | -------------------------------------------------------------- |
| `php artisan schedule:run`                    | รัน cron ตามตาราง                                              |
| `php artisan schedule:list`                   | ดูตารางงานทั้งหมด                                              |
| `top-works:calculate`                         | คำนวณงานเด่นประจำจังหวัด (MockSeeder เรียกให้)                 |
| `app/Console/Commands/GenerateMockImages.php` | สร้าง mock image (ComfyUI / flux-schnell) → `/database/images` |
| `DailyIssuePoint` · `EveryMinuteIssuePoint`   | จ่ายแต้มตาม `issue_points` rules                               |

## ⚠️ ข้อควรระวัง

- **MockSeeder ห้ามรันบน production** — มี guard ในไฟล์แล้ว แต่อย่า override
- **work_type_id ต้องตรงกับ `title`** — มี bug เกิดจริงแล้ว: seeder override `work_type_id`
  ทั้งที่ sequence กำหนดไว้แล้ว → ระวังตอนแก้ MockSeeder
- ข้อมูล mock images อยู่ที่ `/database/images` ซึ่งถูก gitignore — generate ใหม่ทุกครั้งที่ clone ใหม่
- ❌ **`ProductionSeeder` ไม่มีไฟล์ในโปรเจค** — `DEV-DOC.md` อ้างถึงแต่ไม่ได้สร้างจริง
  ถ้าจะ seed admin บน production → ใช้ `UserSeeder` หรือสร้างเอง

ดู [[project-overview]] · [[conventions]]
