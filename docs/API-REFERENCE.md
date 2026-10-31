# API Reference — Chaothuk (เช่าถูก)

### REST API สำหรับ Mobile App (Expo / React Native)

|                      |                                       |
| -------------------- | ------------------------------------- |
| **Base URL (local)** | `http://localhost:8000/api/v1`        |
| **Base URL (prod)**  | `https://{domain}/api/v1`             |
| **รวม endpoint**     | **29**                                |
| **Auth**             | Laravel Sanctum (Bearer token)        |
| **อัปเดต**           | 2026-10-06 · เขียนใหม่ทั้งหมดจากศูนย์ |

> 📌 API เดิม (103 endpoint, `/api/*`) ถูกลบทิ้งแล้วใน 2026-10 — ดูเหตุผลใน
> [ADR-0003](../.claude/memory/decisions/0003-rebuild-mobile-api.md)

---

## หลักการที่เปลี่ยนไปจาก API เดิม

| เรื่อง               | เดิม                                 | ใหม่                                                   |
| -------------------- | ------------------------------------ | ------------------------------------------------------ |
| **รูปแบบ response**  | `{status, data}` / `{data, message}` | REST มาตรฐาน — HTTP code บอกผล, body บอกข้อมูล         |
| **Version**          | ไม่มี                                | `/api/v1`                                              |
| **Pagination**       | `limitOffset()` ไม่มี meta           | `meta` เต็ม (current_page, last_page, total, per_page) |
| **Collection shape** | ไม่มี `$collects`                    | ทุก Collection มี `$collects`                          |
| **ข้อความ error**    | `'no permission'` (อังกฤษ)           | ภาษาไทยจาก FormRequest                                 |
| **Token**            | `my-app-token` (เดิมซ้ำกันหลายตัว)   | `mobile-app` + ลบ token เดียวตอน logout                |

---

## การยืนยันตัวตน

```http
Authorization: Bearer <sanctum_token>
```

Token ได้จาก `POST /api/v1/auth/login` หรือ `POST /api/v1/auth/register`

> ⚠️ **Rate limit**: 240 requests/minute ต่อ IP · เกินแล้วได้ `429`
> ⚠️ เก็บ token ไว้ใน `expo-secure-store` (keychain) ไม่ใช่ AsyncStorage

---

## Format การตอบกลับ

### สำเร็จ

```json
{
    "data": { "id": 1, "title": "รถกะบะรับจ้างขนส่ง" }
}
```

### รายการแบบ paginate

```json
{
    "data": [/* ... */],
    "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 8,
        "per_page": 15,
        "to": 15,
        "total": 73
    }
}
```

### ไม่สำเร็จ

```json
{
    "message": "กรุณาเข้าสู่ระบบ",
    "errors": { "email": ["อีเมลหรือรหัสผ่านไม่ถูกต้อง"] }
}
```

| Code  | หมายถึง             | จัดการในแอป                        |
| ----- | ------------------- | ---------------------------------- |
| `401` | token หมดอายุ/ไม่มี | ล้าง token → กลับไปหน้า login      |
| `403` | ไม่มีสิทธิ์         | แสดงข้อความ ปิดปุ่มที่เกี่ยวข้อง   |
| `404` | ไม่พบข้อมูล         | กลับไปหน้าก่อนหน้า                 |
| `422` | validation ไม่ผ่าน  | แสดง error ใต้ field ที่เกี่ยวข้อง |
| `429` | ยิงถี่เกินไป        | หน่วงแล้วลองใหม่                   |

---

## Endpoints

### Auth — `/api/v1/auth`

| Method | Path        | Auth | รายละเอียด                               |
| ------ | ----------- | ---- | ---------------------------------------- |
| `POST` | `/register` | —    | สมัครสมาชิก · คืน `user` + `token` · 201 |
| `POST` | `/login`    | —    | เข้าสู่ระบบ · คืน `user` + `token`       |
| `GET`  | `/me`       | ✅   | ข้อมูลผู้ใช้ปัจจุบัน + `permissions[]`   |
| `POST` | `/logout`   | ✅   | ลบ token ปัจจุบัน                        |

**Register body**

```json
{
    "name": "สมชาย",
    "email": "somchai@example.com",
    "password": "secret123",
    "password_confirmation": "secret123",
    "first_name": "สมชาย",
    "last_name": "ใจดี",
    "mobile_phone": "0812345678"
}
```

---

### Reference data (public) — ใช้สร้าง dropdown

