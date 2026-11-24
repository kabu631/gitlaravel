#!/usr/bin/env bash
# Export git_infosys_v2.sql for GitHub: all site data, but no personal data.
# Only the admin account is included; customers, messages, sessions, carts and orders are exported as empty tables.
#
#   bash scripts/export-database.sh [path-to-mysqldump]
set -euo pipefail
cd "$(dirname "$0")/.."

DUMP="${1:-/c/xampp/mysql/bin/mysqldump.exe}"
DB=git_infosys_v2
OUT=git_infosys_v2.sql
OPT=(-u root --default-character-set=utf8mb4 --single-transaction)

# Structure only: personal, temporary or per-visitor data.
EMPTY=(users user_profiles user_comments wishlists cart_items orders order_items contact_messages
       newsletter_subscribers newsletter_subscriptions sessions cache cache_locks
       password_reset_tokens jobs job_batches failed_jobs role_user)

IGNORE=()
for t in "${EMPTY[@]}"; do IGNORE+=("--ignore-table=$DB.$t"); done

{
  "$DUMP" "${OPT[@]}" --routines "${IGNORE[@]}" "$DB"
  "$DUMP" "${OPT[@]}" --no-data "$DB" "${EMPTY[@]}"
  "$DUMP" "${OPT[@]}" --no-create-info --where="email='admin@gitinfosys.com'" "$DB" users
} > "$OUT.tmp"

mv "$OUT.tmp" "$OUT"
echo "Wrote $OUT ($(wc -c < "$OUT") bytes)"
