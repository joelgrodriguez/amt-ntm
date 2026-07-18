#!/usr/bin/env bash
# Merge latest dev into master and strip dev-only agent tooling for staging/prod deploy.
#
# Run from a master checkout only:
#   git switch master
#   git pull --ff-only origin master
#   RELEASE_TARGET_BASE_URL=https://<kinsta-staging-host> ./scripts/release/to-master.sh
#
# The target must already have received a DevKinsta Files + Database push;
# database-only does not transfer wp-content/uploads.
#
# Pushes origin/master when complete. Dev keeps all agent/shogun tooling.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
cd "$ROOT"

branch="$(git branch --show-current)"
if [[ "$branch" != "master" ]]; then
  echo "Switch to master first (current: $branch)" >&2
  exit 1
fi

git pull --ff-only origin master

if git merge-base --is-ancestor HEAD dev 2>/dev/null; then
  git merge --ff-only dev
else
  git merge --no-ff dev -m "Merge dev into master for release"
fi

"$ROOT/scripts/release/strip-dev-tooling.sh"

if ! git diff --cached --quiet; then
  git commit -m "chore(release): strip dev-only agent tooling from master"
else
  echo "Dev tooling already stripped on master."
fi

npm run lint:php

npm run build

"$ROOT/scripts/release/required-media-preflight.sh"

git push origin master

"$ROOT/scripts/release/clean-dev-tooling-worktree.sh"

echo "Released to origin/master (theme only, no dev tooling)."
echo "Tip: npm run release:hooks — auto-clean when switching to master."
