# Handoff — 2026-10-07

> ไฟล์นี้คือ **บันทึกสถานะ + ตัวเลือกงานถัดไป** เขียนให้ session ใหม่ (หรือพี่) อ่านแล้วเลือกงานจากที่นี่ได้เลย
> อัปเดตล่าสุด: 2026-10-07 หลัง commit `0bee04e`

---

## 1. เพิ่งทำเสร็จ: ปิดช่องโหว่ Messenger

**3 commit บน `develop`** (ยังไม่ push)

| Commit    | เนื้อหา                                                                |
| --------- | ---------------------------------------------------------------------- |
| `054e8fa` | `fix:` ปิดช่องโหว่ + ลบ guest flow + แก้บั๊จ `user()` relation         |
| `534b858` | `perf:` index ตาราง messenger + `content` → `text` + unique constraint |
| `0bee04e` | `test:` เทสต์กัน regression 8 ตัว                                      |

### ช่องโหว่ที่ปิด

| #   | ปัญหา                                             | วิธีแก้                                                      |
| --- | ------------------------------------------------- | ------------------------------------------------------------ |
| 1   | `Messenger::openChannel()` เปิดแชทใครก็ได้ด้วย id | เช็ค `messenger_participants` ก่อน render                    |
| 2   | `sendMessage()` เขียนข้ามห้องได้                  | เช็คซ้ำที่ write path (`$activeChannelId` เป็น client state) |
| 3   | `/ajax/messenger/*` ไม่มี auth                    | ลบทั้งชุด                                                    |
| 4   | `password = Hash::make($mobilePhone)`             | ลบ `User::getOrCreateMobilePhoneUser()`                      |
| 5   | `content` = `string(255)` ตัดข้อความยาว           | เปลี่ยนเป็น `text`                                           |
| 6   | ตาราง messenger ไม่มี index เลย                   | เพิ่ม 4 index + unique `(channel_id, user_id)`               |
| 7   | เบอร์โทร (ตัว credential) อยู่ใน URL              | ลบ guest flow ทั้งชุด                                        |

### บั๊จที่เจอระหว่างทาง (แก้แล้ว)

