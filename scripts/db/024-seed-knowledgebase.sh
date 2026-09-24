#!/usr/bin/env bash
#
# Seed/refresh the knowledgebase troubleshooting articles in the service hub.
#
# WHY THIS SCRIPT EXISTS: the troubleshooting content was migrated from the live
# support portal (support.newtechmachinery.com) into a `knowledgebase` CPT. The
# CPT is registered in theme code (git), but the posts themselves live in the DB
# — wiped on a fresh prod pull. The committed fixtures
# (app/data/knowledgebase/articles.php) plus this script rebuild them, so the
# service-hub Troubleshooting sections survive a pull.
#
# IDEMPOTENT: the heavy lifting is in 024-seed-knowledgebase.php, which upserts
# each article on its `_kb_source_url` meta (find-or-create, never duplicate)
# and sets — not appends — the department term and machine post_tags. Re-running
# reports "updated" for every record and changes nothing else.
#
# Companion PHP runs via `wp eval-file -` (stdin) so there's no host-vs-container
# path translation: the file is piped in, and it locates the fixtures inside the
# running WP via get_template_directory().

set -euo pipefail

DRY_RUN="${DRY_RUN-1}"
export NTM_DRY_RUN="$DRY_RUN"

# Mirror the apply runner's target resolution. We can't reuse the inherited
# wp() for this one call: eval-file needs stdin, and that wrapper's `docker exec`
# has no -i. So pipe directly, honoring the same WP_CONTAINER / WP_PATH.
WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PHP_FILE="$HERE/024-seed-knowledgebase.php"

if [[ ! -r "$PHP_FILE" ]]; then
  echo "    companion PHP not found at $PHP_FILE — aborting" >&2
  exit 1
fi

if [[ -n "$WP_CONTAINER" ]]; then
  docker exec -i -e NTM_DRY_RUN "$WP_CONTAINER" "${WP_PHP_BIN:-php8.3}" /usr/local/bin/wp --path="$WP_PATH" --allow-root eval-file - < "$PHP_FILE"
else
  command wp --path="$WP_PATH" eval-file - < "$PHP_FILE"
fi

# Flush rewrites so the new CPT's permalinks resolve on a fresh DB.
if [[ "$DRY_RUN" != "0" ]]; then
  echo "    [dry-run] would flush rewrite rules after seeding knowledgebase articles"
  echo "    knowledgebase articles checked; rewrites not flushed in dry run"
  exit 0
fi

if [[ -n "$WP_CONTAINER" ]]; then
  docker exec "$WP_CONTAINER" "${WP_PHP_BIN:-php8.3}" /usr/local/bin/wp --path="$WP_PATH" --allow-root rewrite flush >/dev/null
else
  command wp --path="$WP_PATH" rewrite flush >/dev/null
fi

echo "    knowledgebase articles seeded + rewrites flushed"
