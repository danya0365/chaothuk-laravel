---
name: feedback-develop-only-workflow
description: chaothuk-laravel repo rule — work happens on feature/* branches, never commit directly to develop or the deploy branches
metadata:
    node_type: memory
    type: feedback
    originSessionId: 58188d00-9b02-44e7-a4a7-dd3f50cb5a7d
    modified: 2026-10-07T07:32:44.651Z
---

All work in `chaothuk-laravel` happens on a `feature/<name>` branch, which then merges into
`develop`. Never commit directly to `develop`. `chaothuk` (prod) and `dev.chaothuk` (dev) are
deploy-trigger branches only — pushing to them fires the corresponding GitHub Actions workflow
([.github/workflows/deploy-prod.yml](../../../.github/workflows/deploy-prod.yml),
[.github/workflows/deploy-dev.yml](../../../.github/workflows/deploy-dev.yml)). They move forward only
via `git merge --ff-only develop`, never via independent commits.

Flow: `feature/* -> develop -> main`. Note the repo has no `main` branch yet — production still runs
on `chaothuk`. See [ADR-0004](../decisions/0004-strict-git-flow.md).

**Why:** the original rule was "work on develop only", stated after I created a workflow file while
HEAD was on `chaothuk`. That left `develop` as both the working branch and the integration branch,
with no way to separate work. The user tightened it to git flow on 2026-10-07: feature branches for
everything, and `develop` reserved for merges.

**How to apply:** Before creating files or committing, run `git branch --show-current`. If it is not a
`feature/*` branch, create one from an up-to-date `develop`. To ship, fast-forward
(`git checkout dev.chaothuk && git merge --ff-only develop`, then push) rather than committing on the
deploy branch. If `--ff-only` fails there is divergence — stop and ask; never force-push or rebase
away pushed commits.
