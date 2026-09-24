#!/usr/bin/env bash
# End-to-end check of POST /wp-json/ntm/v1/chat-experiment/track against the
# local DevKinsta site. Sends real HTTP requests and reads the counters back
# through WP-CLI. Writes an evidence log to docs/test-evidence/.
#
# Usage: scripts/tests/chat-experiment-endpoint.e2e.sh
# Requires: DevKinsta running, experiment status "running" on the local site.
set -uo pipefail

BASE="${BASE:-https://newtech.local}"
URL="$BASE/wp-json/ntm/v1/chat-experiment/track"
WP=(docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=/www/kinsta/public/newtech --allow-root)
LIMIT=120
ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
STAMP="$(date -u +%Y%m%dT%H%M%SZ)"
LOG="$ROOT/docs/test-evidence/chat-experiment-endpoint-$STAMP.log"
mkdir -p "$(dirname "$LOG")"
# Fresh client IPs per run (10.<case>.<RUN_ID>) so earlier runs' rate-limit buckets never interfere.
RUN_ID="$((RANDOM % 250)).$((RANDOM % 250))"
fail=0

log() { echo "$*" | tee -a "$LOG"; }

hits() {
  "${WP[@]}" eval 'echo \Standard\ChatExperiment\get_totals(false)["hubspot"]["question_resolved"];' 2>/dev/null | tail -1
}

# post <client-ip> [extra curl args...] -> prints HTTP status
post() {
  local ip="$1"; shift
  curl -sk -o /dev/null -w '%{http_code}' -X POST "$URL" \
    -H 'Content-Type: application/json' \
    -H "CF-Connecting-IP: $ip" \
    --data '{"provider":"hubspot","metric":"question_resolved"}' "$@"
}

check() {
  local name="$1" want_status="$2" got_status="$3" want_delta="$4" before="$5"
  local after delta
  after="$(hits)"; delta=$((after - before))
  if [[ "$got_status" == "$want_status" && "$delta" == "$want_delta" ]]; then
    log "PASS  $name  status=$got_status delta=$delta"
  else
    log "FAIL  $name  status=$got_status (want $want_status) delta=$delta (want $want_delta)"
    fail=1
  fi
}

log "chat-experiment endpoint E2E  $STAMP"
log "target=$URL  commit=$(git -C "$ROOT" rev-parse --short HEAD)  branch=$(git -C "$ROOT" branch --show-current)"
log "state=$("${WP[@]}" option get ntm_chat_experiment --format=json 2>/dev/null | tail -1)"
log "limit_per_client_per_hour=$LIMIT  run_id=$RUN_ID"
log ""

b=$(hits); s=$(post "10.1.$RUN_ID" -H "Origin: $BASE")
check "same-origin beacon is counted" 201 "$s" 1 "$b"

b=$(hits); s=$(post "10.2.$RUN_ID" -H "Origin: ${BASE/https:\/\//https://www.}")
check "www origin is counted" 201 "$s" 1 "$b"

b=$(hits); s=$(post "10.3.$RUN_ID" -H "Referer: $BASE/machines/")
check "same-site referer without Origin is counted" 201 "$s" 1 "$b"

b=$(hits); s=$(post "10.4.$RUN_ID" -H 'Origin: https://evil.example')
check "foreign origin is rejected and not counted" 403 "$s" 0 "$b"

b=$(hits); s=$(post "10.5.$RUN_ID")
check "no Origin and no Referer is rejected" 403 "$s" 0 "$b"

b=$(hits)
s=$(curl -sk -o /dev/null -w '%{http_code}' -X POST "$URL" -H 'Content-Type: application/json' \
  -H "Origin: $BASE" -H "CF-Connecting-IP: 10.6.$RUN_ID" --data '{"provider":"hubspot","metric":"bogus"}')
check "unknown metric is rejected" 400 "$s" 0 "$b"

flood_ip="10.7.$RUN_ID"
b=$(hits); accepted=0; first_block=""
for i in $(seq 1 $((LIMIT + 5))); do
  s=$(post "$flood_ip" -H "Origin: $BASE")
  if [[ "$s" == 201 ]]; then accepted=$((accepted + 1)); elif [[ -z "$first_block" ]]; then first_block="$s@$i"; fi
done
log "INFO  flood: accepted=$accepted first_block=$first_block"
check "one client is capped at $LIMIT per hour" 429 "${first_block%@*}" "$LIMIT" "$b"

b=$(hits); s=$(post "10.8.$RUN_ID" -H "Origin: $BASE")
check "a different client is unaffected by the flood" 201 "$s" 1 "$b"

# Without CF-Connecting-IP the only address is the shared proxy, so a limit
# would pool every visitor into one bucket and silently drop real traffic.
b=$(hits); accepted=0; first_block=""
for i in $(seq 1 $((LIMIT + 5))); do
  s=$(curl -sk -o /dev/null -w '%{http_code}' -X POST "$URL" -H 'Content-Type: application/json' \
    -H "Origin: $BASE" --data '{"provider":"hubspot","metric":"question_resolved"}')
  if [[ "$s" == 201 ]]; then accepted=$((accepted + 1)); elif [[ -z "$first_block" ]]; then first_block="$s@$i"; fi
done
log "INFO  no visitor IP: accepted=$accepted first_block=${first_block:-none}"
check "missing visitor IP is never rate limited" 201 "${first_block:-201}" "$((LIMIT + 5))" "$b"

state="$("${WP[@]}" option get ntm_chat_experiment --format=json 2>/dev/null | tail -1)"
"${WP[@]}" option patch update ntm_chat_experiment status stopped >/dev/null 2>&1
b=$(hits); s=$(post "10.9.$RUN_ID" -H "Origin: $BASE")
"${WP[@]}" option update ntm_chat_experiment "$state" --format=json >/dev/null 2>&1
check "stopped experiment counts nothing" 202 "$s" 0 "$b"
log "state restored=$("${WP[@]}" option get ntm_chat_experiment --format=json 2>/dev/null | tail -1)"

log ""
if [[ $fail == 0 ]]; then log "RESULT: PASS"; else log "RESULT: FAIL"; fi
log "evidence: ${LOG#$ROOT/}"
exit $fail
