<div align="center">

<h1>🏗️ Chaothuk — เช่าถูก</h1>
<p><strong>Thai Freelance & Hiring Marketplace · Full-Stack Web App + REST API</strong></p>

<p>
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img alt="Livewire" src="https://img.shields.io/badge/Livewire-4.2-FB70A9?style=for-the-badge&logo=livewire&logoColor=white"/>
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img alt="Docker" src="https://img.shields.io/badge/Docker-Sail-2496ED?style=for-the-badge&logo=docker&logoColor=white"/>
  <img alt="License" src="https://img.shields.io/badge/License-MIT-22C55E?style=for-the-badge"/>
</p>

<p><em>เชื่อมต่อผู้ว่าจ้างกับฟรีแลนซ์ไทย · Connecting Thai employers with skilled freelancers</em></p>

---

</div>

## 📖 ระบบนี้คืออะไร

ตลาดกลางที่เชื่อม **ผู้ให้บริการ (Workers)** กับ **ผู้ว่าจ้าง (Employers)** ในประเทศไทย

- 👷 **Workers** ลงประกาศ **Works** — "ฉันทำงานนี้ได้" (รถรับจ้าง, ช่าง, งานบ้าน)
- 💼 **Employers** ลงประกาศ **Recruits** — "ฉันต้องการคนมาทำ"
- 🤝 ทั้งสองฝ่าย **จอง → ต่อรอง → ยืนยัน → เริ่มงาน → ติดตาม GPS → รีวิว**
- 🎯 ระบบ **Issue Points** กระตุ้นพฤติกรรม + **Reputation** คะแนน 4 มิติ
- 💬 **Messenger** ทั้งแบบ login และ guest (เบอร์มือถือ)

### 3 หน้าจอ

| หน้าจอ                  | เทคโนโลยี                      | ใครใช้                 |
| ------------------------ | ------------------------------ | ---------------------- |
| **Frontend** `/frontend` | Livewire 4.2 (44 component)    | ผู้ใช้ทั่วไป · UI หลัก |
| **REST API** `/api/v1`    | Laravel Sanctum (29 endpoint)  | **Mobile app** (Expo)  |
| **Admin** `/backend`     | Livewire 4.2 (53 component)    | ผู้ดูแลระบบ            |

### Tech Stack

| Layer    | Technology                                               |
| -------- | -------------------------------------------------------- |
| Backend  | **Laravel 11** · PHP 8.2+ · MySQL 8                      |
| Frontend | **Livewire 4.2** + Alpine.js · Tailwind **3** + Flowbite |
| Auth     | Laravel Sanctum (API) · Livewire session (web)           |
| Maps     | MapLibre GL + CartoDB Dark Tiles                         |
| Charts   | ApexCharts                                               |
| Docs     | Dedoc Scramble → `/docs/api`                             |
| Dev      | **Laravel Sail** (Docker) · PHPUnit · Laravel Pint       |

---

## 📚 เอกสารประกอบ

> ทั้งหมดเขียนจาก **โค้ดจริง** ตรวจสอบ 2026-10-06 — ให้อ่านก่อนเริ่มงาน

### 🔴 เริ่มตรงนี่

| เอกสาร                                             | เนื้อหา                                                                                                              |
| -------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| **[docs/SRS.md](docs/SRS.md)**                     | **ข้อกำหนดระบบ** — ขอบเขต · ฟีเจอร์ · architecture · สิทธิ์ · **ช่องว่างที่ยังขาด** · **บั๊จที่ทำให้ระบบพัง 11 จุด** |
| **[docs/API-REFERENCE.md](docs/API-REFERENCE.md)** | **ทุก endpoint ของ `/api/v1`** พร้อม auth · param · ตัวอย่าง request/response                                        |
| **[docs/DATA-MODEL.md](docs/DATA-MODEL.md)**       | **63 ตาราง** พร้อม columns ทั้งหมด                                                                                   |

### 🔧 สำหรับนักพัฒนา

