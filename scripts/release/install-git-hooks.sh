#!/usr/bin/env bash
# Install repo git hooks for dev/master worktree hygiene.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
HOOKS_DIR="$ROOT/.git/hooks"
SOURCE="$ROOT/scripts/release/hooks/post-checkout"
TARGET="$HOOKS_DIR/post-checkout"

mkdir -p "$HOOKS_DIR"
cp "$SOURCE" "$TARGET"
chmod +x "$TARGET"

echo "Installed post-checkout hook → $TARGET"
echo "Switching to master will auto-remove dev-only paths from the working tree."