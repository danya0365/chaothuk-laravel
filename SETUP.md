# SETUP — ตั้งค่าเครื่องใหม่ (AI agent / Claude Code)

โปรเจคนี้เก็บ config ของผู้ช่วย AI (**ซาน่า**) ไว้ใน git ทั้งหมด (ดู [ADR-0001](.claude/memory/decisions/0001-agent-toolkit.md))
เมื่อ `git clone` เครื่องใหม่ ทำตามนี้:

## 1. Stack ของแอป

```bash
composer install
npm install                    # หรือ yarn
cp .env.example .env && php artisan key:generate
./vendor/bin/sail up -d       # Laravel Sail — MySQL 8 + PHP 8.2 + Node
./vendor/bin/sail artisan storage:link
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm run dev # หรือ ./vendor/bin/sail yarn dev
```

> ต้องมี Docker Desktop พร้อมเครื่อง และ alias `sail` ตาม [docs/DEV-DOC.md](docs/DEV-DOC.md)

## 2. กด accept workspace-trust (บังคับ)

เปิดโปรเจคใน Claude Code แล้ว **กด accept workspace-trust 1 ครั้ง** —
ค่า `autoMemoryDirectory` และ hooks (`format.sh`, `commit-reminder.sh`) ถึงจะทำงาน

## 3. ตรวจ path ของ memory

`.claude/settings.json` → `autoMemoryDirectory` ชี้ **เข้าโปรเจค**:
```json
{ "autoMemoryDirectory": "~/<ชื่อโฟลเดอร์-โปรเจค>/.claude/memory" }
```

✅ **ทำไมต้องอยู่ในโปรเจค** — เพื่อ commit เข้า git แล้ว**ทีมทุกคน share กันได้**
(`git clone` ก็ได้ memory มาด้วย) · เปลี่ยนค่านี้ได้ถ้าพี่ rename โฟลเดอร์


## 4. Hooks (auto-format)

hook `format.sh` เรียก `npx --no-install prettier` กับไฟล์ที่แตะ —
ถ้ายังไม่มี Prettier ในโปรเจค hook จะเงียบ (ไม่ error) ไม่กระทบอะไร
ติดตั้งเมื่อจะให้ format อัตโนมัติ: `npm i -D prettier`

> สำหรับ PHP ให้ใช้ **Pint** แทน (มาพร้อม Laravel อยู่แล้ว): `./vendor/bin/pint`

## 5. Gate ที่ต้องรันก่อนบอกว่า "เสร็จ"

```bash
./vendor/bin/pint --test    # code style
./vendor/bin/phpunit        # test
```

ดูรายละเอียดทั้งหมด → [.claude/rules/definition-of-done.md](.claude/rules/definition-of-done.md)

## 6. (ถ้าจะใช้) MCP — GitHub server

1. `cp .mcp.json.example .mcp.json` (`.mcp.json` ถูก gitignore)
2. สร้าง GitHub personal access token แล้วใส่ใน `.claude/settings.local.json`:
    ```json
    { "env": { "GITHUB_TOKEN": "ghp_xxx" } }
    ```
3. รีสตาร์ท session

---

## ⚠️ Git workflow (สำคัญ)

ทุกงานเกิดบน `develop` เท่านั้น — `chaothuk` (prod) และ `dev.chaothuk` (dev)
เป็น **deploy-trigger branch** ที่ขยับได้ทาง fast-forward จาก `develop` เท่านั้น
→ ดู [ADR-0002](.claude/memory/decisions/0002-develop-only-workflow.md)

---

## ไฟล์ที่ gitignore (สร้างใหม่ต่อเครื่อง)

- `.claude/settings.local.json` — env/ค่าเฉพาะเครื่อง (**ระวัง: ไฟล์นี้มี API key จริง — ห้าม commit**)
- `.mcp.json` — config MCP จริง (คัดจาก `.mcp.json.example`)
- `CLAUDE.local.md` — note ส่วนตัวต่อเครื่อง (ถ้ามี)
