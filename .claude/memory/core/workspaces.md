---
name: workspaces
description: Chaothuk มี 2 workspace — chaothuk-laravel (backend) + chaothuk-app (mobile Expo) ทำงานคู่กัน
metadata:
    node_type: memory
    type: project
    updated: 2026-10-06
    originSessionId: 5b7d9c3d-457b-4210-b417-b65a6aa4804d
    modified: 2026-10-06T14:56:17.663Z
---

# Workspaces — 2 โปรเจคที่ทำงานคู่กัน

| โฟลเดอร์             | บทบาท                    | stack                           |
| -------------------- | ------------------------ | ------------------------------- |
| `~/chaothuk-laravel` | backend + เว็บ (Laravel) | Laravel 11 · PHP 8.2 · Livewire |
| `~/chaothuk-app`     | แอปมือถือ                | React Native · Expo SDK 57      |

**ทำไมแยก:** แอปไม่ควรอยู่ใน repo เดียวกับ backend — dependency ต่างกันโดยสิ้นเชิง
(Node modules ขนาดใหญ่, Expo tooling) · deploy แยกกัน · ทีมแอปทำงานแยกได้โดยไม่แตะ backend

**ทำงานคู่กันแบบไหน:** เขียนแอปก่อน → แอปเรียก endpoint ที่ยังไม่มี → ไปเพิ่มใน
`routes/api/v1.php` + `app/Http/Controllers/Api/V1/` ตามที่แอปต้องใช้จริง
(ดู [[0003-rebuild-mobile-api]])

## เริ่มงานแอป

```bash
cd ~/chaothuk-app
npx expo start --ios          # เครื่องพี่: iOS พร้อม (Xcode 26.6)
npx expo start --android      # ⚠️ ยังไม่ได้ — เครื่องไม่มี Android SDK
npx tsc --noEmit && npx expo lint   # gate ก่อนบอกว่าเสร็จ
```

**ชี้แอปไปที่ backend:** `EXPO_PUBLIC_API_URL` ใน `.env`
(iOS simulator `localhost` · Android emulator `10.0.2.2` · มือถือจริงใช้ LAN IP)

**กติกาสำคัญ:**

- `src/app/` มีแค่ไฟล์ route ของ Expo Router — logic อยู่ `src/presentation|domain|data`
- โครง hexagonal: presentation → data → domain (domain ห้าม import React)
- `src/AGENTS.md` (ของ Expo template) สั่งให้**อ่าน docs ตามเวอร์ชันจริงก่อนเขียน**
  — Expo เปลี่ยน breaking ทุก SDK release · ห้ามเดาจาก training data

## ยังไม่ได้ทำ

Messenger · notifications · GPS sessions · reviews · favorites · portfolio ·
availability · wallet · dispute · map · search — มีในเว็บ แต่ไม่มีใน `/api/v1`