| Method | Path          | คืนค่า                           |
| ------ | ------------- | -------------------------------- |
| `GET`  | `/provinces`  | `[{id, code, name_th, name_en}]` |
| `GET`  | `/work-types` | `[{id, title, image}]`           |
| `GET`  | `/categories` | `[{id, name, image}]`            |

---

### Works (ฉันทำงานนี้ได้) — `/api/v1/works`

| Method | Path             | Auth | รายละเอียด                                  |
| ------ | ---------------- | ---- | ------------------------------------------- |
| `GET`  | `/`              | —    | รายการ + pagination meta                    |
| `GET`  | `/{id}`          | —    | รายละเอียด (มี `categories[]`)              |
| `POST` | `/`              | ✅   | ลงประกาศ · ต้องมีสิทธิ์ `create_work` · 201 |
| `POST` | `/{id}/bookings` | ✅   | จองงาน · 201 · คืน booking พร้อม id         |

**Query ของ `GET /`**

| Param          | ชนิด   | หมายเหตุ                     |
| -------------- | ------ | ---------------------------- |
| `keyword`      | string | ค้นใน title/description/code |
| `province_id`  | int    |                              |
| `work_type_id` | int    |                              |
| `category_id`  | int    |                              |
| `min_price`    | number |                              |
| `max_price`    | number |                              |
| `page`         | int    |                              |
| `per_page`     | int    | 1–50 · default 15            |

> ❌ จองประกาศของตัวเองไม่ได้ (403) — ของเดิมไม่เช็ค

**Create body**

```json
{
    "code": "กก-1234",
    "title": "รถกะบะรับจ้างขนส่ง",
    "description": "บริการขนส่งทั่วกรุงเทพ...",
    "price": 1500,
    "province_id": 1,
    "work_type_id": 2,
    "categories": [1, 3],
    "latitude": 13.7563,
    "longitude": 100.5018
}
```

> `author_id` มาจาก token เสมอ · รับจาก body ไม่ได้

**Booking body**

```json
{
    "booking_date": "2026-10-10",
    "customer_message": "ต้องการขนของย้ายบ้าน",
    "mobile_phone": "0812345678"
}
```

> `booking_date` = **`YYYY-MM-DD`** เท่านั้น · เอกสารเดิมเขียน datetime ทำให้ลูกค้า error

---

### Recruits (ฉันต้องการคนมาทำ) — `/api/v1/recruits`

| Method | Path             | Auth | รายละเอียด                               |
| ------ | ---------------- | ---- | ---------------------------------------- |
| `GET`  | `/`              | —    | รายการ (ใช้ `min_budget`/`max_budget`)   |
| `GET`  | `/{id}`          | —    | รายละเอียด                               |
| `POST` | `/`              | ✅   | ลงประกาศ · ต้องมี `create_recruit` · 201 |
| `POST` | `/{id}/bookings` | ✅   | สมัครงาน · 201 · คืน booking พร้อม id    |

> ❌ สมัครประกาศของตัวเองไม่ได้ (403)

---

### Work bookings — `/api/v1/work-bookings`

| Method | Path                        | Auth | รายละเอียด                      |
| ------ | --------------------------- | ---- | ------------------------------- |
| `GET`  | `/`                         | ✅   | ที่ฉันจอง (ฝั่งลูกค้า)          |
| `GET`  | `/as-owner`                 | ✅   | ที่มาจองประกาศของฉัน (ฝั่งคนทำ) |
| `GET`  | `/{id}`                     | ✅   | ต้องเป็นคู่ของ booking          |
| `POST` | `/{id}/confirm`             | ✅   | **เจ้าของประกาศ** ยืนยัน        |
| `POST` | `/{id}/confirm-as-customer` | ✅   | **ผู้จอง** ยืนยัน               |
| `POST` | `/{id}/cancel`              | ✅   | ทั้งสองฝั่งยกเลิกได้            |

**การเปลี่ยนสถานะ** — `waiting-to-confirm` → ยืนยันทั้งสองฝั่ง → `confirm`

---

### Recruit bookings — `/api/v1/recruit-bookings`

| Method | Path                         | Auth | รายละเอียด                   |
| ------ | ---------------------------- | ---- | ---------------------------- |
| `GET`  | `/`                          | ✅   | ที่ฉันสมัคร                  |
| `GET`  | `/as-employer`               | ✅   | ที่มาสมัครประกาศของฉัน       |
| `GET`  | `/{id}`                      | ✅   |                              |
| `POST` | `/{id}/confirm`              | ✅   | **ผู้ว่าจ้าง** เลือกผู้สมัคร |
| `POST` | `/{id}/confirm-as-applicant` | ✅   | **ผู้สมัคร** ยืนยัน          |
| `POST` | `/{id}/cancel`               | ✅   |                              |

