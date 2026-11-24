#!/usr/bin/env bash
# Update the live site. Run on the server from the project folder: bash scripts/deploy.sh
set -euo pipefail
cd "$(dirname "$0")/.."

# Refuse to deploy an unsafe configuration.
grep -qE '^APP_ENV=production' .env || { echo "APP_ENV is not production"; exit 1; }
grep -qE '^APP_DEBUG=false' .env || { echo "APP_DEBUG must be false"; exit 1; }
grep -qE '^APP_KEY=base64:.+' .env || { echo "APP_KEY is empty: run php artisan key:generate"; exit 1; }
grep -qE '^APP_URL=https://' .env || { echo "APP_URL must start with https://"; exit 1; }

trap 'php artisan up >/dev/null 2>&1 || true' EXIT
php artisan down --retry=60

git pull --ff-only
composer install --no-dev --optimize-autoloader --no-interaction
npm ci
npm run build
php artisan migrate --force
php artisan storage:link 2>/dev/null || true
php artisan optimize:clear
php artisan optimize
php artisan queue:restart
php artisan up

echo "Deployed $(git rev-parse --short HEAD)"
