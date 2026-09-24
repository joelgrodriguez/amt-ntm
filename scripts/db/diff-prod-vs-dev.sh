#!/usr/bin/env bash
#
# diff-prod-vs-dev.sh — READ-ONLY launch reconcile helper.
#
# Compares a freshly-pulled production DB against this dev DB to answer the one
# question that decides launch step 3: "what did prod gain since this dev site
# branched that a local-DB push would overwrite?"
#
# It writes NOTHING. Pure SELECT/count reporting. Safe to run anytime.
#
# CONTEXT (NTM launch, 2026-06): the launch mechanic is "local dev DB is the
# source of truth, pushed up to Kinsta staging then prod" — NOT a fresh-pull
# that wipes local state. So dev wins by default; this script finds the short
# list of EXCEPTIONS to pull FROM prod instead (content/products/media/users
# created on prod after the dev branch), plus the wp_options junk that must NOT
# ship.
#
# USAGE:
#   1. Pull the fresh prod copy into DevKinsta as a SECOND site/DB.
#   2. Set the two DB names below (or pass as args).
#   3. ./scripts/db/diff-prod-vs-dev.sh [PROD_DB] [DEV_DB] [BRANCH_DATE]
#
#   PROD_DB      name of the freshly-pulled prod database   (default: newtech_prod)
#   DEV_DB       name of this dev database                  (default: newtech)
#   BRANCH_DATE  the cutoff: content on prod newer than this is "drift"
#                (default: 2026-05-01 — set this to when the dev DB actually
#                 diverged from prod; the theme repo's first commit is
#                 2026-01-19 but the DB was likely pulled later)
#
# Runs WP-CLI inside the DevKinsta PHP container against each DB via --url is not
# enough (same install, two DBs), so we swap DB_NAME per query with a temp
# wp-config constant override using `wp --path` + `WORDPRESS_DB_NAME` env.

set -uo pipefail

PROD_DB="${1:-newtech_prod}"
DEV_DB="${2:-newtech}"
BRANCH_DATE="${3:-2026-05-01}"

WP_CONTAINER="${WP_CONTAINER:-devkinsta_fpm}"
WP_PATH="${WP_PATH:-/www/kinsta/public/newtech}"

# Post types whose content could have drifted on prod (the ones worth protecting).
# Excludes revisions, nav items, ACF defs, transient/cache types — those either
# come from dev or don't matter.
CONTENT_TYPES="post page product attachment knowledgebase video profile manual literature footprint cutlist pricesheet resource download"

# DevKinsta shares one MySQL server, so we SELECT across both databases by
# fully-qualifying every table as `<db>.wp_posts`. One connection, no DB_NAME
# swapping. `wp db query` reuses the install's credentials.
q() {
  docker exec "$WP_CONTAINER" wp --path="$WP_PATH" --allow-root db query "$1" --skip-column-names 2>/dev/null
}

hr() { printf '%s\n' "------------------------------------------------------------"; }
section() { echo; hr; echo "  $1"; hr; }

echo "NTM launch reconcile — READ-ONLY diff"
echo "prod DB : $PROD_DB"
echo "dev  DB : $DEV_DB"
echo "branch cutoff (prod content newer than this = drift to review): $BRANCH_DATE"

# ----------------------------------------------------------------------------
section "1. Content counts by type — prod vs dev"
# A row where prod > dev for a content type = prod has stuff your dev DB lacks.
printf "%-14s %10s %10s   %s\n" "post_type" "PROD" "DEV" "note"
for t in $CONTENT_TYPES; do
  p=$(q "SELECT COUNT(*) FROM ${PROD_DB}.wp_posts WHERE post_type='${t}' AND post_status IN ('publish','draft','private','pending');")
  d=$(q "SELECT COUNT(*) FROM ${DEV_DB}.wp_posts  WHERE post_type='${t}' AND post_status IN ('publish','draft','private','pending');")
  p=${p:-0}; d=${d:-0}
  note=""
  if   [ "$p" -gt "$d" ]; then note="<-- PROD has $((p-d)) more (review: pull from prod?)"
  elif [ "$d" -gt "$p" ]; then note="    dev has $((d-p)) more (expected: your redesign work)"
  fi
  printf "%-14s %10s %10s   %s\n" "$t" "$p" "$d" "$note"
done

# ----------------------------------------------------------------------------
section "2. THE drift check — prod content published/modified AFTER the branch"
# This is the real question. If this returns nothing, a wholesale push loses
# no prod content. If it lists rows, those are exactly what to bring over.
echo "Prod posts MODIFIED after ${BRANCH_DATE} (these would be overwritten by a dev push):"
q "SELECT post_type, post_status, post_date, post_modified, ID, LEFT(post_title,60)
   FROM ${PROD_DB}.wp_posts
   WHERE post_modified > '${BRANCH_DATE} 00:00:00'
     AND post_type IN ('post','page','product','knowledgebase','video','profile','manual','literature','footprint')
     AND post_status='publish'
   ORDER BY post_modified DESC
   LIMIT 100;" \
| awk -F'\t' 'BEGIN{n=0} {print "  ["$1"/"$2"] "$4"  #"$5"  "$6; n++} END{if(n==0) print "  (none — no prod content drift since branch; wholesale push is content-safe)"; else print "\n  >>> "n" prod rows newer than branch. Review each: keep dev version or pull prod version."}'

