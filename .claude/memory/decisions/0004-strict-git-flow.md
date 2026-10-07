---
name: 0004-strict-git-flow
description: "ADR — บังคับ git flow เข้ม: งานเกิดบน feature/* เท่านั้น ห้าม commit ตรงลง develop/chaothuk/dev.chaothuk (supersedes ADR-0002)"
metadata:
    node_type: memory
    type: decision
    status: active
    scope: workflow
    updated: 2026-10-07
    originSessionId: de1d3a9e-21ff-44d4-b239-d948cfac61bb
    modified: 2026-10-07T07:31:38.088Z
---

# ADR-0004: Git flow แบบเข้ม — feature branch บังคับ

**สถานะ:** accepted · **วันที่:** 2026-10-07 · **ผู้สั่ง:** พี่
**แทนที่:** ADR-0002 (develop-only workflow) — ยังคงใช้บางส่วน แต่ข้อ 1 เปลี่ยน

## บริบท

ADR-0002 กำหนดให้ "ทุกงานเกิดบน `develop`" ซึ่งทำงานได้ แต่ `develop` กลายเป็นทั้ง
**ที่ทำงาน** และ **integration branch** ปนกัน — ไม่มี branch ไหนแยกงานออกจากกันได้
เมื่อพี่สั่งเปลี่ยนเป็น git flow แบบเข้ม จึงเปลี่ยนให้เป็น:

```
feature/*  →  develop  →  main
```

## การตัดสินใจ

1. **งานทุกชิ้นเกิดบน `feature/<ชื่องาน>`** — ไม่ใช่ `develop`
2. **ห้าม commit ตรงลง `develop` · `chaothuk` · `dev.chaothuk` เด็ดขาด**
3. **`develop` เป็น integration branch** — รับ merge จาก `feature/*` เท่านั้น
4. **`chaothuk` (prod) · `dev.chaothuk` (dev) ยังเป็น deploy-trigger** — เลื่อนด้วย
   `git merge --ff-only develop` เท่านั้น ห้าม commit อิสระ (เหตุผลเดิมจาก ADR-0002)
5. **ห้าม force-push** ทุกกรณี · ถ้า `--ff-only` fail แปลว่ามี divergence → **หยุดถามพี่**

### หมายเหตุเรื่อง `main`

Repo นี้ **ยังไม่มี branch `main`** — มีแค่ `master` (legacy จากตอนตั้ง repo, ไม่ได้ใช้งาน)
`develop → main` ในกฎนี้จึงเป็น **เป้าหมายระยะยาว** ยังไม่มีขั้นตอนจริง
จนกว่าพี่จะตัดสินใจย้าย production ไปที่ `main` — ดู "สิ่งที่ยังไม่ทำ" ด้านล่าง

## ผลกระทบ

- ✅ แยกงานออกจากกันได้ → review ก่อน merge ง่ายขึ้น · rollback ได้เป็นราย feature
- ✅ `develop` สะอาด = ตัวแทน "โค้ดที่ผ่านการทดสอบแล้วทั้งหมด"
- ⚠️ **ต้อง merge ทุกชิ้นงาน** — เพิ่มขั้นตอน และต้องตั้งชื่อ branch ให้ดี
- ⚠️ **commit ที่ทำไปแล้วบน `develop` ยังอยู่ที่นั่น** — ไม่ต้องย้อน ให้ต่อยอดจาก `develop`
  แล้วเริ่ม feature branch ตัวไหมันไป

## วิธีใช้ (คัดลอกไว้ใช้)

```bash
# เริ่มงานใหม่ — ทุกครั้ง
git checkout develop && git pull
git checkout -b feature/<ชื่องาน>       # เช่น feature/messenger-booking-link

# …ทำงาน + commit บน feature branch เท่านั้น…

# ปิดงาน — merge เข้า develop
git checkout develop
git merge --ff-only feature/<ชื่องาน>  # ถ้า develop ยังไม่มีอะไรหลัง feature
git push
git branch -d feature/<ชื่องาน>        # ลบทิ้งหลัง merge สำเร็จ
```

> ⚠️ **ถ้า `develop` มีงานคนอื่นเพิ่ม** `--ff-only` จะ fail → ใช้
> `git merge --no-ff feature/<ชื่องาน> -m "merge: <ชื่องาน>"` แทน (ห้าม rebase ทิ้ง commit เดิม)

## สิ่งที่ยังไม่ทำ (รอพี่ตัดสินใจ)

| เรื่อง                                 | ทำไมยังไม่ทำ                                                             |
| -------------------------------------- | ------------------------------------------------------------------------ |
| สร้าง branch `main`                    | ยังไม่มี · production ยังใช้ `chaothuk` ผ่าน workflow                    |
| แก้ deploy workflow ให้ trigger `main` | จะทำพร้อมกันตอนตัดสินใจเรื่องข้างบน                                      |
| ป้องกัน commit ตรงด้วย hook            | `core.hooksPath` ยังไม่ได้ตั้ง — ตอนนี้บังคับด้วยกฎ + ตรวจเองก่อน commit |

## ทำเองได้ไหม

- ✅ สร้าง feature branch · commit · merge เข้า `develop` เมื่อพี่สั่ง
- ✅ merge `--ff-only` ไป `dev.chaothuk` เมื่อพี่สั่ง ship
- ❌ commit ตรงลง `develop` / `chaothuk` / `dev.chaothuk` — **ห้ามเด็ดขาด**
- ❌ push ไป `chaothuk` (prod) — ต้องพี่สั่งชัดเจน
- ❌ force-push ที่ branch ใด · ❌ rebase ทิ้ง commit ที่ push ไปแล้ว

ดู [[memory-guide]] · [[0002-develop-only-workflow]] (ที่ยังใช้บางส่วน) · กฎเหล็ก no-commit ใน `AGENTS.md`
