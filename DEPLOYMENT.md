# AiSchool ERP - Deployment Guide

## Server Requirements

- **PHP** 8.3 or higher
- **MySQL** 8.0+ or MariaDB 10.6+
- **Composer** 2.5+
- **Node.js** 20+ (for frontend build)
- **Redis** 7+ (recommended for cache/queue)
- **Web Server** Nginx 1.24+ or Apache 2.4+
- **SSL Certificate** (Let's Encrypt or commercial)

### Required PHP Extensions

```
bcmath, ctype, curl, dom, fileinfo, gd, gmp, iconv, intl, json,
mbstring, openssl, pdo_mysql, redis, tokenizer, xml, zip
```

---

## Installation Steps

### 1. Clone the Repository

```bash
git clone https://github.com/your-org/aischool.git
cd aischool
```

### 2. Install PHP Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials and application settings.

### 4. Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE aischool CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --force

# Create storage link
php artisan storage:link
```

### 5. Install & Build Frontend Assets

```bash
npm install --ignore-scripts
npm run build
```

### 6. Set Permissions

```bash
chmod -R 775 storage bootstrap/cache public/uploads
chmod -R 775 storage/app/public
```

---

## Environment Configuration Guide

### Database (`DB_*`)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aischool
DB_USERNAME=aischool_user
DB_PASSWORD=strong_password_here
```

### Queue (`QUEUE_CONNECTION`)

```env
QUEUE_CONNECTION=database
# or for better performance:
# QUEUE_CONNECTION=redis
```

### Mail (`MAIL_*`)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-school.com"
MAIL_FROM_NAME="AiSchool ERP"
```

### Cache (`CACHE_STORE`)

```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Sanctum (API Auth)

```env
SANCTUM_STATEFUL_DOMAINS=your-domain.com,admin.your-domain.com
SANCTUM_TOKEN_EXPIRATION=1440  # minutes (24 hours)
```

---

## Queue Worker Setup (Supervisor)

Install Supervisor:

```bash
sudo apt install supervisor
```

Create configuration `/etc/supervisor/conf.d/aischool-worker.conf`:

```ini
[program:aischool-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/aischool/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/aischool/storage/logs/queue-worker.log
stopwaitsecs=3600
```

Reload and start:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start aischool-worker:*
```

---

## Scheduler Setup (Cron)

Add to crontab (`crontab -e`):

```cron
* * * * * cd /var/www/aischool && php artisan schedule:run >> /dev/null 2>&1
```

Verify with:

```bash
php artisan schedule:list
```

---

## File Permissions

```bash
# Set proper ownership
sudo chown -R www-data:www-data /var/www/aischool

# Set directory permissions
find /var/www/aischool -type d -exec chmod 755 {} \;
find /var/www/aischool -type f -exec chmod 644 {} \;

# Writable directories
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 775 public/uploads
chmod -R 775 public/storage
```

---

## Production Optimizations

Run these after every deployment:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
```

To clear in maintenance:

```bash
php artisan optimize:clear
```

---

## Nginx Virtual Host Configuration

```nginx
server {
    listen 80;
    server_name your-school.com www.your-school.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-school.com www.your-school.com;

    root /var/www/aischool/public;
    index index.php;

    ssl_certificate /etc/letsencrypt/live/your-school.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-school.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|webp|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    access_log /var/log/nginx/aischool-access.log;
    error_log /var/log/nginx/aischool-error.log;
}
```

---

## Apache Virtual Host Configuration

```apache
<VirtualHost *:80>
    ServerName your-school.com
    ServerAlias www.your-school.com
    Redirect permanent / https://your-school.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName your-school.com
    ServerAlias www.your-school.com

    DocumentRoot /var/www/aischool/public

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/your-school.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/your-school.com/privkey.pem

    <Directory /var/www/aischool/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"

    ErrorLog ${APACHE_LOG_DIR}/aischool-error.log
    CustomLog ${APACHE_LOG_DIR}/aischool-access.log combined
</VirtualHost>
```

---

## SSL/HTTPS Setup (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-school.com -d www.your-school.com
# Auto-renewal is added by default. Test with:
sudo certbot renew --dry-run
```

---

## Backup Strategy

### Database Backup (Automated via Scheduled Command)

```bash
# Manual backup
php artisan backup:create

# With compression
php artisan backup:create --compress
```

### Automated Daily Backups

The scheduler runs `backup:create` daily. Backups are stored in `storage/app/backups/`.

### External Backup Script

```bash
#!/bin/bash
# /etc/cron.daily/aischool-backup

BACKUP_DIR="/backups/aischool"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="aischool"
DB_USER="root"
DB_PASS="password"

mkdir -p $BACKUP_DIR

# MySQL dump
mysqldump -u$DB_USER -p$DB_PASS --single-transaction --routines $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Upload to S3 (optional)
# aws s3 cp $BACKUP_DIR/db_$DATE.sql.gz s3://your-bucket/backups/

# Delete backups older than 30 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete
```

---

## Monitoring Setup

### Application Health Checks

- Endpoint: `GET /up` (returns 200 when healthy)
- Scheduler: `php artisan schedule:run` must run every minute
- Queue Worker: `php artisan queue:work` must be running

### Log Monitoring

```bash
# Monitor error logs in real-time
tail -f storage/logs/laravel.log

# Combined with filtering
tail -f storage/logs/laravel.log | grep -i error
```

### Server Monitoring (Recommended Tools)

- **Laravel Pulse** - Built-in application monitoring
- **Laravel Horizon** - Queue monitoring (if using Redis)
- **Supervisor** - Process monitoring
- **Netdata / Prometheus** - Server resource monitoring
- **Uptime Kuma** - Uptime monitoring
- **Sentry** - Error tracking

---

## Scaling Considerations

### Horizontal Scaling

1. **Stateless Application**: Configure `SESSION_DRIVER=redis` or `database` for shared sessions
2. **Shared Filesystem**: Use S3 for file storage (`FILESYSTEM_DISK=s3`)
3. **Database Replication**: Configure read-write split
4. **Queue Workers**: Scale queue workers horizontally with multiple servers

### Performance Optimization

```env
# Enable OPcache
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.revalidate_freq=60

# PHP-FPM tuning
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
```

### CDN for Static Assets

```env
# In .env
ASSET_URL=https://cdn.your-school.com
```

---

## Security Checklist

- [ ] **APP_ENV=production** and **APP_DEBUG=false** in .env
- [ ] Generate unique **APP_KEY** (`php artisan key:generate`)
- [ ] Enable **HTTPS** with valid SSL certificate
- [ ] Set strong database passwords
- [ ] Restrict database user permissions (only SELECT, INSERT, UPDATE, DELETE on app database)
- [ ] Configure **CORS** properly for your frontend domains
- [ ] Set **SESSION_SECURE_COOKIE=true** on HTTPS
- [ ] Set **SESSION_SAME_SITE=strict** or **lax**
- [ ] Use **HTTP headers** (HSTS, CSP, X-Frame-Options, etc.)
- [ ] Disable directory listing in web server config
- [ ] Regular security updates: `composer update`
- [ ] Monitor `storage/logs/laravel.log` for suspicious activity
- [ ] Rate limiting on API routes (already configured via `ThrottleRequests`)
- [ ] Set file upload size limits in `php.ini` and `.env`
- [ ] Configure fail2ban for brute force protection
- [ ] Regular database backups with offsite storage
- [ ] Keep PHP, MySQL, Redis, and Nginx up to date
- [ ] Use **Spatie Permission** middleware for all sensitive routes
- [ ] Enable **audit logging** for all critical operations
- [ ] Set up **Sentry** or similar error tracking for production

---

## Deployment Script (Zero-Downtime)

```bash
#!/bin/bash
# deploy.sh

set -e

echo "Starting deployment..."

# Enter maintenance mode
php artisan down --render="errors::maintenance" --retry=60

# Pull latest code
git pull origin main

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install & build frontend
npm install --ignore-scripts
npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild cache
php artisan optimize

# Restart queue workers
php artisan queue:restart

# Exit maintenance mode
php artisan up

echo "Deployment complete!"
```

---

## Troubleshooting

### Common Issues

| Issue | Solution |
|-------|----------|
| 500 Server Error | Check `storage/logs/laravel.log` |
| 419 Page Expired | Configure `SESSION_DRIVER` and `SESSION_DOMAIN` |
| 403 Forbidden | Check file permissions |
| White screen | Enable debug mode: `APP_DEBUG=true` |
| Database connection error | Verify DB credentials in `.env` |
| Queue jobs not processing | Ensure queue worker is running |
| Email not sending | Check `MAIL_*` settings and spam folder |
| File upload fails | Check `upload_max_filesize` and `post_max_size` in php.ini |
| Cache not clearing | Run `php artisan optimize:clear` |
