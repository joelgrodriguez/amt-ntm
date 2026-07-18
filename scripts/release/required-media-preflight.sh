#!/usr/bin/env bash
# Verify release-critical upload binaries exist locally and on the target site.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
INVENTORY="${REQUIRED_MEDIA_INVENTORY:-$ROOT/scripts/release/required-media.txt}"
TARGET_BASE_URL="${RELEASE_TARGET_BASE_URL:-${STAGING_BASE_URL:-}}"
LOCAL_UPLOADS_DIR="${RELEASE_LOCAL_UPLOADS_DIR:-${LOCAL_UPLOADS_DIR:-}}"
HTTP_TIMEOUT="${RELEASE_MEDIA_TIMEOUT:-15}"

fail() {
  echo "ERROR: $*" >&2
  exit 1
}

trim() {
  sed 's/^[[:space:]]*//; s/[[:space:]]*$//'
}

if [[ -z "$TARGET_BASE_URL" ]]; then
  fail "RELEASE_TARGET_BASE_URL is required. Set it to the Kinsta staging base URL after a DevKinsta Files + Database push."
fi

if [[ "$TARGET_BASE_URL" != http://* && "$TARGET_BASE_URL" != https://* ]]; then
  fail "RELEASE_TARGET_BASE_URL must start with http:// or https:// (got: $TARGET_BASE_URL)"
fi

if [[ ! -f "$INVENTORY" ]]; then
  fail "required media inventory not found: $INVENTORY"
fi

if [[ -z "$LOCAL_UPLOADS_DIR" ]]; then
  inferred_uploads="$ROOT/../../uploads"
  if [[ -d "$inferred_uploads" ]]; then
    LOCAL_UPLOADS_DIR="$(cd "$inferred_uploads" && pwd)"
  else
    fail "LOCAL_UPLOADS_DIR is required. Set it to the local wp-content/uploads path."
  fi
fi

if [[ ! -d "$LOCAL_UPLOADS_DIR" ]]; then
  fail "local uploads directory not found: $LOCAL_UPLOADS_DIR"
fi

TARGET_BASE_URL="${TARGET_BASE_URL%/}"

echo "==> Required media preflight"
echo "    local uploads: $LOCAL_UPLOADS_DIR"
echo "    target base:   $TARGET_BASE_URL"

missing_local=0
unreachable_remote=0
checked=0

while IFS= read -r line || [[ -n "$line" ]]; do
  rel="$(printf '%s' "${line%%#*}" | trim)"
  [[ -z "$rel" ]] && continue

  checked=$((checked + 1))
  local_file="$LOCAL_UPLOADS_DIR/$rel"
  target_url="$TARGET_BASE_URL/wp-content/uploads/$rel"

  if [[ -f "$local_file" ]]; then
    echo "    OK local:  $rel"
  else
    echo "    ERROR local missing: $local_file" >&2
    missing_local=1
  fi

  if curl --fail --silent --location --head --max-time "$HTTP_TIMEOUT" --output /dev/null "$target_url" ||
     curl --fail --silent --show-error --location --range 0-0 --max-time "$HTTP_TIMEOUT" --output /dev/null "$target_url"; then
    echo "    OK target: $target_url"
  else
    echo "    ERROR target unreachable: $target_url" >&2
    unreachable_remote=1
  fi
done < "$INVENTORY"

if [[ "$checked" -eq 0 ]]; then
  fail "required media inventory is empty: $INVENTORY"
fi

if [[ "$missing_local" -ne 0 || "$unreachable_remote" -ne 0 ]]; then
  echo "!! Required media preflight failed." >&2
  echo "   Use a DevKinsta Files + Database push; a database-only push cannot transfer wp-content/uploads." >&2
  exit 1
fi

echo "==> Required media preflight passed ($checked file(s))."