# ----------------------------------------------------------------------------
section "3. WooCommerce orders — confirm 'no live orders' assumption"
po=$(q "SELECT COUNT(*) FROM ${PROD_DB}.wp_posts WHERE post_type IN ('shop_order','shop_order_placehold');")
do_=$(q "SELECT COUNT(*) FROM ${DEV_DB}.wp_posts WHERE post_type IN ('shop_order','shop_order_placehold');")
# HPOS check: orders may live in wc_orders table, not wp_posts.
pho=$(q "SELECT COUNT(*) FROM ${PROD_DB}.wp_wc_orders;" 2>/dev/null)
echo "prod orders (wp_posts): ${po:-0}   |   prod orders (HPOS wc_orders): ${pho:-N/A}"
echo "dev  orders (wp_posts): ${do_:-0}"
if [ "${po:-0}" = "0" ] && { [ "${pho:-0}" = "0" ] || [ -z "${pho}" ]; }; then
  echo "  OK — no real orders on prod. Wholesale push won't clobber transactions."
else
  echo "  ⚠️  PROD HAS ORDERS. Do NOT overwrite wp_posts/wp_wc_orders/wp_users from dev."
  echo "      Those tables must come FROM prod. Selective merge required."
fi

# ----------------------------------------------------------------------------
section "4. Users — accounts exist on prod that dev lacks?"
pu=$(q "SELECT COUNT(*) FROM ${PROD_DB}.wp_users;")
du=$(q "SELECT COUNT(*) FROM ${DEV_DB}.wp_users;")
echo "prod users: ${pu:-0}   |   dev users: ${du:-0}"
echo "Prod users registered after ${BRANCH_DATE} (would be lost in a dev push):"
q "SELECT user_login, user_email, user_registered FROM ${PROD_DB}.wp_users
   WHERE user_registered > '${BRANCH_DATE} 00:00:00' ORDER BY user_registered DESC LIMIT 50;" \
| awk -F'\t' 'BEGIN{n=0}{print "  "$1"  <"$2">  "$3; n++} END{if(n==0)print "  (none new — dev user table is safe to ship)"}'

# ----------------------------------------------------------------------------
section "5. wp_options HYGIENE — dev-only junk that must NOT ship to prod"
echo "Dev option values that look local/dev-only (review before pushing):"
q "SELECT option_name, LEFT(option_value,80) FROM ${DEV_DB}.wp_options
   WHERE option_name IN ('siteurl','home','active_plugins','template','stylesheet','blog_public')
      OR option_value LIKE '%localhost%'
      OR option_value LIKE '%devkinsta%'
      OR option_value LIKE '%.local%'
      OR option_value LIKE '%127.0.0.1%'
   ORDER BY option_name;" \
| awk -F'\t' '{printf "  %-24s %s\n",$1,$2}'
echo
echo "  KEY CHECKS before push:"
echo "   - siteurl/home: must become the PROD domain (search-replace on push)."
echo "   - blog_public: must be '1' on prod (0 = noindex; fine for dev, fatal for launch SEO)."
echo "   - active_plugins: confirm CookieYes / Microsoft Clarity / PixelYourSite are present + configured."
echo "   - the Vite dev-server URL is a FILE (app/.vite-dev-server), gitignored + removed on build — not a DB row, so it won't ship. Good."

# ----------------------------------------------------------------------------
section "6. Go-live toggle config — are the launch-day options actually set?"
echo "Microsoft Clarity project id:"
q "SELECT IFNULL(option_value,'(NOT SET — Clarity will not fire)') FROM ${DEV_DB}.wp_options WHERE option_name='clarity_project_id';" | sed 's/^/  /'
echo "blog_public (1 = indexable, REQUIRED for prod):"
q "SELECT option_value FROM ${DEV_DB}.wp_options WHERE option_name='blog_public';" | sed 's/^/  /'
echo "CookieYes / cookie-law-info presence in active_plugins:"
q "SELECT IF(option_value LIKE '%cookie-law-info%','present','MISSING') FROM ${DEV_DB}.wp_options WHERE option_name='active_plugins';" | sed 's/^/  /'

# ----------------------------------------------------------------------------
section "7. Slug-change safety — local slugs without a redirect"
echo "This is a heuristic: pages in dev whose slug differs from prod for the same"
echo "post ID (a renamed page) should have an old->new redirect in db/redirects.json."
q "SELECT d.ID, p.post_name AS prod_slug, d.post_name AS dev_slug, LEFT(d.post_title,50)
   FROM ${DEV_DB}.wp_posts d
   JOIN ${PROD_DB}.wp_posts p ON p.ID = d.ID
   WHERE d.post_type='page' AND p.post_type='page'
     AND d.post_name <> p.post_name
   LIMIT 50;" \
| awk -F'\t' 'BEGIN{n=0}{print "  #"$1": prod=/"$2"/  ->  dev=/"$3"/   ("$4")"; n++} END{if(n==0)print "  (no slug changes detected on matched page IDs)"; else print "\n  >>> "n" renamed pages. Confirm each has a redirect in db/redirects.json (CLAUDE.md: never a bare slug change)."}'

echo; hr
echo "  DONE. Nothing was modified. Use section 2 (drift) + section 3 (orders)"
echo "  to decide: clean drift + no orders => near-wholesale push is safe."
echo "  Any drift => bring those specific rows over from prod first."
hr
