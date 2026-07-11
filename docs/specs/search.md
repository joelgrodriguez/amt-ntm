# Behavior spec: search

<!-- admiral:auto — appended on land, newest first. Read top-down for current behavior. -->

## Make the header modal feel instant for machine queries while materially reducing backend work for all suggestions. Preserve full-page Relevanssi behavior and click tracking. — #88
*Landed 2026-07-11 · type: feature*

- Machine aliases render locally before the network response
- AJAX search avoids dynamic machine-data query explosions
- Normalized query and subtype cache result IDs without caching tracking URLs
- Duplicate and overlapping requests are controlled
- Full-page Relevanssi behavior and machine-first ranking remain correct
- Production build and DevKinsta search smoke pass
- Measured modal endpoint latency and DB query count improve materially
