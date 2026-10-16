# Leadpipe tracker check

- Contract: public pages emit the supplied Leadpipe script once in the head, asynchronously, alongside RB2B. Lead Forensics stays disabled.
- Credible regression: a missing, duplicated, synchronous, or wrong-account script fails the real-browser tag/request assertions.
- Existing gap: the visitor-tracker E2E checks RB2B and Lead Forensics, but not Leadpipe. Extend that test instead of adding another runner.
- No production test hooks. Vendor requests are blocked before delivery, so vendor ingestion is not verified.

Run `node scripts/tests/visitor-tracker.e2e.mjs` against local WordPress before and after the implementation. Retain both evidence logs.

## Results

- Before: `visitor-tracker-20261016T150210Z.log` failed because the Leadpipe tag was missing.
- After: `visitor-tracker-20261016T150236Z.log` passed public-page, preloaded-RB2B, and JavaScript-disabled cases in real Chrome.
- The supplied Leadpipe URL renders once with `async`. RB2B remains active and Lead Forensics remains disabled.
- `php -l app/inc/performance.php` and `git diff --check` pass.
- All third-party requests were blocked. No vendor-side receipt was checked.
- Only the tracker callback, the existing visitor test, and this note changed for this task. Earlier local image and RB2B work remains intact.
- No commits, pushes, or deployments.
