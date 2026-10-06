---
name: feedback-develop-only-workflow
description: chaothuk-laravel repo rule — all real work happens on develop; other branches are deploy triggers only
metadata: 
  node_type: memory
  type: feedback
  originSessionId: 58188d00-9b02-44e7-a4a7-dd3f50cb5a7d
  modified: 2026-08-05T07:57:03.465Z
---

All actual feature/CI work in `chaothuk-laravel` must happen on the `develop` branch. Branches like
`chaothuk` (prod) and `dev.chaothuk` (dev) are deploy-trigger branches only — pushing to them fires
the corresponding GitHub Actions workflow ([.github/workflows/deploy-dev.yml](../../../.github/workflows/deploy-dev.yml),
[.github/workflows/deploy-prod.yml](../../../.github/workflows/deploy-prod.yml)). They should only ever move
forward via fast-forward merge from `develop`, never receive independent commits.

**Why:** user's explicit rule ("กฎของเรา ต้องทำงานบน develop เท่านั้น"), stated after I created a new
workflow file while HEAD was on `chaothuk` instead of `develop`. The user then had me `git checkout
develop && git merge --ff-only chaothuk` to bring develop back level with chaothuk before continuing.

**How to apply:** Before creating/editing files or making commits in this repo, check out `develop`
first. To ship to dev or prod, fast-forward `develop`'s commits into `dev.chaothuk` / `chaothuk`
respectively (`git checkout dev.chaothuk && git merge --ff-only develop`, then push) rather than
committing directly on the deploy branch.