| เอกสาร                                                           | เนื้อหา                                          |
| ---------------------------------------------------------------- | ------------------------------------------------ |
| [docs/DEV-DOC.md](docs/DEV-DOC.md)                               | วิธีรัน Sail · seeder map · บัญชีทดสอบ           |
| [docs/DEPLY_TO_VPS.md](docs/DEPLY_TO_VPS.md)                     | ขั้นตอน deploy ขึ้น VPS                          |
| [docs/TODO.md](docs/TODO.md)                                     | งานค้าง                                          |
| [SETUP.md](SETUP.md)                                             | ตั้งค่า AI assistant (Claude Code) บนเครื่องใหม่ |
| [.agent/ai_workspace_context.md](.agent/ai_workspace_context.md) | บริบทสำหรับ AI                                   |

> ⚠️ **ก่อนเขียน feature ใหม่ อ่าน [docs/SRS.md §9](docs/SRS.md#9-ข้อจำกัดและบั๊จที่ต้องรู้)**
> มีบั๊จที่ทำให้ระบบพังจริง เช่น route ที่ชี้ไป method ที่ไม่มีอยู่จริง

---

## ⚡ Quick Start

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- PHP 8.2+ & Composer (สำหรับติดตั้งครั้งแรกเท่านั้น)

### 1. Clone & Install

```bash
git clone <repository-url> chaothuk-laravel
cd chaothuk-laravel
composer install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Start Docker

```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
sail up -d
```

🌐 **http://localhost** · Frontend: **http://localhost/frontend** · API docs: **/docs/api**

### 4. Database + Assets

```bash
sail php artisan migrate --seed
sail yarn && sail yarn dev
sail php artisan storage:link
```

รายละเอียดทั้งหมด → [docs/DEV-DOC.md](docs/DEV-DOC.md)

---

## 🛠️ Development Commands

```bash
# Database
sail php artisan migrate:fresh --seed
sail php artisan db:seed --class=MockSeeder    # ข้อมูลเยอะ (dev เท่านั้น)

# Assets
sail yarn dev

# Cron
sail php artisan schedule:run
sail php artisan schedule:list

# Routes
php artisan route:list --path=api
php artisan route:list --json

# Gate — ต้องรันก่อนบอกว่าเสร็จ
./vendor/bin/pint --test     # code style
./vendor/bin/phpunit         # tests
```

> ⚠️ **อย่าลืม:** ทำงานบน branch `develop` เท่านั้น — `chaothuk` / `dev.chaothuk` เป็น deploy-trigger เท่านั้น

---

## 📊 ขนาดระบบ

| สิ่ง                | จำนวน                                                  |
| ------------------- | ------------------------------------------------------ |
| Routes              | **152** (api/v1 29 · backend 57 · frontend 39 · ajax 8 · อื่น 19) |
| ตาราง               | **63**                                                 |
| Models              | **54**                                                 |
| Enums               | **26**                                                 |
| Livewire components | **98**                                                 |
| Blade views         | **177**                                                |
| Seeders             | **16**                                                 |

---

## 📝 Commit Convention

ใช้ [Conventional Commits](https://www.conventionalcommits.org/) — เนื้อความเป็น**อังกฤษ**

```
<type>(<scope>): <subject>
```

| Type       | Description                       |
| ---------- | --------------------------------- |
| `feat`     | New feature                       |
| `fix`      | Bug fix                           |
| `docs`     | Documentation only                |
| `style`    | Formatting, no logic change       |
| `refactor` | Code restructure                  |
| `test`     | Adding or fixing tests            |
| `chore`    | Build, tooling, or config changes |

**ทุกงานเกิดบน `develop`** · deploy ด้วย `git merge --ff-only develop` ไป `dev.chaothuk` / `chaothuk`

---

## 🌏 Localization

- `APP_LOCALE=th` · `APP_FALLBACK_LOCALE=en`
- Translation files: `lang/th/` · `lang/en/`

---

## 📄 License

MIT License — ⚠️ ยังไม่มีไฟล์ `LICENSE` ในโปรเจค ต้องเพิ่มเมื่อพร้อมปล่อยจริง

---

<div align="center">

Built with ❤️ in Thailand · Powered by [Laravel](https://laravel.com) + [Livewire](https://livewire.laravel.com)

</div>
