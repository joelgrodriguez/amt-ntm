# Behavior spec: release

<!-- admiral:auto — appended on land, newest first. Read top-down for current behavior. -->

## Gate releases on required upload binaries reaching staging. DevKinsta must push Files + Database; a database-only push cannot transfer `wp-content/uploads`. — #112
*Landed 2026-07-18 · type: bugfix*

- The release runbook explicitly requires a DevKinsta Files + Database push and explains that database-only does not transfer uploads.
- The required-asset inventory contains both `2026/05/20260511_NTM_Abel-Highlight-MACH-II-In-Action-Video_V1-720p-optimized.mp4` and `2026/07/20270713_NTM_MACHII-Motor-Panel.jpg`.
- The runbook explains that migration 035 registers the video attachment, while the motor-panel image is consumed directly by `content_url()` and needs no attachment row.
- A repository-owned release preflight under `scripts/release/` verifies both local binaries and both target-environment URLs.
- `scripts/release/to-master.sh` invokes the preflight before pushing and fails clearly when the target base URL is absent or required staging media is unreachable.
- Migration 035 cannot make a required missing video look like a successful replay; its required-media failure is nonzero or the preflight is explicitly enforced as the authoritative gate.
- `npm run build` passes.