---

### Uploads — `/api/v1/uploads`

| Method | Path      | Auth | รายละเอียด                                     |
| ------ | --------- | ---- | ---------------------------------------------- |
| `POST` | `/image`  | ✅   | `multipart` field `image` · JPG/PNG/WebP ≤10MB |
| `POST` | `/avatar` | ✅   | field `avatar` · บันทึกลง profile แล้ว         |

> 🔒 **ปิด auth แล้ว** — ของเดิมเปิดให้ใครก็อัปโหลดได้ และไม่จำกัด mime

### Favorites (บุ๊คมาร์ค) — `/api/v1/favorites`

| Method | Path                    | Auth | รายละเอียด                                                              |
| ------ | ----------------------- | ---- | ----------------------------------------------------------------------- |
| `GET`  | `/`                     | ✅   | รายการโปรดของผู้ใช้ (ใหม่สุดก่อน) · `?type=work\|recruit` · `?per_page=` · `favoritable` เป็น card เต็มแบบ marketplace |
| `GET`  | `/check`                | ✅   | `?type=work&favoritable_id=1` → `{ data: { favorited: true } }` สำหรับหัวใจบนหน้า detail |
| `POST` | `/`                     | ✅   | body `{ type: "work"\|"recruit", id }` → 201 · บันทึกซ้ำ = no-op (ไม่ error) |
| `DELETE` | `/{favorite}`          | ✅   | ลบรายการโปรดของตัวเอง · ลบซ้ำได้ (ไม่ 404) · ลบของคนอื่น = 403            |

> ❤️ ตอนก่อนอยู่แค่หน้าเว็บ (Livewire) — model `Favorite` ใช้ morph `favoritable` อยู่แล้ว

---

### Reviews + ตอบรีวิว

| Method | Path                       | Auth | รายละเอียด                                                                                |
| ------ | -------------------------- | ---- | ----------------------------------------------------------------------------------------- |
| `GET`  | `/works/{work}/reviews`    | ❌   | รีวิวของหนึ่งประกาศ (ใหม่สุดก่อน) · reply ซ้อนใต้ตัวใน `replies` ลึกเท่าที่มี · for public |
| `GET`  | `/recruits/{recruit}/reviews` | ❌ | เหมือน works — shape เดียวกัน                                                             |
| `POST` | `/works/{work}/reviews`    | ✅   | body `{ title?, rating 1–5, content ≥5 }` → 201 · ต้องมีสิทธิ์ `review_work`              |
| `POST` | `/recruits/{recruit}/reviews` | ✅ | เหมือน works                                                                             |
| `POST` | `/reviews/{post}/replies`  | ✅   | body `{ content ≥2 }` · ตอบได้เฉพาะ**รีวิวหลัก** · ต้องมีสิทธิ์ `reply_review`            |

> ⭐ รีวิว/reply เป็น `posts` หนึ่งต้นไม้ (`parent_id`) ผูกประกาศผ่าน `works_reviews`/`recruits_reviews` — เจ้าของประกาศได้ notification `work-review` · ผู้รีวิวได้ `review-reply` (ไม่แจ้งตัวเอง) พร้อม notificationable ชี้ที่ประกาศเพื่อ route ไปหน้าจริง

### Sessions (เซสชันการทำงาน + GPS) — `/api/v1/sessions`

| Method | Path                                | Auth | รายละเอียด                                                                              |
| ------ | ----------------------------------- | ---- | ---------------------------------------------------------------------------------------- |
| `GET`  | `/`                                 | ✅   | เซสชันของผู้เรียก (worker หรือ customer) · `?status=active\|paused\|completed\|cancelled` |
| `POST` | `/work-bookings/{workBooking}/start` | ✅   | เจ้าของประกาศเริ่มเซสชันจากการจองที่**ยืนยันแล้ว** · body `{ price_agreed? }` · ห้ามซ้ำระหว่าง active |
| `GET`  | `/{session}`                        | ✅   | เฉพาะสองฝั่งในเซสชัน · รวม trail พิกัด (200 จุดล่าสุด · เก่า→ใหม่)                        |
| `POST` | `/{session}/pause` · `/resume` · `/stop` | ✅ | transition ตามเว็บ (pause จาก active · resume จาก paused · stop คำนวณระยะเวลา)          |
| `POST` | `/{session}/confirm`                | ✅   | ยืนยันฝั่งที่เรียก (`worker_confirm`/`customer_confirm`)                                 |
| `POST` | `/{session}/locations`              | ✅   | drop GPS ping — body `{ latitude, longitude, accuracy?, speed?, heading?, recorded_at? }` · เฉพาะ status active |

