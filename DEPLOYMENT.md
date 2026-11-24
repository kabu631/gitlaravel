# Putting Git Infosys online

## What the server needs

- PHP 8.3 or newer with `mbstring`, `intl`, `gd`, `pdo_mysql`, `curl`, `zip`, `fileinfo`
- MySQL 8 or MariaDB 10.4+
- Composer 2, and Node 20+ (Node is only needed to build the frontend; you can build elsewhere and upload `public/build`)
- A web server whose document root is the **`public`** folder, with HTTPS

## First install

```bash
git clone https://github.com/kabu631/gitlaravel.git gitinfosys && cd gitinfosys
cp .env.production.example .env      # then fill in every CHANGE_ME and your domain
composer install --no-dev --optimize-autoloader
php artisan key:generate
mysql -u USER -p DATABASE < git_infosys_v2.sql
php artisan migrate --force
php artisan storage:link
npm ci && npm run build
php artisan admin:password           # prints the admin password once: save it
php artisan optimize
```

Make `storage` and `bootstrap/cache` writable by the web server user.

Never reuse a key or password that has been in Git. `php artisan key:generate` must be run on the server.

## Every update

```bash
bash scripts/deploy.sh
```

It puts the site in maintenance mode, pulls, installs, migrates, rebuilds, caches and brings it back. If a step fails
the site is brought back up and the script stops with an error.

## Settings that matter in `.env`

| Setting | Live value |
|---|---|
| `APP_ENV` / `APP_DEBUG` | `production` / `false`. Debug pages expose secrets. |
| `APP_URL` | Your real `https://` address. Links, the sitemap and share images use it. |
| `SESSION_SECURE_COOKIE` | `true` |
| `FILAMENT_PATH` | Your own admin address, hard to guess |
| `TRUSTED_PROXIES` | Only if you use Cloudflare or a load balancer: its IPs, or `*` when all traffic comes through it |
| `MAIL_*` | Needed for password reset and contact-form emails |
| `OPENROUTER_API_KEY` or `DEEPSEEK_API_KEY` | Needed for the assistant, PC builder and comparison verdict |

## Apache and nginx

Apache works with the included `public/.htaccess` (compression and caching are set there; enable `mod_rewrite`,
`mod_deflate`, `mod_headers` and `mod_expires`). For nginx:

```nginx
server {
    listen 443 ssl;
    http2 on;
    server_name your-domain.com;
    root /var/www/gitinfosys/public;
    index index.php;
    client_max_body_size 20m;

    gzip on;
    gzip_types text/css application/javascript application/json application/ld+json application/xml image/svg+xml;

    location /build/ { expires 1y; add_header Cache-Control "public, immutable"; }
    location /storage/ { expires 30d; }
    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    location ~ /\.(?!well-known) { deny all; }
}
```

## After going live

1. Open `/up`: it should answer 200. Point your uptime monitor at it.
2. Sign in to the admin panel and change anything that is still a placeholder.
3. Add the site to Google Search Console and submit `/sitemap.xml`. Paste the verification code in Admin → SEO Settings.
4. Back up the database and `storage/app/public` every day, somewhere other than the server:

   ```bash
   mysqldump --single-transaction DATABASE | gzip > backup-$(date +%F).sql.gz
   ```

   This is a full backup with personal data. Keep it private, and never commit it. The file in this repository
   (`git_infosys_v2.sql`) is made with `scripts/export-database.sh`, which leaves personal data out.
