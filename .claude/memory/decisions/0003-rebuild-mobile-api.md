---
name: 0003-rebuild-mobile-api
description: ADR — ทิ้ง REST API เดิมทั้งหมด เขียน /api/v1 ใหม่แบบ standard REST และสร้างแอป React Native + Expo (อ่านก่อนแตะ routes/api หรือแอป chaothuk-app)
metadata:
    node_type: memory
    type: decision
    status: active
    scope: api-mobile
    updated: 2026-10-06
    originSessionId: 5b7d9c3d-457b-4210-b417-b65a6aa4804d
    modified: 2026-10-06T14:55:12.047Z
---

# ADR-0003: รื้อ Mobile API และสร้างแอปใหม่

**สถานะ:** accepted · **วันที่:** 2026-10-06 · **ผู้เสนอ:** พี่

## บริบท

`chaothuk-laravel` เคยมี REST API สำหรับ mobile แต่ไม่เคยมีแอปจริงที่ deploy
(ยืนยันจากพี่ว่าไม่มีผู้ใช้) — พี่สั่งทิ้งทั้งหมดแล้วสร้างใหม่ โดย**เดินคู่กันไป**:
เขียนแอปก่อน แล้วค่อยเพิ่ม endpoint ตามที่แอปเรียกจริง

ตรวจโค้ดแล้วยืนยันว่าเหตุผลถูกต้องจริง ไม่ใช่แค่ความรู้สึก:

| ปัญหา                     | หลักฐาน                                                                         |
| ------------------------- | ------------------------------------------------------------------------------- |
| Response shape ไม่มีสัญญา | 18 Collection ไม่มี `$collects`                                                 |
| Error ทำนายไม่ได้         | `{status,data}` บางที่ · `{data,message}` บางที่ · message เป็นอังกฤษ           |
| ไม่มี pagination meta     | `limitOffset()` คืนแค่ array                                                    |
| ไม่มี version             | `/api/` ตรง ๆ เปลี่ยน schema เมื่อไรก็พังแอปที่อัปเดตแล้ว                       |
| Route พังจริง             | `GET /api/auth/customer-register-login` เรียก method ที่ไม่มี (500)             |
| ช่องโหว่                  | `/api/upload/*` ไม่มี auth · `/api/link-storage` เปิดให้ใครก็รัน `storage:link` |
| Logic ผิด                 | `GET /api/me/recruits` คืน `WorkCollection` · booking ไม่คืน id                 |
| ไม่มี rate limit          | `throttle` ถูก comment ออกตั้งแต่ตอนแรก                                         |

## การตัดสินใจ

1. **ลบ API เดิมทั้งหมด** — `routes/api.php` + `app/Http/Controllers/Api/*` (24 controller)
2. **เขียน `/api/v1` ใหม่** — REST มาตรฐาน: HTTP code บอกผล, body บอกข้อมูล, ไม่มี field `status`
3. **ตัดขอบเขตให้เหลือเท่าที่แอปต้องใช้** — 29 endpoint (auth · works · recruits ·
   bookings · reference data · uploads) ไม่เขียนส่วนที่แอปยังไม่เรียก
4. **แอป = React Native + Expo (SDK 57)** — เปลี่ยนจาก Flutter เพราะเครื่องมี Node 22
   อยู่แล้ว ไม่ต้องลง SDK ใหม่ · ได้ทั้ง iOS + Android จากโค้ดชุดเดียว
5. **สอง workspace** — `chaothuk-laravel` (backend) + `chaothuk-app` (mobile)
   ทำงานคู่กัน commit แยกที่
6. **แก้ช่องโหว่ที่เจอระหว่างทาง** — จองงานตัวเองไม่ได้, upload ต้อง auth +
   จำกัด mime, messenger ตรวจ participant, เปิด rate limit

## ผลกระทบ

### Laravel

- `routes/api/v1.php` — 29 route ใหม่
- `app/Http/Controllers/Api/V1/` — 6 controller
- `app/Http/Resources/Api/V1/` — resource ทุกตัวมี `$collects`
- `app/Http/Requests/Api/V1/` — validation + ข้อความไทย
- `app/Http/Controllers/Frontend/` — controller ที่ `ajax.php` ใช้ ย้ายจาก namespace Api
- หน้าเว็บ 7 ไฟล์เลิกยิง `/api/upload/*` → เรียก `UserImageService` ตรงผ่าน Livewire
- `phpunit.xml` → SQLite in-memory (ทำให้ test รันได้โดยไม่ต้องมี Sail)

### Mobile (`chaothuk-app`)

- `src/domain/` types ล้วน · `src/data/` API client + secure store · `src/presentation/` UI
- Expo Router: `src/app/` มีแค่ route file
- โครง hexagonal: presentation → data → domain
- หน้า: login · register · ฟีด · works · recruits · chat (placeholder) · me ·
  work detail + จอง · recruit detail + สมัคร · bookings · ลงประกาศ 2 แบบ

### ที่ยังไม่มี

Messenger · notifications · sessions/GPS · reviews · favorites · portfolio ·
availability · wallet · dispute · map · search · barcode · banner · issue points
— มีในเว็บอยู่แล้ว แต่ไม่มีใน `/api/v1` รอบนี้

## บันทึก

- โครง API เดิมยังอยู่ใน git history — `git log --diff-filter=D -- routes/api.php`
- เอกสารเก่าที่อ้างถึง `/api/*` ต้องอัปเดต (ดู `docs/README.md`)
- เครื่องพี่ยังไม่มี Android SDK → build Android ไม่ได้จนกว่าจะติดตั้ง
