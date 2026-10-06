---
description: สร้าง memory spec ของ feature/โมดูลใหม่ใน Chaothuk (works, recruits, bookings, points, messenger ฯลฯ)
argument-hint: "[ชื่อ feature เช่น payments, portfolios, work-packages]"
---

สร้าง memory spec สำหรับ feature/โมดูลใหม่ตาม convention ใน `.claude/memory/MEMORY-GUIDE.md`
ชื่อ: **$ARGUMENTS**

> ระบบนี้เป็น marketplace — ถ้าพี่ตั้งชื่อใหม่ให้ map เข้ากลุ่มที่มีอยู่ก่อน
> (`works`, `recruits`, `bookings`, `points`, `reputation`, `messenger`, `geography`, `notifications`)
> หรือเป็น feature ใหม่จริงๆ (เช่น `payments`, `disputes`, `favorites`) → เก็บแยก

ทำตามขั้นตอนนี้:

1. แปลงชื่อเป็น kebab-case → สร้างไฟล์ `.claude/memory/modules/<slug>.md`
2. frontmatter มาตรฐาน (`type: module`, `status: active`, `scope: <slug>`, `updated` = วันนี้)
3. โครงเนื้อหา spec (เว้นที่ให้เติม) — **ปรับหัวข้อให้ตรงกับ feature นั้น**:
    - **หน้าที่/ขอบเขต** — feature นี้ทำอะไร ไม่ทำอะไร
    - **Data** — migration/model/table ที่เกี่ยวข้อง (ชี้ชื่อจริงใน `database/migrations/` + `app/Models/`)
    - **Interface** — API routes (`routes/api.php`) · controller (`app/Http/Controllers/Api/`) · Request/Resource
    - **Frontend** — Livewire component หรือ Blade view ที่เกี่ยวข้อง
    - **Dependency** — ต้องพึ่งอะไรบ้าง (เช่น payments → WorkBooking + RecruitBooking + User)
    - **ปม technical ที่ต้องระวัง** — soft deletes, enum ที่ใช้, permission ที่ต้องเช็ก, ภาษาไทยใน user-facing string
    - **Test ที่ต้องมี**
    - ถ้ายังไม่รู้รายละเอียด → ถามพี่ทีละจุด หรือใส่ TODO ไว้
4. เพิ่ม pointer ใน section "Modules" ของ `.claude/memory/MEMORY.md`
5. ถ้าจะลงมือเขียนโค้ด → ถามพี่ก่อนว่าจะเริ่มเขียน migration / model / api เลยไหม

> 🔴 หมายเหตุ: feature ใหม่ที่กระทบ **payment, wallet, dispute** ต้องเขียน ADR ด้วย (`/new-adr`)
> เพราะกระทบทั้ง schema และ flow การเงิน — คิดถึงเรื่องภาษี/PDPA/พร.ร.ด้วย
