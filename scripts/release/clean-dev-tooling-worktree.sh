#!/usr/bin/env bash
# Delete dev-only paths from the working tree (tracked or not).
# Safe to run after `git switch master` — restores a theme-only checkout.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
PATHS_FILE="$ROOT/scripts/release/dev-tooling-paths.txt"

cd "$ROOT"

while IFS= read -r path || [[ -n "$path" ]]; do
  path="${path%%#*}"
  path="$(echo "$path" | xargs)"

  if [[ -z "$path" ]]; then
    continue
  fi

  if [[ -e "$path" ]]; then
    rm -rf "$path"
    echo "cleaned: $path"
  fi
done < "$PATHS_FILE"