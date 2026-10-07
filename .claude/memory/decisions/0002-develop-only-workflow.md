---
name: 0002-develop-only-workflow
description: 'ADR — [ถูกแทนที่บางส่วนโดย ADR-0004] chaothuk/dev.chaothuk เป็น deploy-trigger เท่านั้น; ส่วน "ทุกงานเกิดบน develop" เปลี่ยนเป็น feature branch'
metadata:
    node_type: memory
    type: decision
    status: superseded
    scope: git-workflow
    updated: 2026-10-07
    originSessionId: de1d3a9e-21ff-44d4-b239-d948cfac61bb
    modified: 2026-10-07T07:31:55.058Z
---

# ADR-0002: Develop-Only Workflow

> ⚠️ **ถูกแทนที่บางส่วนโดย [ADR-0004](0004-strict-git-flow.md) (2026-10-07)**
>
> - ❌ **ข้อ 1** "ทุกงานเกิดบน `develop`" → **ยกเลิก** · งานเกิดบน `feature/*` แล้ว merge เข้า `develop`
> - ✅ **ข้อ 2–5** (deploy-trigger · เลื่อนด้วย `--ff-only` · เช็ค branch ก่อนทำงาน · ห้าม force-push) — **ยังใช้**
>
> อ่านกฎปัจจุบันได้ที่ [ADR-0004](0004-strict-git-flow.md) — ไฟล์นี้เก็บไว้เป็นประวัติ

**สถานะ:** superseded (บางส่วน) · **วันที่:** 2026-08-05 · **ผู้เสนอ:** พี่ (กฎที่ตั้งไว้เอง)

## บริบท

Repo `chaothuk-laravel` มี 4 branch ที่ใช้งานจริง:
`develop` (local) · `chaothuk` (prod) · `dev.chaothuk` (dev) · `master` (legacy) —
และมี GitHub Actions ที่ trigger deploy เมื่อ push เข้า `chaothuk` / `dev.chaothuk`

เกิดเหตุ: ซาน่าสร้างไฟล์ workflow ใหม่ตอน HEAD อยู่บน `chaothuk` (branch deploy)
→ งานจริงไปปนบน branch ที่ไม่ควรมีงาน

## การตัดสินใจ

1. **ทุกงานจริงเกิดบน `develop` เท่านั้น** — feature, CI config, fix, refactor ทั้งหมด
2. **`chaothuk` (prod) และ `dev.chaothuk` (dev) เป็น deploy-trigger branch เท่านั้น**
   — ห้าม commit อิสระ ห้าม push โดยตรง
3. **เลื่อนขึ้นได้ทางเดียว: fast-forward จาก `develop`**
    ```bash
    git checkout dev.chaothuk && git merge --ff-only develop && git push
    git checkout chaothuk    && git merge --ff-only develop && git push
    ```
    ถ้า `--ff-only` fail แปลว่ามี divergence → หยุดถามพี่ ห้าม `--force`
4. **ก่อนแตะไฟล์/commit ทุกครั้ง** — เช็ค `git branch --show-current` ก่อน ถ้าไม่ใช่ `develop` ให้ `git checkout develop`
5. **ห้าม force-push** ไปที่ deploy branch เด็ดขาด

## ผลกระทบ

- ✅ Deploy ควบคุมได้ — branch deploy สะอาด เป็นแค่ pointer ไปหา commit ที่ผ่านการทดสอบแล้ว
- ✅ ไม่มีงานหลุดขึ้น production โดยไม่ได้ตั้งใจ
- ⚠️ ต้อง discipline — ถ้าลืม checkout ระหว่าง session จะได้ conflict ตอน merge
  → เช็ค branch ก่อนเริ่มงานทุกครั้ง (ห้ามพึ่งความจำ)

## ทำเองได้ไหม

- ✅ เขียนโค้ด/commit บน `develop`
- ✅ merge `--ff-only` ไป `dev.chaothuk` **เมื่อพี่สั่ง ship**
- ❌ push ไป `chaothuk` (prod) — ต้องพี่สั่งชัดเจนเท่านั้น
- ❌ force-push ที่ branch ใด — ห้ามเด็ดขาด

ดู [[memory-guide]] · กฎเหล็ก no-commit ใน `AGENTS.md`
