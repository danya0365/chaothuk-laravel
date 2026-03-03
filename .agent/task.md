# 🔍 วิเคราะห์ Database Schema — Chaothuk

## ระบบนี้คืออะไร?

**Chaothuk** เป็นแพลตฟอร์มจับคู่ **ผู้ให้บริการ** กับ **ผู้ว่าจ้าง** — เน้นงานบริการเช่น รถรับจ้าง, ช่าง, แรงงาน ฯลฯ

### สองฝั่งหลักของระบบ:
| ฝั่ง | หน้าที่ | ตาราง |
|------|---------|-------|
| **Works** (งาน) | ผู้ให้บริการลงประกาศ "ฉันทำงานนี้ได้" | [works](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/User.php#282-286), `work_bookings`, `work_likes`, `works_reviews` |
| **Recruits** (หาคน) | ผู้ว่าจ้างลงประกาศ "ฉันต้องการคนทำงานนี้" | [recruits](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/User.php#292-296), `recruit_bookings`, `recruits_reviews` |

---

## 📊 สรุป Schema ปัจจุบัน (57 migrations, 43 models)

### ✅ มีแล้ว — จัดกลุ่มตามระบบ

#### 1. ผู้ใช้และสิทธิ์
| ตาราง | หน้าที่ |
|-------|---------|
| `users` | ชื่อ, อีเมล, โทร, รูป, bio, theme |
| [roles](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/User.php#155-159) / [permissions](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/User.php#160-164) | RBAC — บทบาท (admin/user) กับสิทธิ์ |
| `users_roles` / `user_permissions` / `role_permissions` | ตารางเชื่อม N:N |

#### 2. ภูมิศาสตร์ (4 ระดับ)
`geographies` → `provinces` → `districts` → `sub_districts`

#### 3. Works (งาน/บริการ)
| ตาราง | หน้าที่ |
|-------|---------|
| `work_types` | ประเภทงาน (รถรับจ้าง, ช่างไฟฟ้า ฯลฯ) |
| [works](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/User.php#282-286) | ประกาศงาน — code, title, desc, price, images, lat/lng, status |
| `work_bookings` | จองงาน — มี customer/worker confirm status |
| `work_likes` | กดไลค์ |
| `works_reviews` | เชื่อม work ↔ post (รีวิว) |
| `works_categories` | เชื่อม work ↔ category |
| [categories](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/Work.php#90-94) | หมวดหมู่ |

#### 4. Recruits (หาคน)
| ตาราง | หน้าที่ |
|-------|---------|
| [recruits](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/User.php#292-296) | ประกาศหาคน — title, desc, budget, images |
| `recruit_bookings` | สมัครงาน — confirm status |
| `recruits_reviews` | เชื่อม recruit ↔ post (รีวิว) |
| `recruits_categories` | เชื่อม recruit ↔ category |

#### 5. Reviews & Posts
| ตาราง | หน้าที่ |
|-------|---------|
| [posts](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/User.php#302-306) | รีวิว + ตอบกลับ (parent_id) — ใช้ร่วมทั้ง work และ recruit |
| `post_likes` | กดไลค์รีวิว |

#### 6. Messenger
`messenger_channels` → `messenger_participants` → `messenger_conversations`

#### 7. Points (แต้มสะสม)
| ตาราง | หน้าที่ |
|-------|---------|
| `issue_points` | กฎการให้แต้ม (เช่น login ทุกวัน +10 แต้ม) |
| `user_points` | แต้มที่ผู้ใช้ได้รับ |
| `point_transaction_logs` | ประวัติธุรกรรมแต้ม |
| `user_point_logs` | log การใช้แต้ม |
| `issue_point_status_logs` | log สถานะ issue point |

#### 8. Reputation (ชื่อเสียง)
| ตาราง | หน้าที่ |
|-------|---------|
| `user_reputations` | คะแนนรวม quality/timeliness/communication/professionalism, trust_level |
| `user_reputation_reviews` | รีวิวชื่อเสียง |
| `user_reputation_logs` | log การเปลี่ยนแปลง |
| `user_badges` | เหรียญตรา (เช่น "ตอบไว", "งานดี") |
| `user_verifications` | ยืนยันตัวตน |
| `user_reports` | รายงานผู้ใช้ |

#### 9. อื่นๆ
| ตาราง | หน้าที่ |
|-------|---------|
| [notifications](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/WorkBooking.php#53-57) / `user_notifications` | ระบบแจ้งเตือน |
| `banners` | แบนเนอร์โฆษณา |
| `configurations` | ค่า config ระบบ |
| `featured_works` | งานเด่น |
| `province_top_works` | งาน top ประจำจังหวัด |
| `cron_logs` | log cron job |
| `user_activity_logs` | log กิจกรรมผู้ใช้ |

---

## ❌ สิ่งที่ยังขาด — แนะนำเพิ่ม

### 🔴 สำคัญมาก (ควรมีก่อน Production)

#### 1. ระบบการเงิน / Payment
> ไม่มีตารางเก็บข้อมูลการชำระเงิน — booking ยืนยันแล้วแต่ไม่มี record การจ่ายเงิน

```
payments               — id, booking_type (work/recruit), booking_id, amount,
                         payment_method, payment_status, transaction_ref,
                         paid_at, refunded_at, timestamps
payment_methods         — id, user_id, type (bank/promptpay/wallet), details(json)
```

**ทำไมต้องมี:** ถ้าเป็นแพลตฟอร์มจับคู่งาน ต้องมีหลักฐานการจ่ายเงิน ป้องกัน dispute

---

#### 2. ระบบ Wallet / ยอดคงเหลือ
> Points ≠ เงินจริง — ไม่มีที่เก็บ wallet balance

```
wallets                 — id, user_id, balance, currency, timestamps
wallet_transactions     — id, wallet_id, type (deposit/withdraw/payment/refund),
                         amount, reference_type, reference_id, note, timestamps
```

**ทำไมต้องมี:** ตัดเงินค่าบริการ, จ่ายเงินให้ผู้ให้บริการ, ถอนเงิน

---

#### 3. Favorite / บันทึก
> ผู้ใช้ไม่สามารถ bookmark งานหรือ recruit ที่สนใจ

```
favorites               — id, user_id, favoritable_type (work/recruit),
                         favoritable_id, timestamps
```

**ทำไมต้องมี:** UX สำคัญ — ผู้ว่าจ้างอยากบันทึกงานไว้เปรียบเทียบ

---

#### 4. ระบบ Dispute / อุทธรณ์
> ยืนยันจองแล้ว แต่เกิดปัญหา → ไม่มีช่องทางร้องเรียนในระบบ

```
disputes                — id, booking_type, booking_id, reporter_id,
                         respondent_id, reason, evidence(json),
                         status (open/investigating/resolved/closed),
                         resolution, admin_id, timestamps
```

**ทำไมต้องมี:** ปกป้องทั้งฝั่งผู้ว่าจ้างและผู้ให้บริการ

---

### 🟡 สำคัญปานกลาง (ควรมีก่อน Scale)

#### 5. ประวัติการทำงาน / Portfolio
> ผู้ให้บริการไม่มีที่แสดงผลงานเก่า

```
portfolios              — id, user_id, title, description, images(json),
                         work_type_id, timestamps
```

**ทำไมต้องมี:** สร้างความน่าเชื่อถือ ช่วยผู้ว่าจ้างตัดสินใจ

---

#### 6. ราคาเสริม / Service Packages
> Works มี `price` เดียว — ไม่รองรับหลายแพ็กเกจ

```
work_packages           — id, work_id, name, description, price,
                         duration_hours, sort_order, timestamps
```

**ทำไมต้องมี:** งานจริงมีหลายระดับราคา เช่น รถ 4 ล้อ vs 6 ล้อ vs 10 ล้อ

---

#### 7. Availability / ตารางว่าง
> ไม่มีตารางเก็บ "วันไหนว่าง" ของผู้ให้บริการ — ต้องจองแล้วรอยืนยัน

```
work_availabilities     — id, work_id, day_of_week (0-6),
                         start_time, end_time, is_available, timestamps
work_blocked_dates      — id, work_id, blocked_date, reason, timestamps
```

**ทำไมต้องมี:** ลด booking ที่ถูก cancel เพราะผู้ให้บริการไม่ว่าง

---

#### 8. ระบบคูปอง / Promotion
> ไม่มีระบบส่วนลด → ดึงดูดผู้ใช้ใหม่ยาก

```
coupons                 — id, code, type (percent/fixed), value,
                         min_amount, max_uses, used_count,
                         start_at, end_at, timestamps
coupon_uses             — id, coupon_id, user_id, booking_type,
                         booking_id, discount_amount, timestamps
```

---

#### 9. Search History / แนะนำ
> ไม่มี tracking ว่าผู้ใช้ค้นหาอะไร → ไม่สามารถแนะนำงานได้

```
search_histories        — id, user_id, keyword, province_id,
                         work_type_id, results_count, timestamps
```

---

### 🟢 Nice to Have (เพิ่มทีหลังได้)

| ตาราง | หน้าที่ |
|-------|---------|
| `work_tags` | แท็กเสริม เช่น "24ชม.", "มีประกัน" |
| `work_faqs` | คำถามที่พบบ่อยของแต่ละงาน |
| `user_follows` | ติดตามผู้ให้บริการ |
| `service_areas` | พื้นที่ให้บริการ (work ↔ districts N:N) |
| `booking_attachments` | ไฟล์แนบ (รูป/เอกสาร) ใน booking |
| `review_images` | รูปประกอบรีวิว (ปัจจุบัน post.images เป็น json แต่ยังไม่มี upload) |
| `commission_settings` | ค่า commission ที่แพลตฟอร์มหัก |
| `invoices` | ใบเสร็จ/ใบแจ้งหนี้ |

---

## 🐛 ปัญหาที่เจอใน Schema ที่มีอยู่

| ปัญหา | รายละเอียด |
|--------|-----------|
| **`posts.content` เป็น `string`** | ควรเป็น `text` — string จำกัด 255 ตัวอักษร ไม่พอสำหรับรีวิวยาวๆ |
| **[recruits](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/User.php#292-296) ไม่มี `code`** | Works มี `code` (เช่น ทะเบียนรถ) แต่ Recruits ไม่มี — อาจต้องการ reference code |
| **[recruits](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/User.php#292-296) ไม่มี `like_count` / `reply_count` / `avg_review_rating`** | Works มีครบ แต่ Recruits ไม่มี — ทำให้ sort หรือแสดง rating ไม่ได้ |
| **Booking ไม่มี `price_agreed`** | จองแล้วไม่ได้บันทึกราคาที่ตกลง — ราคาอาจต่อรองได้ |
| **ไม่มี `completed_at` ใน bookings** | รู้แค่ status = close แต่ไม่รู้เวลาจริงที่เสร็จ |
| **`user_reputations.overall_score` เป็น `decimal(3,2)`** | Max = 9.99 — ถ้าคะแนนเต็ม 5 ก็โอเค แต่ไม่มี margin |

---

## 📋 แนะนำ: ลำดับความสำคัญในการทำ

| ลำดับ | สิ่งที่ต้องทำ | ความยาก | Priority |
|-------|-------------|---------|----------|
| 1 | 🔧 แก้ `posts.content` → `text` | ง่าย | 🔴 ทำเลย |
| 2 | 🔧 เพิ่ม `like_count`, `reply_count`, `avg_review_rating` ใน [recruits](file:///Users/marosdeeuma/chaothuk-laravel/app/Models/User.php#292-296) | ง่าย | 🔴 ทำเลย |
| 3 | 🔧 เพิ่ม `price_agreed`, `completed_at` ใน bookings | ง่าย | 🔴 ทำเลย |
| 4 | ⭐ สร้างตาราง `favorites` | ง่าย | 🟡 ควรทำ |
| 5 | 💰 สร้างตาราง `payments` | ปานกลาง | 🔴 ก่อน Production |
| 6 | 💰 สร้างตาราง `wallets` + `wallet_transactions` | ปานกลาง | 🔴 ก่อน Production |
| 7 | 🛡️ สร้างตาราง `disputes` | ปานกลาง | 🟡 ควรทำ |
| 8 | 📋 สร้างตาราง `portfolios` | ง่าย | 🟡 ควรทำ |
| 9 | 📦 สร้างตาราง `work_packages` | ง่าย | 🟡 ก่อน Scale |
| 10 | 📅 สร้างตาราง `work_availabilities` | ปานกลาง | 🟡 ก่อน Scale |
