#!/usr/bin/env bash
#
# Regression check for the db:apply dry-run contract.
#
# It proves three things:
#   1. Every numbered shell migration declares dry-run handling.
#   2. Numbered migrations with known write primitives have a dry-run branch,
#      not just a decorative DRY_RUN token.
#   3. DRY_RUN=1 scripts/db/apply leaves the target DB and uploads inventory
#      unchanged.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
HERE="$ROOT/scripts/db"

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP_PHP_BIN="${WP_PHP_BIN-php8.3}"
DRY_RUN_SKIP_PLUGINS="${DRY_RUN_SKIP_PLUGINS-microsoft-clarity}"
DRY_RUN_DISABLE_WP_CRON_EXEC="${DRY_RUN_DISABLE_WP_CRON_EXEC-if (!defined('DISABLE_WP_CRON')) { define('DISABLE_WP_CRON', true); }}"

wp_cli() {
  local global_args=()
  if [[ -n "${DRY_RUN_DISABLE_WP_CRON_EXEC:-}" ]]; then
    global_args+=("--exec=${DRY_RUN_DISABLE_WP_CRON_EXEC}")
  fi
  if [[ -n "${DRY_RUN_SKIP_PLUGINS:-}" ]]; then
    global_args+=("--skip-plugins=${DRY_RUN_SKIP_PLUGINS}")
  fi

  if [[ -n "$WP_CONTAINER" ]]; then
    docker exec "$WP_CONTAINER" "$WP_PHP_BIN" /usr/local/bin/wp "${global_args[@]}" --path="$WP_PATH" --allow-root "$@"
  else
    command wp "${global_args[@]}" --path="$WP_PATH" "$@"
  fi
}

fail() {
  echo "ERROR: $*" >&2
  exit 1
}

static_check() {
  if ! scan_static_coverage "$HERE"; then
    exit 1
  fi
}

