# 📚 เอกสาร Chaothuk

> เอกสารทุกไฟล์เขียนจาก **โค้ดจริง** ตรวจสอบ 2026-10-06
> หน้าประตูเข้า docs/ — ถ้าเพิ่งเริ่มอ่านที่นี่ที่เดียว

> ⚠️ **SRS.md ยังเป็นข้อมูลของโปรเจคเดิม** — API เดิม (103 endpoint `/api/*`) ถูกลบแล้ว
> ดู [API-REFERENCE.md](API-REFERENCE.md) สำหรับ API ปัจจุบัน

---

## 🔴 เอกสารหลัก — อ่านก่อนเริ่มงาน

### [SRS.md](SRS.md) — ข้อกำหนดระบบ

สัญญาระหว่าง backend กับทีมอื่น บอกว่าระบบนี้ **ทำอะไรได้ / ทำอะไรไม่ได้**

| หัวข้อ                                                           | เนื้อหา                                             |
| ---------------------------------------------------------------- | --------------------------------------------------- |
| [§1 ภาพรมระบบ](SRS.md#1-ภาพรวมระบบ)                              | ระบบนี้คืออะไร · architecture 3 หน้าจอ · stack      |
| [§2 ผู้ใช้และบทบาท](SRS.md#2-ผู้ใช้งานและบทบาท)                  | Role 4 ตัว · Permission 7 สิทธิ์ · กลไกการผสมสิทธิ์ |
| [§3 ฟีเจอร์ที่มีอยู่จริง](SRS.md#3-ฟีเจอร์ที่มีอยู่จริง)         | ประกาศ · จอง · รีวิว · GPS · reputation · wallet    |
| [§4 โครงสร้างข้อมูล](SRS.md#4-โครงสร้างข้อมูล)                   | 63 ตาราง แบ่งตาม domain                             |
| [§5 API สำหรับ Mobile](SRS.md#5-api-สำหรับ-mobile)               | ภาพรวม endpoint · format · parameter                |
| [§6 หน้าจอ Web](SRS.md#6-หน้าจอ-web)                             | 39 หน้า frontend · 57 หน้า admin                    |
| [§7 กฎทางธุรกิจ](SRS.md#7-กฎทางธุรกิจ)                           | permission gate · booking ownership · pagination    |
| [⚠️ §8 สิ่งที่ยังไม่มี](SRS.md#8-สิ่งที่ยังไม่มี-ต้องรู้ก่อนใช้) | payment gateway · email · OTP · coupon — ยังไม่มี   |
| [🔴 §9 บั๊จที่ต้องรู้](SRS.md#9-ข้อจำกัดและบั๊จที่ต้องรู้)       | **11 จุดที่พังได้** · ช่องโหว่สิทธิ์ · โค้ดตาย      |
| [ภาคผนวก ก](SRS.md#ภาคผนวก-ก-—-ฟีเจอร์ที่-mobile-ต้องขอเพิ่ม)    | **13 ฟีเจอร์ที่มีในเว็บแต่ยังไม่มีใน API**          |
| [ภาคผนวก ข](SRS.md#ภาคผนวก-ข-—-ความต่างจากเอกสารเดิม)            | เอกสารเก่าผิดตรงไหนบ้าง                             |
| [ภาคผนวก ค](SRS.md#ภาคผนวก-ค-—-ข้อเสนอ)                          | ลำดับงานที่ควรทำต่อ                                 |

---

### [API-REFERENCE.md](API-REFERENCE.md) — ทุก endpoint ของ `/api/v1`

**29 endpoint** พร้อม auth · query param · ตัวอย่าง JSON · ตาราง HTTP code ที่คืน

| กลุ่ม                                                                        | Endpoint                                        |
| ---------------------------------------------------------------------------- | ----------------------------------------------- |
| [Auth](API-REFERENCE.md#auth)                                                | 4 · register · login · me · logout              |
| [Reference data](API-REFERENCE.md#reference-data-public--ใช้สร้าง-dropdown)  | 3 · จังหวัด · ประเภทงาน · หมวดหมู่              |
| [Works](API-REFERENCE.md#works-ฉันทำงานนี้ได้--apiv1works)                   | 4 · รายการ · รายละเอียด · ลงประกาศ · จอง        |
| [Recruits](API-REFERENCE.md#recruits-ฉันต้องการคนมาทำ--apiv1recruits)        | 4 · รายการ · รายละเอียด · ลงประกาศ · สมัครงาน   |
| [Work bookings](API-REFERENCE.md#work-bookings--apiv1work-bookings)          | 6 · รายการ 2 มุม · รายละเอียด · ยืนยัน · ยกเลิก |
| [Recruit bookings](API-REFERENCE.md#recruit-bookings--apiv1recruit-bookings) | 6 · เหมือนกัน สลับฝั่งผู้สมัคร/ผู้ว่าจ้าง       |
| [Uploads](API-REFERENCE.md#uploads--apiv1uploads)                            | 2 · รูป · avatar                                |

> ✅ เขียนใหม่ 2026-10-06 · standard REST · rate limit · pagination meta · ปิดช่องโหว่เดิม
> ⚠️ ยังไม่มี: messenger · notifications · GPS sessions · reviews · favorites ·
> wallet · dispute · map · search — ดูหัวข้อ "สิ่งที่ยังไม่มีใน v1" ท้ายไฟล์
> 📱 แอปอยู่ที่ `../chaothuk-app` (React Native + Expo) — ดู
> [ADR-0003](../.claude/memory/decisions/0003-rebuild-mobile-api.md)

---

### [DATA-MODEL.md](DATA-MODEL.md) — 63 ตาราง

| Domain                                                                                  | ตาราง                                                                |
| --------------------------------------------------------------------------------------- | -------------------------------------------------------------------- |
| [Auth & RBAC](DATA-MODEL.md#1-auth-rbac)                                                | users · roles · permissions · pivots · tokens · sessions             |
| [Geography](DATA-MODEL.md#2-geography)                                                  | geographies · provinces · districts · sub_districts                  |
| [Works](DATA-MODEL.md#3-works)                                                          | works · work_types · work_likes · work_bookings · availability       |
| [Recruits](DATA-MODEL.md#4-recruits)                                                    | recruits · recruit_bookings · pivots                                 |
| [Reviews](DATA-MODEL.md#5-reviews)                                                      | posts · post_likes                                                   |
| [Sessions & Tracking](DATA-MODEL.md#6-sessions-tracking)                                | work_sessions · session_location_logs                                |
| [Reputation & Trust](DATA-MODEL.md#7-reputation-trust) · [Money](DATA-MODEL.md#8-money) | คะแนน · badges · verification · reports · wallet · payment · dispute |
| [Points](DATA-MODEL.md#9-points) · [Messenger](DATA-MODEL.md#10-messenger)              | แต้ม · แชท                                                           |
| [Discovery](DATA-MODEL.md#11-discovery) · [System](DATA-MODEL.md#12-system)             | favorites · portfolios · featured · notifications · config           |

---

## 🔧 เอกสารปฏิบัติการ

| เอกสาร                             | เนื้อหา                                          |
| ---------------------------------- | ------------------------------------------------ |
| [DEV-DOC.md](DEV-DOC.md)           | วิธีรัน Sail · seeder map · บัญชีทดสอบ           |
| [DEPLY_TO_VPS.md](DEPLY_TO_VPS.md) | ขั้นตอน deploy ขึ้น VPS                          |
| [TODO.md](TODO.md)                 | งานค้าง                                          |
| [../SETUP.md](../SETUP.md)         | ตั้งค่า AI assistant (Claude Code) บนเครื่องใหม่ |

---

## 🔗 สำหรับ AI

| ไฟล์                                                                                                       | ใช้ทำอะไร                                        |
| ---------------------------------------------------------------------------------------------------------- | ------------------------------------------------ |
| [../.agent/ai_workspace_context.md](../.agent/ai_workspace_context.md)                                     | บริบทสรุปทุกอย่าง — ให้ AI อ่านก่อนเริ่มงาน      |
| [../.agent/ai_workspace_context.BACKUP-2026-03-02.md](../.agent/ai_workspace_context.BACKUP-2026-03-02.md) | สำเนาเก่า — ⚠️ ข้อมูลผิดเยอะ อย่าใช้อ้างอิง      |
| [../.agent/task.md](../.agent/task.md)                                                                     | วิเคราะห์ schema — ⚠️ ล้าสลัย หลายข้อทำเสร็จแล้ว |

---

## 📌 กฎเวลาอ่าน

> อย่าจำทุกอย่างพร้อมกัน — เลือกเปิดตามงาน

| ต้องการ                       | เปิด                                                                               |
| ----------------------------- | ---------------------------------------------------------------------------------- |
| รู้ว่า "ระบบนี้ทำอะไรได้บ้าง" | [SRS §1-3](SRS.md#3-ฟีเจอร์ที่มีอยู่จริง)                                          |
| รู้ว่า "อะไรยังไม่มี"         | [SRS §8](SRS.md#8-สิ่งที่ยังไม่มี-ต้องรู้ก่อนใช้)                                  |
| รู้ว่า "อะไรพังได้"           | [SRS §9](SRS.md#9-ข้อจำกัดและบั๊จที่ต้องรู้)                                       |
| จะยิง endpoint                | [API-REFERENCE.md](API-REFERENCE.md)                                               |
| จะแตะ column                  | [DATA-MODEL.md](DATA-MODEL.md)                                                     |
| ออกแบบ migration ใหม่         | [DATA-MODEL.md](DATA-MODEL.md) → [SRS §8](SRS.md#8-สิ่งที่ยังไม่มี-ต้องรู้ก่อนใช้) |

---

<div align="center">

_เอกสารทั้งหมดเขียนจากโค้ดจริง · ตรวจสอบ 2026-10-06_

</div>