> 📍 เว็บเริ่มเซสชันได้จาก work booking เท่านั้น (worker = เจ้าของงาน, customer = คนจอง) — recruiter ยังไม่มี flow ผู้เริ่ม (ผู้เริ่มได้ `worker_confirm = confirmed`)

---

### Portfolio (ผลงานของผู้ให้บริการ) — `/api/v1/portfolios`

| Method | Path | Auth | รายละเอียด |
| ------ | ---- | ---- | ----------- |
| `GET` | `/` | ✅ | ผลงานของผู้เรียกเอง (latest first · รวม work_type) |
| `POST` | `/` | ✅ | เพิ่มผลงาน — body `{ title, description, work_type_id, images: [url…] }` · validate เหมือนเว็บ (ชื่อ ≥3 · รายละเอียด ≥5 · images ≥1) |
| `GET` | `/{portfolio}` | ❌ public | ดูผลงานของใครก็ได้ — รวม owner รูปจาก path → URL |
| `PUT` | `/{portfolio}` | ✅ | แก้ไข **ของตัวเองเท่านั้น** |
| `DELETE` | `/{portfolio}` | ✅ | ลบของตัวเอง (ลบซ้ำ no-op · ของคนอื่น 403) |

> 📘 รูปใน `images` คือ path/URL ที่ได้จาก `POST /uploads/image` แหล่งเดียวกับประกาศ ผู้ใช้งานไม่ต้องแก้ URL ด้วยเอง

---

### Messenger (แชทจากการจอง) — `/api/v1/messenger`

| Method | Path | Auth | รายละเอียด |
| ------ | ---- | ---- | ----------- |
| `GET` | `/channels` | ✅ | ห้องของผู้เรียก (latest first · `?per_page=`) — แต่ละห้องมี `counterpart` · `last_message` · **`unread_count`** (ข้อความคู่สนทนาที่ยังไม่ seen) |
| `GET` | `/unread` | ✅ | **ยอดรวม unread ทุกห้อง** — `{ data: { unread_count } }` สำหรับ poll ทำ badge tab แชท |
| `POST` | `/work-bookings/{workBooking}/channel` | ✅ | เปิด/ดึงห้องของการจองงาน (ผู้จอง/เจ้าของงาน) |
| `POST` | `/recruit-bookings/{recruitBooking}/channel` | ✅ | เปิด/ดึงห้องของการสมัคร (ผู้สมัคร/ผู้ประกาศ) |
| `GET` | `/channels/{channel}` | ✅ | รายละเอียดห้อง (participant-only · รวม `unread_count`) |
| `GET` | `/channels/{channel}/messages` | ✅ | ข้อความเรียงเก่า→ใหม่ (`?after_id=` สำหรับ poll · `?per_page=` ≤200) |
| `POST` | `/channels/{channel}/messages` | ✅ | ส่งข้อความ `{ content, type?, local_code_id? }` — จะ push หาอุปกรณ์คู่สนทนาที่ลงทะเบียนไว้ |
| `POST` | `/channels/{channel}/seen` | ✅ | mark seen ข้อความของคู่สนทนาในห้อง |

> 🔒 ทุกเส้นตรวจผู้เรียกต่อ participants ของห้องก่อนอ่าน/เขียน — เดา id ไม่ได้

### Availability (ตารางว่างของผู้ให้บริการ) — `/api/v1/works/{work}/availability`

| Method | Path | Auth | รายละเอียด |
| ------ | ---- | ---- | ----------- |
| `GET` | `/` | ✅ | ตารางเต็ม 7 วัน (0=อาทิตย์…6=เสาร์) + blocked dates ตั้งแต่วันนี้ — วันที่ไม่เคยตั้งค่าจะคืนเป็น inactive default 08:00–17:00 (เหมือนฟอร์มเว็บ) |
| `PUT` | `/` | ✅ | **แทน schedule ทั้งสัปดาห์ทีเดียว** — body `{ schedule: [{ day_of_week 0-6, start_time 'H:i', end_time 'H:i', is_available }] }` · วันที่ไม่ส่งมา = ลบ row |
| `POST` | `/blocked-dates` | ✅ | เพิ่มวันหยุด — body `{ blocked_date (>= วันนี้), reason? }` · วันซ้ำ = ใช้ row เดิม (ครั้งแรก 201 · ซ้ำ 200) |
| `DELETE` | `/blocked-dates/{id}` | ✅ | ลบวันหยุดของประกาศนี้ (ของ work อื่น = ไม่มีผล) |

