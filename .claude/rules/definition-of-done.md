---
paths:
    - "app/**"
    - "routes/**"
    - "database/**"
    - "resources/views/**"
    - "config/**"
    - "tests/**"
---

# Definition of Done — เช็คก่อนบอกว่า "เสร็จ" (ทุก session / ทุก AI)

> **กฎข้อเดียวที่สำคัญสุด:** ห้ามบอกพี่ว่างาน "เสร็จ" จนกว่าจะผ่าน checklist นี้ครบ —
> ไม่ว่าจะ session ไหน, AI ตัวไหน, มี persona ซาน่า หรือไม่. นี่คือมาตรฐานตายตัว ไม่ใช่ทางเลือก.

## ✅ Checklist ก่อนปิดงาน (เรียงตามลำดับ)

### 1. Gate เขียวครบ — **บังคับเสมอ**

```bash
./vendor/bin/pint --test        # code style (ถ้า fail → ./vendor/bin/pint แล้วรันซ้ำ)
./vendor/bin/phpunit            # test ทั้งหมด
```

- ต้องเขียวทั้งสองตัว
- แตะเฉพาะ Blade/markdown → `./vendor/bin/pint --test` พอ (ไม่ต้องรันทั้งเซ็ต)
- ❗ **ไม่มี static analysis** (PHPStan/Psalm) และ **ไม่มี CI** — ถ้างานเสี่ยง (เปลี่ยน schema, auth, payment)
  ให้บอกพี่ตรงๆ ว่ายังไม่มี gate ครอบ แล้วเสนอเปิด PHPStan + CI

### 2. Test ครอบของใหม่ — **บังคับเมื่อเพิ่ม/แก้ logic**

- logic กลาง (service/helper/status transition) → unit test ครอบทุก branch ใหม่
- endpoint ใหม่ → feature test (`tests/Feature/`) ยิงจริงผ่าน HTTP
- adapter/Resource → contract test เทียบ payload กับ Resource ที่ใช้จริง
- ทุก behavior ใหม่ต้องมี test ยืนยัน — ไม่ใช่แค่ "ไม่มี error"

> ⚠️ โปรเจคนี้ coverage น้อยมาก (PHPUnit มีแต่ test ไม่กี่ตัว) — ถ้าพี่สั่งให้เขียน test ต้องเขียนเองได้
> แต่อย่าเพิ่ม test ที่ไม่ได้รันใน workflow ใด

### 3. Verify บน real-flow — **บังคับเมื่อแตะ flow ที่ผู้ใช้เห็น / DB**

ถ้างานแตะ flow ที่ผู้ใช้เห็น, หรือเขียน/อ่าน DB จริง:

```bash
sail up -d                              # stack ต้อง up
sail php artisan migrate
sail yarn dev                           # ถ้าแตะ Blade/Vite
# แล้วเปิดจริง — ยิง endpoint / เปิดหน้า / เช็ค DB
```

- ต้อง**เห็นผลจริง** (พิสูจน์บนจอ + ใน DB จริง) ไม่ใช่แค่ "รันผ่าน"
- ⚠️ **stack ไม่ up / ยิงไม่ได้** → **ห้ามเคลมว่า "เสร็จ/พิสูจน์แล้ว"** ·
  ต้องแจ้งพี่ตรงๆ ว่า "เขียนโค้ดเสร็จ + test เขียว แต่ยังไม่ได้ verify บนจอจริงเพราะ stack ไม่ up"
  พร้อมบอกคำสั่งที่พี่ต้องรันเอง
- งาน doc/memory/markdown ล้วน → ข้อ 3 ไม่บังคับ (แต่ต้องอ่าน output จริงว่าไม่มี placeholder/อักขระเสีย)

### 4. Commit สะอาด

- **อยู่บน `develop`** — ห้าม commit บน `chaothuk` / `dev.chaothuk` (ดู ADR-0002)
- conventional message: `feat:`/`fix:`/`docs:`/`chore:`/`refactor:`/`test:` — **เนื้อความอังกฤษ**
- body ≤100 char/บรรทัด · งานใหญ่ commit ทีละ increment ที่เขียว ไม่กองรวมก้อนเดียว
- ปิดท้ายด้วย `Co-Authored-By: Claude Code <noreply@anthropic.com>`
- **ห้าม** secret/คีย์หลุด — `.env*` และ `.claude/settings.local.json` ถูก gitignore แล้ว
- ⚠️ commit เมื่อพี่สั่งเท่านั้น (กฎเหล็กใน AGENTS.md)

### 5. รายงานตรง (ห้าม overclaim)

บอกชัด: **ผ่านอะไร / ข้ามอะไร / เหลืออะไร**

- test fail → บอกตรง พร้อม output
- verify บนจริงไม่ได้ → บอกตรงว่าไม่ได้ทำ (ดูข้อ 3)
- "เสร็จและพิสูจน์แล้ว" = ผ่านข้อ 1-3 **จริง** เท่านั้น

## ✅ เพิ่มเติมเฉพาะโปรเจคนี้

- **แก้ path รูป** → ใช้ `image_url()` helper เสมอ อย่าต่อ path เอง
  (เคยมีบั๊จ double-prefix `storage/` และ path ที่เก็บไม่ normalize — เกิดจริงแล้ว)
- **แก้ seeder** → อย่า override `work_type_id` ทั้งที่ sequence กำหนดไว้แล้ว
  และให้ `title` ของ work ตรงกับ vehicle type เสมอ
- **เพิ่ม migration** → คิดเรื่อง index, foreign key, `softDeletes` ให้ครบก่อนรัน
  บน production (ดู roadmap ใน [[project-overview]])

## สรุปสั้น (จำ 1 บรรทัด)

> **pint + phpunit เขียว → test ครอบของใหม่ → verify บนจริง (sail up) ถ้าแตะ flow → commit บน develop เมื่อพี่สั่ง → รายงานตรง**

ดูมาตรฐานโค้ดเต็ม: [code-standards.md](code-standards.md)
