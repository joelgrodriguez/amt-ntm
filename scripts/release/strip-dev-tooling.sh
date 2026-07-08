#!/usr/bin/env bash
# Remove dev-only paths from git index and working tree (master release step).
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
PATHS_FILE="$ROOT/scripts/release/dev-tooling-paths.txt"

cd "$ROOT"

removed=0

while IFS= read -r path || [[ -n "$path" ]]; do
  path="${path%%#*}"
  path="$(echo "$path" | xargs)"

  if [[ -z "$path" ]]; then
    continue
  fi

  if git ls-files --error-unmatch "$path" &>/dev/null 2>&1; then
    git rm -rf --ignore-unmatch "$path" >/dev/null
    echo "git removed: $path"
    removed=1
  fi
done < "$PATHS_FILE"

"$ROOT/scripts/release/clean-dev-tooling-worktree.sh"

if [[ "$removed" -eq 0 ]]; then
  echo "No tracked dev-tooling paths left in git index."
fi