`MessengerConversation` ไม่มี relation `user()` แต่ [Messenger.php:83](app/Livewire/Frontend/Messenger.php#L83) เรียก `with(['user'])`
→ **หน้าแชทพังทุกครั้งที่เปิดห้องที่มีข้อความ** (`RelationNotFoundException`) แก้เป็น `author()` ซึ่งมีจริง

### ⚠️ Migration ต้องรันบน VPS

`database/migrations/2026_10_07_000001_harden_messenger_tables.php` — รันไปแล้วบน local MySQL
แต่ **ยังไม่ได้รันบน dev/prod** (workflow มี `artisan migrate --force` อยู่แล้ว พี่ push แล้วมันจะรันเอง)

### สิ่งที่ยังไม่ได้แก้ (ตั้งใจไม่แตะ)

| เรื่อง                            | เหตุผล                                                                                     |
| --------------------------------- | ------------------------------------------------------------------------------------------ |
| Admin messenger ยังอ่านทุกห้องได้ | อยู่หลัง `auth` + `IsCanAccessBackend` คือ moderation surface — **ถ้าพี่อยากจำกัดต้องบอก** |
| `Role::MOBILE_PHONE` ยังอยู่      | เก็บเผื่อทำ guest flow ใหม่ (ของเดิมถูกลบแล้ว ต้องออกแบบใหม่)                              |
| `MessengerChannel.is_public`      | dead column — ไม่มีโค้ดไหนอ่าน                                                             |

---

## 2. สถานะระบบ ณ ตอนนี้ (verified 2026-10-07)

### ✅ แข็งแล้ว

| อย่าง                    | ตัวเลข                                                                 |
| ------------------------ | ---------------------------------------------------------------------- |
| `/api/v1`                | 29 route · 6 controller                                                |
| เทสต์                    | **50 ผ่าน** (303 assertions, ~1.7s) — SQLite in-memory                 |
| Pint                     | ผ่าน (เฉพาะไฟล์ที่แก้ — ทั้งโปรเจคยังมี issue เก่าอยู่)                |
| แอปมือถือ `chaothuk-app` | 34 ไฟล์ · `tsc --noEmit` ผ่าน · hexagonal (`domain/data/presentation`) |
| Docs                     | SRS · API-REFERENCE · DATA-MODEL (63 ตาราง)                            |

### ⚠️ เอกสาร `.agent/ai_workspace_context.md` ล้าสมัย — หนูเช็คแล้ว

รายการที่เอกสารระบุว่า "เป็นบั๊จ" **แต่จริงๆ ถูกแก้ไปแล้ว**:

| เอกสารบอกว่า                                                    | ความจริง                                                                                                             |
| --------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `SessionController` ทุก method ไม่เช็คสิทธิ์                    | [SessionDetail.php:22-27](app/Livewire/Frontend/SessionDetail.php#L22-L27) scope ด้วย `worker_id`/`customer_id` แล้ว |
| `NotificationController::list` อ่านทั้งระบบ                     | [Notifications.php:21,31,38](app/Livewire/Frontend/Notifications.php#L21) scope `author_id = auth()->id()` ทุก query |
| `/ajax/upload/*` ไม่มี auth                                     | [ajax.php](routes/ajax.php) มี `middleware: ['auth']` แล้ว                                                           |
| `MessengerController::getChannelConversations` อ่านแชทคนอื่นได้ | ลบไปแล้ว + `openChannel()` ที่ใช้จริงก็เช็คแล้ว                                                                      |

> ⚠️ **อย่าเชื่อรายการในหัวข้อ "⚠️ บั๊จที่ทำให้ระบบพัง" ของ `ai_workspace_context.md`**
> ต้อง verify โค้ดจริงก่อนเสมอ — หนูเจอว่าล้าสมัย 4 ข้อจาก 14

---

## 3. 🎯 ตัวเลือกงานถัดไป — พี่เลือกเองได้

### A. CI + quality gate ⭐ เร็ว ต้นทุนต่ำ

`.github/workflows/` มีแต่ `deploy-dev.yml` + `deploy-prod.yml` — **ไม่มีอันไหนรันเทสต์หรือ lint เลย**
50 เทสต์นี้รันแค่บนเครื่องพี่คนเดียว วันหนึ่งทีไปท่านอื่น push มาพังเงียบๆ

- workflow เดียว: `php artisan test` + Pint + `npm run typecheck` (แอป)
- ทำในโปรเจค backend ได้เลย · งานเล็ก ~1-2 ชม.
- **ข้อดี**: กัน regression ทั้งทีม · **ข้อเสีย**: ไม่เพิ่มมูลค่าต่อผู้ใช้

### B. เชื่อมแชทเข้ากับ booking ⭐ ใกล้ที่สุดกับที่พี่เริ่ม

ตอนนี้ `messenger_channels` **ไม่มี `work_id` / `recruit_id` / `booking_id`** → จองงานแล้วไม่มีห้องแชทเกิดขึ้น
หน้าแชทเขียนว่า _"เริ่มแชทโดยจองงานหรือรับสมัครงาน"_ แต่ไม่มีโค้ดไหนทำให้มันเกิด

- migration เพิ่ม nullable FK · สร้าง channel ตอน booking สำเร็จ · `/api/v1/messenger/*`
- **งานใหญ่** ~3-5 วัน แต่ได้ผลิตภัณฑ์จริง

### C. Realtime (polling ก่อน ไม่ต้องตั้ง websocket)

ไม่มี realtime เลย — `config/broadcasting.php` **ไม่มีไฟล์นี้** ไม่มี reverb/pusher ใน composer.json
ตอนนี้ผู้ใช้ A ส่งข้อความ ผู้ใช้ B ไม่เห็นจนกว่าจะกดรีเฟรชเอง

- **ทางถูก**: `?after_id=X` + poll ทุก 3-5 วิ (มี `local_code_id` ให้ dedup อยู่แล้ว)
- **ทางแพง**: laravel/reverb → ต้องตั้ง server + deploy
- แนะนำทำ**หลัง B** เพราะต้องมีช่องทางแชทก่อนถึงจะมีอะไรให้ poll

### D. ปิดช่องโหว่ที่เหลือ — verify ก่อน

เอกสารเดิมบอกว่ามี แต่ที่เช็คแล้ว**แก้ไปหมด** (ดูหัวข้อ 2) — เหลือที่ยัง**ไม่ได้เช็ค**:

- `IssuePoint::customer()` / `userMission()` → class ไม่มี (เอกสารบอกว่า fatal)
- `UserSeeder` import `UserBackend` → class ไม่มี (เอกสารบอกว่า seed พัง)
- `routes/web.php` → `settings.index` ซ้ำ 2 ครั้ง
- `layouts/app.blade.php` include `layouts.navigation` → ไฟล์ไม่มี

**ควรทำ**: ไล่ verify บั๊จที่เหลือทีละอัน อันไหนยังพังจริงก็แก้ อันไหนหายไปแล้วก็ตัดออกจากเอกสาร
→ ประโยชน์คือเอกสารเชื่อถือได้ + เจอช่องโหว่ที่ยังไม่เจอ

### E. ปิดช่องว่าง API ที่เหลือในแอป

ADR-0003 ระบุว่ายังไม่มีใน `/api/v1`: notifications · favorites · reviews · portfolio ·
sessions/GPS · availability · wallet · dispute · map · search · barcode · banner · issue points
— มีครบในเว็บแล้ว ย้ายมาได้ แต่ **ต้องทำตามที่แอปเรียกจริง** (หลักการเดิมของ ADR)

### F. เก็บงานบ้าน

- `docs/TODO.md` ตายแล้ว (เหลือ `- [ ]` ว่างเปล่า 1 บรรทัด)
- `.agent/ai_workspace_context.md` ล้าสมัย 4 ข้อ (ดูหัวข้อ 2) · มีไฟล์ `.BACKUP-2026-03-02.md` ค้าง
- ลบ dead code: `⚡*` blade (~52 ไฟล์) · `Frontend\{Dashboard, MeExplorer, WorkExplorer, RecruitExplorer}` · `Cron` model
- เพิ่ม reverse relation `User` → messenger (ตอนนี้ต้อง query `MessengerParticipant` ด้วยมือทุกที)

---

## 4. ข้อควรรู้ก่อนเริ่มงานใหม่

### เครื่องมือ

```bash
# Docker stack (MySQL + Laravel) — ต้อง up
docker compose up -d

# เทสต์ (รันนอก Docker ได้ ใช้ SQLite in-memory)
php artisan test
php artisan test --filter=MessengerAccessTest

# Pint — ระวัง! PHP 8.4 ในเครื่องพี่ทำให้ Pint crash
# ให้รันใน container (PHP 8.3) แทน
docker exec chaothuk-laravel-laravel.test-1 sh -c "cd /var/www/html && ./vendor/bin/pint --test app/..."

# migrate บน stack จริง
docker exec chaothuk-laravel-laravel.test-1 sh -c "cd /var/www/html && php artisan migrate --force"

# แอปมือถือ
cd ../chaothuk-app && npx tsc --noEmit
```

### กับเหนียวที่เจอระหว่างทำ

| เรื่อง                                    | รายละเอียด                                                                                        |
| ----------------------------------------- | ------------------------------------------------------------------------------------------------- |
| **Pint crash บน PHP 8.4**                 | เครื่องพี่ PHP 8.4 · container PHP 8.3 → **ต้องรันใน container** ไม่งั้นเห็นแต่ deprecation noise |
| **Pint มี violation เก่า**                | ~24 ไฟล์ทั่วโปรเจค — **อย่า** `pint` ทั้งโปรเจค ให้รันเฉพาะไฟล์ที่ตัวเองแก้                       |
| **Livewire test ดัก exception**           | `expectException` ใช้ไม่ได้ → ต้อง assert ผลลัพธ์ (`assertSet`) แทน                               |
| **Livewire ใช้ hashed path**              | `livewire-<hash>/update` ไม่ใช่ `/livewire/update`                                                |
| **Login ผ่านฟอร์มได้ ไม่ผ่านการคลิกปุ่ม** | ต้อง `form.requestSubmit()` ใน browser QA                                                         |
| **`$ch.id` ยังผ่าน lint**                 | IDE จะเตือน "Undefined method" = **false positive** (Eloquent magic) — ตรวจด้วย `php -l`          |

### บัญชีทดสอบ

| ประเภท   | บัญชี                                                                      | มีแชท                                                     |
| -------- | -------------------------------------------------------------------------- | --------------------------------------------------------- |
| Demo     | `worker1@chaothuk.test` … `employer2@chaothuk.test`                        | ไม่มี                                                     |
| Mock     | `user1..user30@mock.test` (worker) · `user31..user50@mock.test` (employer) | `user19` มี 3 channels · `user7`, `user45`, `user50` มี 2 |
| Password | `password`                                                                 |                                                           |

### กฎเหล็ก (จาก AGENTS.md)

- ทำงานบน **`develop`** เท่านั้น — `chaothuk` / `dev.chaothuk` เป็น deploy-trigger
- **ห้าม commit/push ถ้าพี่ไม่สั่ง**
- แยก commit ตามชนิดงาน ไม่กองก้อนเดียว
- เนื้อหา commit = อังกฤษ · ปิดท้ายด้วย `Co-Authored-By: Claude Code <noreply@anthropic.com>`
- ข้อความที่ user เห็น = **ไทย**

---

## 5. สถานะ git

```
branch: develop (ahead of origin2/develop by 6 commits — ยังไม่ push)
```

| Commit    | หัวข้อ                                                            |
| --------- | ----------------------------------------------------------------- |
| `0bee04e` | `test: pin the messenger authorization fixes`                     |
| `534b858` | `perf: index messenger tables and stop truncating long messages`  |
| `054e8fa` | `fix: close messenger authorization holes and remove guest flow`  |
| `053a9c3` | `fix: portfolio detail 500s and /portfolios/create 404s` (พี่เอง) |

**ยังไม่ push** — พี่สั่ง push เมื่อไรค่อย push

---

## 6. ถ้าจะอัปเดตไฟล์นี้

เพิ่มหัวข้อที่ทำเสร็จลงข้อ 1 · เลื่อนตัวเลือกที่เลือกไปเป็น "เสร็จแล้ว" ในข้อ 3 · อัปเดตข้อ 2 ถ้า verify บั๊จเพิ่ม

---

## 7. อัปเดต 2026-10-24 — messenger `/api/v1` เสริมให้สมบูรณ์ (ยังไม่ commit)

งาน messenger สำหรับแอปมือถือ (ข้อ 3-B/C เดิม) เขียนไว้ใน working tree เป็นจำนวนมากแล้ว
วันนี้เสริมให้ครบ **สมมาตรทั้งสองฝั่งของ marketplace** — เทสต์ใหม่รวม **73 ผ่าน (402 assertions)** · Pint ✅

| สิ่งที่เพิ่ม/แก้ | ไฟล์ |
| --- | --- |
| `POST /api/v1/messenger/recruit-bookings/{id}/channel` — เปิดห้องจากการสมัครงาน (mirror ฝั่ง work; ใช้ `ensureForRecruitBooking` ที่มีอยู่แล้ว) | `MessengerController.php` · `routes/api/v1.php` |
| เทสต์ 3 ตัว: applicant เปิดห้อง (+ is_customer กลับข้าง) · เปิดซ้ำได้ห้องเดิม · stranger 403 | `tests/Feature/Api/V1/MessengerTest.php` |
| pin รายชื่อ messenger route อัปเดตบวก route ใหม่ | `tests/Feature/MessengerAccessTest.php` |
| **แก้บั๊จ time-bomb**: `NotificationTest` hardcode `booking_date '2026-10-20'` → หมดอายุเป็น 422 วันที่ย้อนหลัง → `now()->addDays(7)` | `tests/Feature/Api/V1/NotificationTest.php` |

ฝั่งแอป (`../chaothuk-app`) ปรับหน้าแชท/หน้า booking ให้ใช้ endpoint ครบทั้งสองฝั่ง + แก้บั๊ก id ประกาศในปุ่มดูประกาศ —
สถานะเต็มอยู่ที่ **chaothuk-app/.agent/handoff.md** (ยังไม่ commit ทั้งสอง repo — รอคำสั่ง)