scan_static_coverage() {
  local dir="$1"
  local missing=()
  local unguarded=()
  local script
  local failed=0

  shopt -s nullglob
  for script in "$dir"/[0-9]*.sh; do
    if ! has_dry_run_declaration "$script"; then
      missing+=("$(display_path "$script")")
    fi
  done

  if [[ ${#missing[@]} -gt 0 ]]; then
    printf 'ERROR: numbered DB migrations missing dry-run handling:\n' >&2
    printf '  - %s\n' "${missing[@]}" >&2
    failed=1
  fi

  for script in "$dir"/[0-9]*.sh "$dir"/[0-9]*.php; do
    [[ -e "$script" ]] || continue

    if has_write_primitive "$script" && ! has_dry_run_branch "$script"; then
      unguarded+=("$(display_path "$script")")
    fi
  done

  if [[ ${#unguarded[@]} -gt 0 ]]; then
    printf 'ERROR: numbered DB migrations with write primitives need a dry-run branch:\n' >&2
    printf '  - %s\n' "${unguarded[@]}" >&2
    failed=1
  fi

  local broad_write_suppression
  broad_write_suppression="$(
    awk '
      /^[[:space:]]*(#|\/\/|\*)/ { next }
      /\|\|[[:space:]]*true/ {
        if ($0 ~ /(wp[[:space:]]+post[[:space:]]+(create|update|delete)|wp[[:space:]]+post[[:space:]]+meta[[:space:]]+(add|update|delete)|wp[[:space:]]+media[[:space:]]+(import|regenerate)|wp[[:space:]]+rewrite[[:space:]]+flush|wp[[:space:]]+db[[:space:]]+query.*(UPDATE|DELETE|INSERT|REPLACE)|wp_update_post[[:space:]]*\(|wp_insert_post[[:space:]]*\(|wp_delete_post[[:space:]]*\(|update_post_meta[[:space:]]*\(|delete_post_meta[[:space:]]*\(|delete_metadata[[:space:]]*\(|wp_set_object_terms[[:space:]]*\(|wp_update_term_count_now[[:space:]]*\(|set_post_thumbnail[[:space:]]*\(|media_sideload_image[[:space:]]*\(|update_option[[:space:]]*\(|flush_rewrite_rules[[:space:]]*\(|Red_Item::create[[:space:]]*\(|Red_Module::flush_by_module[[:space:]]*\(|\$wpdb->(update|insert|delete)[[:space:]]*\()/) {
          print FILENAME ":" FNR ":" $0
        }
      }
    ' "$dir"/[0-9]*.sh "$dir"/[0-9]*.php
  )"

  if [[ -n "$broad_write_suppression" ]]; then
    printf 'ERROR: write-path || true suppression found:\n%s\n' "$broad_write_suppression" >&2
    failed=1
  fi

  return "$failed"
}

display_path() {
  local path="$1"
  if [[ "$path" == "$ROOT/"* ]]; then
    printf '%s\n' "${path#$ROOT/}"
  else
    printf '%s\n' "$path"
  fi
}

has_dry_run_declaration() {
  awk '!/^[[:space:]]*(#|\/\/|\*)/ && /DRY_RUN|NTM_DRY_RUN|dry_run/ { found = 1 } END { exit(found ? 0 : 1) }' "$1"
}

has_write_primitive() {
  awk '
    /^[[:space:]]*(#|\/\/|\*)/ { next }
    /(wp[[:space:]]+post[[:space:]]+(create|update|delete)|wp[[:space:]]+post[[:space:]]+meta[[:space:]]+(add|update|delete)|wp[[:space:]]+media[[:space:]]+(import|regenerate)|wp[[:space:]]+rewrite[[:space:]]+flush|wp[[:space:]]+db[[:space:]]+query.*(UPDATE|DELETE|INSERT|REPLACE)|wp_update_post[[:space:]]*\(|wp_insert_post[[:space:]]*\(|wp_delete_post[[:space:]]*\(|update_post_meta[[:space:]]*\(|delete_post_meta[[:space:]]*\(|delete_metadata[[:space:]]*\(|wp_set_object_terms[[:space:]]*\(|wp_update_term_count_now[[:space:]]*\(|set_post_thumbnail[[:space:]]*\(|media_sideload_image[[:space:]]*\(|update_option[[:space:]]*\(|flush_rewrite_rules[[:space:]]*\(|Red_Item::create[[:space:]]*\(|Red_Module::flush_by_module[[:space:]]*\(|\$wpdb->(update|insert|delete)[[:space:]]*\()/ {
      found = 1
    }
    END { exit(found ? 0 : 1) }
  ' "$1"
}

has_dry_run_branch() {
  awk '
    /^[[:space:]]*(#|\/\/|\*)/ { next }
    /(if[[:space:]]+.*(DRY_RUN|NTM_DRY_RUN|dry_run|is_dry_run)|if[[:space:]]*\(.*\$dry|\$dry[[:space:]]*\?)/ {
      found = 1
    }
    END { exit(found ? 0 : 1) }
  ' "$1"
}

static_checker_self_test() {
  local fixture="$tmp/static-fixture"
  local pass_dir="$fixture/pass"
  local fail_dir="$fixture/fail"
  local output="$fixture/fail.out"

  mkdir -p "$pass_dir" "$fail_dir"

  # Deliberately synthetic: this proves the guard without dirtying any real WP DB.
  cat > "$pass_dir/001-guarded.sh" <<'EOF'
#!/usr/bin/env bash
set -euo pipefail
DRY_RUN="${DRY_RUN-1}"
if [[ "$DRY_RUN" != "0" ]]; then
  echo "dry-run: would update fixture"
  exit 0
fi
wp post meta update 123 _fixture value
EOF

  cat > "$fail_dir/001-token-only.sh" <<'EOF'
#!/usr/bin/env bash
set -euo pipefail
DRY_RUN="${DRY_RUN-1}"
echo "has the token, but no guard"
wp post meta update 123 _fixture value
EOF

  if ! scan_static_coverage "$pass_dir" >/dev/null 2>&1; then
    fail "static coverage rejected the guarded synthetic fixture"
  fi

  if scan_static_coverage "$fail_dir" >"$output" 2>&1; then
    fail "static coverage accepted a token-only synthetic fixture with an unguarded write"
  fi

  if ! grep -q "001-token-only.sh" "$output"; then
    fail "static coverage failed but did not name the unguarded synthetic fixture"
  fi
}

db_snapshot() {
  local output="$1"
  local tables
  local table
  local quoted_tables=""

  tables="$(wp_cli db tables --all-tables-with-prefix)"
  [[ -n "$tables" ]] || fail "wp db tables returned no tables"

  while IFS= read -r table; do
    [[ -n "$table" ]] || continue
    table="${table//\`/\`\`}"
    quoted_tables+="${quoted_tables:+, }\`${table}\`"
  done <<< "$tables"

  wp_cli db query "CHECKSUM TABLE ${quoted_tables}" --skip-column-names \
    | LC_ALL=C sort > "$output"
}

options_snapshot() {
  local output="$1"
  local prefix

  prefix="$(wp_cli db prefix)"
  wp_cli db query \
    "SELECT option_name, LENGTH(option_value), CRC32(option_value) FROM ${prefix}options ORDER BY option_name" \
    --skip-column-names > "$output"
}

wait_for_idle_wp_cron() {
  local prefix
  local count
  local attempt

  prefix="$(wp_cli db prefix)"

  for attempt in {1..30}; do
    count="$(wp_cli db query \
      "SELECT COUNT(*) FROM ${prefix}options WHERE option_name = '_transient_doing_cron'" \
      --skip-column-names | tr -d '[:space:]')"

    if [[ "$count" == "0" ]]; then
      return
    fi

    if [[ "$attempt" == "1" ]]; then
      echo "==> Waiting for in-flight WP-Cron to finish before baseline snapshot"
    fi
    sleep 2
  done

  fail "WP-Cron stayed in-flight for 60s; refusing to take a noisy dry-run baseline"
}

uploads_snapshot() {
  local output="$1"
  local prefix
  local upload_path
  local uploads_dir
  local php

  prefix="$(wp_cli db prefix)"
  upload_path="$(wp_cli db query \
    "SELECT option_value FROM ${prefix}options WHERE option_name = 'upload_path' LIMIT 1" \
    --skip-column-names | tr -d '\r' || true)"

  if [[ -z "$upload_path" ]]; then
    uploads_dir="${WP_PATH%/}/wp-content/uploads"
  elif [[ "$upload_path" == /* ]]; then
    uploads_dir="$upload_path"
  else
    uploads_dir="${WP_PATH%/}/${upload_path}"
  fi

  php='
$base = getenv("UPLOADS_DIR");
if (!$base || !is_dir($base)) {
    echo "__uploads_dir_not_found__\n";
    exit(0);
}

$rows = [];
$flags = FilesystemIterator::SKIP_DOTS;
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base, $flags));
foreach ($iterator as $file) {
    if (!$file->isFile()) {
        continue;
    }

    $path = $file->getPathname();
    $relative = substr($path, strlen(rtrim($base, DIRECTORY_SEPARATOR)) + 1);
    $rows[] = $relative . "\t" . $file->getSize() . "\t" . $file->getMTime();
}
sort($rows, SORT_STRING);
echo implode("\n", $rows);
echo "\n";
'

  if [[ -n "$WP_CONTAINER" ]]; then
    docker exec -e UPLOADS_DIR="$uploads_dir" "$WP_CONTAINER" "$WP_PHP_BIN" -r "$php" > "$output"
  else
    UPLOADS_DIR="$uploads_dir" php -r "$php" > "$output"
  fi
}

snapshot() {
  local dir="$1"

  mkdir -p "$dir"
  db_snapshot "$dir/db-checksums.txt"
  options_snapshot "$dir/options-fingerprints.txt"
  uploads_snapshot "$dir/uploads-inventory.txt"
}

assert_same() {
  local before="$1"
  local after="$2"
  local label="$3"
  local diff_file="$4"

  if ! diff -u "$before" "$after" > "$diff_file"; then
    echo "ERROR: DRY_RUN=1 changed ${label}." >&2
    cat "$diff_file" >&2
    if [[ "$label" == "database checksums" ]]; then
      echo "Changed wp_options rows, if any (value-redacted as length + CRC32):" >&2
      diff -u "$tmp/before/options-fingerprints.txt" "$tmp/after/options-fingerprints.txt" >&2 || true
    fi
    exit 1
  fi
}

tmp="$(mktemp -d "${TMPDIR:-/tmp}/ntm-dry-run-check-XXXXXX")"
trap 'rm -rf "$tmp"' EXIT

echo "==> Proving static coverage with synthetic fixtures"
static_checker_self_test

echo "==> Static dry-run coverage check"
static_check

echo "==> Checking WordPress target"
wp_cli core is-installed >/dev/null
wait_for_idle_wp_cron

echo "==> Capturing before snapshot"
snapshot "$tmp/before"

echo "==> Running DRY_RUN=1 scripts/db/apply"
if ! WP_CONTAINER="$WP_CONTAINER" WP_PATH="$WP_PATH" WP_PHP_BIN="$WP_PHP_BIN" DRY_RUN=1 NTM_DRY_RUN=1 "$HERE/apply" > "$tmp/apply.log" 2>&1; then
  cat "$tmp/apply.log" >&2
  fail "DRY_RUN=1 scripts/db/apply failed"
fi

echo "==> Capturing after snapshot"
snapshot "$tmp/after"

assert_same "$tmp/before/db-checksums.txt" "$tmp/after/db-checksums.txt" "database checksums" "$tmp/db.diff"
assert_same "$tmp/before/uploads-inventory.txt" "$tmp/after/uploads-inventory.txt" "uploads inventory" "$tmp/uploads.diff"

echo "OK: DRY_RUN=1 db:apply left the DB and uploads inventory unchanged."
