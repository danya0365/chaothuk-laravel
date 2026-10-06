# Data Model — Chaothuk (เช่าถูก)
### โครงสร้างฐานข้อมูล — ฉบับยึดตาม migrations จริง

| | |
|---|---|
| **DB** | MySQL 8.0 |
| **Migration files** | 70 |
| **`Schema::create` calls** | 67 |
| **ตารางที่ใช้งานจริง** | **63** (ตัด legacy review 4 ตารางที่ถูก drop) |
| **Models** | 54 |
| **Enums** | 26 |
| **อัปเดต** | 2026-10-06 · จาก `database/migrations/` จริง |

> 📌 อ่าน [SRS.md](SRS.md) ก่อน · ดู [API-REFERENCE.md](API-REFERENCE.md) สำหรับ endpoint

---

## สารบัญ
1. [Auth & RBAC](#1-auth-rbac)
2. [Geography](#2-geography)
3. [Works](#3-works)
4. [Recruits](#4-recruits)
5. [Reviews](#5-reviews)
6. [Sessions & Tracking](#6-sessions-tracking)
7. [Reputation & Trust](#7-reputation-trust)
8. [Money](#8-money)
9. [Points](#9-points)
10. [Messenger](#10-messenger)
11. [Discovery](#11-discovery)
12. [System](#12-system)
13. [Infrastructure](#13-infrastructure)

---

## 1. Auth & RBAC

### `users`

📄 `0001_01_01_000000_create_users_table.php` · 4 columns

| Column | หมายเหตุ |
|---|---|
| `name` |  |
| `email` |  |
| `email_verified_at` |  |
| `password` |  |

### `roles`

📄 `0000_03_20_085350_create_roles_table.php` · 1 columns

| Column | หมายเหตุ |
|---|---|
| `name` |  |

### `permissions`

📄 `2021_06_13_015437_create_permissions_table.php` · 3 columns

| Column | หมายเหตุ |
|---|---|
| `slug` |  |
| `name` |  |
| `desc` |  |

### `users_roles`

📄 `2022_06_13_015437_create_users_roles.php` · 2 columns

| Column | หมายเหตุ |
|---|---|
| `user_id` |  |
| `role_id` |  |

### `user_permissions`

📄 `2021_06_13_025437_create_user_permissions_table.php` · 4 columns

| Column | หมายเหตุ |
|---|---|
| `data` |  |
| `desc` |  |
| `user_id` |  |
| `permission_id` |  |

### `role_permissions`

📄 `2022_06_13_015437_create_role_permissions_table.php` · 4 columns

| Column | หมายเหตุ |
|---|---|
| `data` |  |
| `desc` |  |
| `role_id` |  |
| `permission_id` |  |

### `personal_access_tokens`

📄 `2019_12_14_000001_create_personal_access_tokens_table.php` · 6 columns

| Column | หมายเหตุ |
|---|---|
| `tokenable` |  |
| `name` |  |
| `token` |  |
| `abilities` |  |
| `last_used_at` |  |
| `expires_at` |  |

### `sessions`

📄 `0001_01_01_000000_create_users_table.php` · 6 columns

| Column | หมายเหตุ |
|---|---|
| `id` |  |
| `user_id` |  |
| `ip_address` |  |
| `user_agent` |  |
| `payload` |  |
| `last_activity` |  |

### `password_reset_tokens`

📄 `0001_01_01_000000_create_users_table.php` · 3 columns

| Column | หมายเหตุ |
|---|---|
| `email` |  |
| `token` |  |
| `created_at` |  |

### `categories`

📄 `2024_10_25_092516_create_categories_table.php` · 3 columns

| Column | หมายเหตุ |
|---|---|
| `name` | |
| `description` | longtext |
| `image_url` | |

⚠️ **ไม่มี `slug`** (ต่างจากที่หลายเอกสารคิด) · ไม่มี softDeletes

---

---

## 2. Geography

### `geographies`

📄 `2023_06_11_032906_create_geographies_table.php` · 1 columns

| Column | หมายเหตุ |
|---|---|
| `name` |  |

### `provinces`

📄 `2023_06_11_032941_create_provinces_table.php` · 5 columns

| Column | หมายเหตุ |
|---|---|
| `code` |  |
| `name_th` |  |
| `name_en` |  |
| `image` |  |
| `geography_id` |  |

### `districts`

📄 `2023_06_11_033430_create_districts_table.php` · 4 columns

| Column | หมายเหตุ |
|---|---|
| `code` |  |
| `name_th` |  |
| `name_en` |  |
| `province_id` |  |

### `sub_districts`

📄 `2023_06_11_033446_create_sub_districts_table.php` · 4 columns

| Column | หมายเหตุ |
|---|---|
| `zip_code` |  |
| `name_th` |  |
| `name_en` |  |
| `district_id` |  |

---

## 3. Works

### `works`

📄 `2023_06_12_095622_create_works_table.php` · 15 columns

| Column | หมายเหตุ |
|---|---|
| `code` |  |
| `title` |  |
| `description` |  |
| `details` | JSON |
| `primary_image` |  |
| `images` | JSON (array) |
| `price` |  |
| `like_count` |  |
| `reply_count` |  |
| `avg_review_rating` |  |
| `display_priority` |  |
| `work_status` | enum: stand-by/busy/close |
| `province_id` |  |
| `work_type_id` |  |
| `author_id` |  |

### `work_types`

📄 `2023_06_12_094338_create_work_types_table.php` · 2 columns

| Column | หมายเหตุ |
|---|---|
| `title` |  |
| `image` |  |

### `work_likes`

📄 `2023_06_12_104555_create_work_likes_table.php` · 2 columns

| Column | หมายเหตุ |
|---|---|
| `work_id` |  |
| `author_id` |  |

### `work_bookings`

📄 `2023_06_12_152329_create_work_bookings_table.php` · 10 columns

| Column | หมายเหตุ |
|---|---|
| `customer_message` |  |
| `mobile_phone` |  |
| `booking_date` |  |
| `latitude` | ⚠️ string (ไม่ใช่ decimal) |
| `longitude` |  |
| `booking_status` | enum: waiting-to-confirm/confirm/close/cancel |
| `customer_confirm_status` |  |
| `worker_confirm_status` | enum: waiting-to-confirm/confirm/rejected |
| `author_id` |  |
| `work_id` |  |

### `work_availabilities`

📄 `2026_03_03_030006_create_work_availabilities_table.php` · 5 columns

| Column | หมายเหตุ |
|---|---|
| `work_id` |  |
| `day_of_week` |  |
| `start_time` |  |
| `end_time` |  |
| `is_available` |  |

### `work_blocked_dates`

📄 `2026_03_03_030006_create_work_availabilities_table.php` · 3 columns

| Column | หมายเหตุ |
|---|---|
| `work_id` |  |
| `blocked_date` |  |
| `reason` |  |

### `works_categories`

📄 `2024_10_25_093357_create_works_categories_table.php` · 2 columns

| Column | หมายเหตุ |
|---|---|
| `work_id` |  |
| `category_id` |  |

### `works_reviews`

📄 `2024_10_25_094526_create_works_reviews_table.php` · 2 columns

| Column | หมายเหตุ |
|---|---|
| `post_id` |  |
| `work_id` |  |

---

## 4. Recruits

### `recruits`

📄 `2023_06_12_151741_create_recruits_table.php` · 11 columns

| Column | หมายเหตุ |
|---|---|
| `title` |  |
| `description` |  |
| `details` |  |
| `primary_image` |  |
| `images` |  |
| `budget` |  |
| `display_priority` |  |
| `recruit_status` | enum: stand-by/busy/close |
| `province_id` |  |
| `work_type_id` |  |
| `author_id` |  |

### `recruit_bookings`

📄 `2023_06_12_155752_create_recruit_bookings_table.php` · 10 columns

| Column | หมายเหตุ |
|---|---|
| `customer_message` |  |
| `mobile_phone` |  |
| `booking_date` |  |
| `latitude` |  |
| `longitude` |  |
| `booking_status` |  |
| `customer_confirm_status` |  |
| `worker_confirm_status` |  |
| `author_id` |  |
| `recruit_id` |  |

### `recruits_categories`

📄 `2024_10_25_093423_create_recruits_categories_table.php` · 2 columns

| Column | หมายเหตุ |
|---|---|
| `recruit_id` |  |
| `category_id` |  |

### `recruits_reviews`

📄 `2024_10_25_094542_create_recruits_reviews_table.php` · 2 columns

| Column | หมายเหตุ |
|---|---|
| `post_id` |  |
| `recruit_id` |  |

---

## 5. Reviews

### `posts`

📄 `2024_10_25_093815_create_posts_table.php` · 6 columns

| Column | หมายเหตุ |
|---|---|
| `title` |  |
| `content` |  |
| `images` |  |
| `rating` | 0-5 · 0 = ตอบกลับ |
| `author_id` |  |
| `parent_id` | nullable FK → posts.id (ตอบกลับ) |

### `post_likes`

📄 `2024_10_25_093834_create_post_likes_table.php` · 2 columns

| Column | หมายเหตุ |
|---|---|
| `post_id` |  |
| `author_id` |  |

---

## 6. Sessions & Tracking

### `work_sessions`

📄 `2026_03_03_030004_create_work_sessions_table.php` · 13 columns

| Column | หมายเหตุ |
|---|---|
| `sessionable` | morph → works | recruits |
| `worker_id` |  |
| `customer_id` |  |
| `bookingable` | morph → work_bookings | recruit_bookings (nullable) |
| `started_at` |  |
| `ended_at` |  |
| `total_duration_minutes` |  |
| `price_agreed` | decimal(10,2) |
| `status` | enum: active/paused/completed/cancelled |
| `notes` |  |
| `cancel_reason` |  |
| `worker_confirm` |  |
| `customer_confirm` |  |

### `session_location_logs`

📄 `2026_03_03_030005_create_session_location_logs_table.php` · 8 columns

| Column | หมายเหตุ |
|---|---|
| `session_id` |  |
| `user_id` |  |
| `latitude` | decimal(10,7) |
| `longitude` |  |
| `accuracy` |  |
| `speed` |  |
| `heading` |  |
| `recorded_at` |  |

### `disputes`

📄 `2026_03_03_030002_create_disputes_table.php` · 10 columns

| Column | หมายเหตุ |
|---|---|
| `bookingable` | morph → work_bookings \| recruit_bookings (nullable) |
| `reporter_id` | FK → users |
| `respondent_id` | FK → users |
| `reason` | string |
| `description` | text |
| `evidence` | JSON |
| `status` | enum: open/investigating/resolved/closed |
| `resolution` | text |
| `admin_id` | ⚠️ **ไม่มี FK constraint** |

---

---

## 7. Reputation & Trust

### `user_reputations`

📄 `2026_03_03_000010_create_user_reputations_table.php` · 15 columns

| Column | หมายเหตุ |
|---|---|
| `user_id` | ⚠️ PRIMARY KEY (ไม่มี id) |
| `overall_score` |  |
| `quality_score` |  |
| `timeliness_score` |  |
| `communication_score` |  |
| `professionalism_score` |  |
| `total_reviews` |  |
| `total_completed_jobs` |  |
| `total_cancelled_jobs` |  |
| `completion_rate` |  |
| `response_rate` |  |
| `avg_response_minutes` |  |
| `repeat_customer_count` |  |
| `trust_level` | enum: new/bronze/silver/gold/platinum/diamond |
| `total_points_earned` |  |

### `user_reputation_reviews`

📄 `2026_03_03_000011_create_user_reputation_reviews_table.php` · 12 columns

| Column | หมายเหตุ |
|---|---|
| `reviewer_id` |  |
| `reviewee_id` |  |
| `booking_id` |  |
| `booking_type` |  |
| `quality_rating` |  |
| `timeliness_rating` |  |
| `communication_rating` |  |
| `professionalism_rating` |  |
| `overall_rating` |  |
| `comment` |  |
| `response` |  |
| `is_verified_booking` |  |

### `user_reputation_logs`

📄 `2026_03_03_000015_create_user_reputation_logs_table.php` · 5 columns

| Column | หมายเหตุ |
|---|---|
| `user_id` |  |
| `event_type` |  |
| `score_before` |  |
| `score_after` |  |
| `metadata` |  |

### `user_badges`

📄 `2026_03_03_000012_create_user_badges_table.php` · 4 columns

| Column | หมายเหตุ |
|---|---|
| `user_id` |  |
| `badge_type` |  |
| `badge_level` | enum: bronze/silver/gold |
| `metadata` |  |

### `user_verifications`

📄 `2026_03_03_000013_create_user_verifications_table.php` · 6 columns

| Column | หมายเหตุ |
|---|---|
| `user_id` |  |
| `verification_type` |  |
| `status` | enum: pending/approved/rejected |
| `proof_data` |  |
| `verified_by` |  |
| `verified_at` |  |

### `user_reports`

📄 `2026_03_03_000014_create_user_reports_table.php` · 8 columns

| Column | หมายเหตุ |
|---|---|
| `reporter_id` |  |
| `reported_user_id` |  |
| `report_type` |  |
| `description` |  |
| `status` | enum: pending/reviewing/resolved/dismissed |
| `admin_note` |  |
| `resolved_by` |  |
| `resolved_at` |  |

---

## 8. Money

### `user_wallets`

📄 `2026_03_16_070137_create_user_wallets_table.php` · 3 columns

| Column | หมายเหตุ |
|---|---|
| `user_id` |  |
| `balance` | decimal(12,2) default 0 |
| `status` |  |

### `wallet_transactions`

📄 `2026_03_16_070137_create_wallet_transactions_table.php` · 6 columns

| Column | หมายเหตุ |
|---|---|
| `user_wallet_id` |  |
| `type` | enum: deposit/payment/refund/withdrawal/adjustment |
| `amount` |  |
| `balance_after` |  |
| `reference` |  |
| `description` |  |

### `payments`

📄 `2026_03_16_070138_create_payments_table.php` · 7 columns

| Column | หมายเหตุ |
|---|---|
| `user_id` |  |
| `amount` |  |
| `currency` | default 'THB' |
| `payment_method` |  |
| `status` | enum: pending/successful/failed/refunded |
| `gateway_transaction_id` |  |
| `gateway_response` |  |

---

## 9. Points

### `issue_points`

📄 `2024_04_11_042602_create_issue_points_table.php` · 11 columns

| Column | หมายเหตุ |
|---|---|
| `slug` |  |
| `name` |  |
| `desc` |  |
| `points` |  |
| `type` |  |
| `cron_info` |  |
| `cron_task` |  |
| `start_at` |  |
| `end_at` |  |
| `status` |  |
| `user_id` |  |

### `user_points`

📄 `2024_04_11_055041_create_user_points_table.php` · 5 columns

| Column | หมายเหตุ |
|---|---|
| `point_received` |  |
| `point_available` |  |
| `expired_at` |  |
| `user_id` |  |
| `issue_point_id` |  |

### `user_point_logs`

📄 `2024_04_11_075617_create_user_point_logs_table.php` · 4 columns

| Column | หมายเหตุ |
|---|---|
| `points` |  |
| `user_point_id` |  |
| `action_user_id` |  |
| `point_transaction_id` |  |

### `point_transaction_logs`

📄 `2024_04_11_075417_create_point_transaction_logs_table.php` · 4 columns

| Column | หมายเหตุ |
|---|---|
| `points` |  |
| `user_point_logs` |  |
| `transactionable` |  |
| `user_id` |  |

### `issue_point_status_logs`

📄 `2024_04_20_031522_create_issue_point_status_logs_table.php` · 3 columns

| Column | หมายเหตุ |
|---|---|
| `issue_point_status` |  |
| `issue_point_id` |  |
| `action_user_id` |  |

---

## 10. Messenger

### `messenger_channels`

📄 `2024_01_06_145642_create_messenger_channels_table.php` · 5 columns

| Column | หมายเหตุ |
|---|---|
| `slug` |  |
| `title` |  |
| `is_direct` |  |
| `is_public` |  |
| `total_participants` |  |

### `messenger_participants`

📄 `2024_01_06_145711_create_messenger_participants_table.php` · 3 columns

| Column | หมายเหตุ |
|---|---|
| `last_seen_at` |  |
| `user_id` |  |
| `channel_id` |  |

### `messenger_conversations`

📄 `2024_01_06_145727_create_messenger_conversations_table.php` · 7 columns

| Column | หมายเหตุ |
|---|---|
| `type` |  |
| `content` |  |
| `local_code_id` |  |
| `delivered_at` |  |
| `seen_at` |  |
| `user_id` |  |
| `channel_id` |  |

---

## 11. Discovery

### `favorites`

📄 `2026_03_03_030001_create_favorites_table.php` · 2 columns

| Column | หมายเหตุ |
|---|---|
| `user_id` |  |
| `favoritable` |  |

### `portfolios`

📄 `2026_03_03_030003_create_portfolios_table.php` · 5 columns

| Column | หมายเหตุ |
|---|---|
| `user_id` |  |
| `title` |  |
| `description` |  |
| `images` |  |
| `work_type_id` |  |

### `featured_works`

📄 `2026_03_03_020000_create_featured_works_table.php` · 13 columns

| Column | หมายเหตุ |
|---|---|
| `work_id` |  |
| `author_id` |  |
| `start_at` |  |
| `end_at` |  |
| `slot_position` |  |
| `amount_paid` |  |
| `payment_method` |  |
| `point_transaction_id` |  |
| `payment_status` |  |
| `is_approved` |  |
| `approved_by` |  |
| `impression_count` |  |
| `click_count` |  |

### `province_top_works`

📄 `2026_03_03_023000_create_province_top_works_table.php` · 11 columns

| Column | หมายเหตุ |
|---|---|
| `province_id` |  |
| `work_id` |  |
| `author_id` |  |
| `period` | string(7) เช่น '2026-03' |
| `rank` |  |
| `booking_count` |  |
| `confirmed_count` |  |
| `review_count` |  |
| `avg_rating` |  |
| `like_count` |  |
| `total_score` |  |

---

## 12. System

### `notifications`

📄 `2024_01_06_144403_create_notifications_table.php` · 5 columns

| Column | หมายเหตุ |
|---|---|
| `notificationable` |  |
| `title` |  |
| `content` |  |
| `notification_type` |  |
| `total_reads` |  |

### `user_notifications`

📄 `2023_07_12_125252_create_user_notifications_table.php` · 6 columns

| Column | หมายเหตุ |
|---|---|
| `notificationable` |  |
| `title` |  |
| `details` |  |
| `notification_type` |  |
| `is_read` |  |
| `author_id` |  |

### `banners`

📄 `2024_04_06_100116_create_banners_table.php` · 8 columns

| Column | หมายเหตุ |
|---|---|
| `name` |  |
| `view_count` |  |
| `is_public` |  |
| `is_pinned` |  |
| `type` |  |
| `image_url` |  |
| `expired_at` |  |
| `external_url` |  |

### `configurations`

📄 `2024_01_06_143523_create_configurations_table.php` · 5 columns

| Column | หมายเหตุ |
|---|---|
| `slug` |  |
| `name` |  |
| `value_type` |  |
| `value` |  |
| `value_options` |  |

### `user_activity_logs`

📄 `2024_04_24_160618_create_user_activity_logs_table.php` · 3 columns

| Column | หมายเหตุ |
|---|---|
| `activity_type` |  |
| `activity_value` |  |
| `user_id` |  |

### `cron_logs`

📄 `2024_08_06_031131_create_cron_logs_table.php` · 2 columns

| Column | หมายเหตุ |
|---|---|
| `cron_type` |  |
| `number_issue_point_executes` |  |

---

## 13. Infrastructure

### `cache`

📄 `0001_01_01_000001_create_cache_table.php` · 3 columns

| Column | หมายเหตุ |
|---|---|
| `key` |  |
| `value` |  |
| `expiration` |  |

### `cache_locks`

📄 `0001_01_01_000001_create_cache_table.php` · 3 columns

| Column | หมายเหตุ |
|---|---|
| `key` |  |
| `owner` |  |
| `expiration` |  |

### `jobs`

📄 `0001_01_01_000002_create_jobs_table.php` · 6 columns

| Column | หมายเหตุ |
|---|---|
| `queue` |  |
| `payload` |  |
| `attempts` |  |
| `reserved_at` |  |
| `available_at` |  |
| `created_at` |  |

### `job_batches`

📄 `0001_01_01_000002_create_jobs_table.php` · 10 columns

| Column | หมายเหตุ |
|---|---|
| `id` |  |
| `name` |  |
| `total_jobs` |  |
| `pending_jobs` |  |
| `failed_jobs` |  |
| `failed_job_ids` |  |
| `options` |  |
| `cancelled_at` |  |
| `created_at` |  |
| `finished_at` |  |

### `failed_jobs`

📄 `0001_01_01_000002_create_jobs_table.php` · 6 columns

| Column | หมายเหตุ |
|---|---|
| `uuid` |  |
| `connection` |  |
| `queue` |  |
| `payload` |  |
| `exception` |  |
| `failed_at` |  |

---

## ภาคผนวก — ตารางที่ถูกลบ

Legacy review system ถูก drop ใน `2026_03_02_053000_drop_legacy_review_tables.php`:

```
Schema::dropIfExists('reply_likes');
Schema::dropIfExists('review_likes');
Schema::dropIfExists('replies');
Schema::dropIfExists('reviews');
```

**แทนที่ด้วย:** ตาราง `posts` รวม (รีวิว + ตอบกลับผ่าน `parent_id`) + pivots `works_reviews` / `recruits_reviews`

Models `Review`, `Reply`, `ReviewLike`, `ReplyLike` ถูก**ลบออกจากโปรเจคแล้ว**

---

_รวม **63 ตาราง** ในเอกสารนี้ · จัดทำโดย ซาน่า 🌈 · มี.ค. 2026_