> 🔒 ทุกเส้นต้องเป็น **เจ้าของประกาศ** (`work.author_id` มาจาก token) — mirror ฟอร์ม Livewire `WorkAvailabilityManage` ของเว็บ

### Wallet (กระเป๋าเงินของฉัน) — `/api/v1/wallet`

| Method | Path | Auth | รายละเอียด |
| ------ | ---- | ---- | ----------- |
| `GET` | `/` | ✅ | ยอดเงินของผู้เรียก — `{ data: { balance, status } }` (ไม่มี wallet = 0.00 · status active/suspended) |
| `GET` | `/transactions` | ✅ | รายการเดินบัญชีของผู้เรียก newest first (`?per_page=` ≤50 · paginated) — `{ type: deposit\|payment\|refund\|withdrawal\|adjustment, amount (ลบ = เงินออก), balance_after, description, created_at }` |

> 💰 **อ่านอย่างเดียว** — เงินเดินตาม flow งานจริง (โปรโมท, คืนเงิน, ปรับปรุงโดย admin) และทุกการเคลื่อนไหวมี ledger row อยู่แล้ว · ไม่มี endpoint เติมเงิน

### แผนที่ (Map pins) — `/api/v1/map`

| Method | Path | Auth | รายละเอียด |
| ------ | ---- | ---- | ----------- |
| `GET` | `/` | ❌ public | หมุดทั้งสองฝั่งที่มีพิกัด ครั้งเดียว (`?kind=work\|recruit` กรอง) — works เฉพาะ `stand-by` + recruits เฉพาะ `stand-by` จำกัด 500 ต่อฝั่ง เรียง id ใหม่ → เก่า — pin `{ id, title, price, image, lat, lng, province, type, rating }` · tap หมุดแล้วแอปเดินไปหน้าประกาศ |

> 🗺 mirror หน้า `/map` ของเว็บ (MapExplore) — การปิด/จอง/แก้สถานะทำให้หมุดหายอัตโนมัติเพราะ query ผ่านสถานะปัจจุบันทุกครั้ง

### Push notifications (อุปกรณ์ + Expo push) — `/api/v1/push-tokens`

| Method | Path | Auth | รายละเอียด |
| ------ | ---- | ---- | ----------- |
| `POST` | `/` | ✅ | ลงทะเบียน push token ของอุปกรณ์ตัวเอง — body `{ token, platform?: android|ios|web }` · token เดิม = update ไม่ซ้ำ |

> 📲 ตอนนี้ระบบ push ข้อความแชท (Expo push) — อุปกรณ์ที่ไม่มี token ก็ใช้แอปได้ตามปกติ (นับเป็น opt-in) · การส่งล้มเหลวไม่กระทบการส่งข้อความ

---

## สิ่งที่ยังไม่มีใน v1

เพราะแอปรอบนี้ยังไม่เรียกใช้ — รอบถัดไปค่อยเพิ่ม:

- 💬 Messenger / แชท (มีแล้ว — `/messenger` + unread badge)
- 🔔 Notifications (แบบราย user) (มีแล้ว — `/notifications` + push token สำหรับแชท)
- 💼 Portfolio (มีแล้ว — `/portfolios`) · 📅 Availability (มีแล้ว — `/works/{id}/availability`) · 💰 Wallet (อ่านอย่างเดียว — `/wallet`) · ⚖️ Dispute (admin-only บนเว็บ — ไม่มี user-facing flow)
- 🗺 Map search (มีแล้ว — `/map`) · 🔍 Search endpoint แยก · 🏆 Featured works · 📊 Province top
- 🎫 Barcode / QR · 🖼 Banner · 🎯 IssuePoints · 📝 Activity log

> ทั้งหมดยัง**มีอยู่ในเว็บ** — ดู [SRS.md](SRS.md) ภาคผนวก ก

---

_จัดทำโดย ซาน่า 🌈 · ตรวจจาก `php artisan route:list` + feature test · 6 ต.ค. 2026_
