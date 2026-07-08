#!/usr/bin/env bash
# Remove dev-only agent/orchestration files from the current checkout (master).
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

  if git ls-files --error-unmatch "$path" &>/dev/null; then
    git rm -rf --ignore-unmatch "$path" >/dev/null
    echo "removed: $path"
    removed=1
  elif [[ -e "$path" ]]; then
    rm -rf "$path"
    echo "deleted untracked: $path"
  fi
done < "$PATHS_FILE"

if [[ "$removed" -eq 0 ]]; then
  echo "No tracked dev-tooling paths to remove."
fi