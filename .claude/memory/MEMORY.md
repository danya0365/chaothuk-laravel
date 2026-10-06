# Chaothuk — เช่าถูก — Memory Index

> Active index — โหลดทุก session **คุมให้ ≤150 บรรทัด**
> ดู [MEMORY-GUIDE.md](MEMORY-GUIDE.md) สำหรับวิธีจัดการ (เพิ่ม/archive/promote)

## Core
- [Project Overview](core/project-overview.md) — ตลาดกลางหาฟรีแลนซ์ + ผู้ว่าจ้าง · architecture, stack, roadmap
- [ซาน่า Persona](core/persona.md) — ตัวตน ซาน่า: เลขาผู้หญิง เร้าร้อน พูดตรง · ภรรยา+เลขาของพี่ · ลงท้าย "ค่ะ"
- [Conventions](core/conventions.md) — มาตรฐานโค้ด Laravel/PHP + naming + git
- [🔒 Develop-Only Rule](core/feedback-develop-only-workflow.md) — **ทุกงานเกิดบน `develop`** · chaothuk/dev.chaothuk เป็น deploy-trigger เท่านั้น
- [🗄️ ดัชนีเอกสาร](reference/system-docs.md) — SRS · API-Reference · Data-Model (อ่านก่อนแตะโค้ด)

## Decisions (ADR)
- [0001 AI Agent Toolkit](decisions/0001-agent-toolkit.md) — ชุดเครื่องมือ Claude ในโปรเจค (persona/memory/hooks/rules/commands)
- [0002 Develop-Only Workflow](decisions/0002-develop-only-workflow.md) — ทำงานทั้งหมดบน `develop`; deploy ด้วย ff-merge

## Modules
<!-- spec ต่อ feature — สร้างด้วย /new-feature (ยังไม่มี) -->

## Working Log
<!-- log งานที่กำลังทำ — ยังไม่มี -->

## Reference
- [API Endpoints Map](reference/api-endpoints.md) — สรุป route ทั้งหมด 3 ฝั่ง
- [Seeder Map](reference/seeder-map.md) — 15 seeder · อะไรทำอะไร · ENV ที่ใช้

## Archived
<!-- ของที่ retire ดู _archive/INDEX.md -->

## 📚 เอกสารในโปรเจค

> ทั้งหมดเขียนจาก **โค้ดจริง** · อัปเดต 2026-10-06 · เริ่มที่ `docs/README.md`

- [docs/README.md](../../docs/README.md) — ดัชนีเอกสารทั้งหมด (หน้าประตู)
- [docs/SRS.md](../../docs/SRS.md) — ข้อกำหนดระบบ · **อ่านก่อนเริ่มงานใหม่**
- [docs/API-REFERENCE.md](../../docs/API-REFERENCE.md) — 103 endpoint ของ `/api/*`
- [docs/DATA-MODEL.md](../../docs/DATA-MODEL.md) — 63 ตาราง
- [docs/DEV-DOC.md](../../docs/DEV-DOC.md) · [docs/DEPLY_TO_VPS.md](../../docs/DEPLY_TO_VPS.md) · [docs/TODO.md](../../docs/TODO.md)
- [.agent/ai_workspace_context.md](../../.agent/ai_workspace_context.md) — บริบทสำหรับ AI
