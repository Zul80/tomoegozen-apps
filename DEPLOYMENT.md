# 🚀 Panduan Deployment Tomoe Gozen

## ⚠️ Error yang Sering Terjadi Setelah Deploy

### 1. MissingAppKeyException - "No application encryption key has been specified"

**Penyebab:** File `.env` tidak ada atau `APP_KEY` belum di-generate.

**Solusi:**

```bash
# 1. Pastikan file .env ada di root project
# 2. Generate APP_KEY dengan command:
php artisan key:generate

# Atau jika sudah ada .env tapi APP_KEY kosong:
php artisan key:generate --force
```

**Cara Manual (jika tidak bisa akses SSH):**
1. Login ke hosting/cPanel
2. Buka File Manager
3. Cari file `.env` di root project
4. Pastikan ada baris: `APP_KEY=base64:...` (panjang string)
5. Jika tidak ada, jalankan `php artisan key:generate` via terminal/SSH

---

## 📋 Checklist Deployment Lengkap

### Sebelum Deploy

- [ ] Pastikan PHP versi ≥ 8.2
- [ ] Pastikan Composer terinstall
- [ ] Pastikan Node.js ≥ 18 (untuk build assets)
- [ ] Siapkan database MySQL
- [ ] Siapkan kredensial database

### Langkah-langkah Deploy

#### 1. Upload File ke Server
```bash
# Via Git (recommended)
git clone <repository-url>
cd tomoegozen-apps

# Atau upload via FTP/SFTP ke folder public_html atau www
```

#### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies & build assets
npm ci
npm run build
```

#### 3. Setup Environment File
```bash
# Copy .env.example ke .env (jika belum ada)
cp .env.example .env

# Edit .env dengan kredensial production:
# - DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
# - APP_URL=https://tomoegozen.co.id
# - APP_ENV=production
# - APP_DEBUG=false
# - SANCTUM_STATEFUL_DOMAINS=tomoegozen.co.id
```

#### 4. Generate Application Key
```bash
# ⚠️ PENTING: Ini harus dilakukan!
php artisan key:generate
```

#### 5. Setup Storage Link
```bash
php artisan storage:link
```

#### 6. Setup Database

**Opsi A: Via Laravel Migration (Recommended)**
```bash
php artisan migrate --force

# Jika perlu seed data sample:
php artisan db:seed --class=ShopSeeder
```

**Opsi B: Via SQL File (Alternatif)**
```bash
# Import schema SQL langsung
mysql -u username -p database_name < database/schema.sql

# Atau gunakan quick_setup.sql (tanpa DROP TABLE)
mysql -u username -p database_name < database/quick_setup.sql
```

📖 **Lihat `database/README_DATABASE.md` untuk dokumentasi lengkap struktur database.**

#### 7. Set Permissions (Linux/Unix)
```bash
# Set permissions untuk storage dan cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 8. Optimize Laravel (Production)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 9. Clear Old Caches (jika update)
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## 🔧 Konfigurasi Web Server

### Apache

**⚠️ PENTING:** Pastikan `DocumentRoot` mengarah ke folder `public`:
```apache
DocumentRoot /path/to/tomoegozen-apps/public
```

File `.htaccess` sudah ada di `public/.htaccess` untuk routing Laravel.

### Nginx

```nginx
server {
    listen 80;
    server_name tomoegozen.co.id;
    root /path/to/tomoegozen-apps/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 🌐 Environment Variables yang Wajib

```env
APP_NAME="Tomoe Gozen"
APP_ENV=production
APP_KEY=base64:...  # ⚠️ WAJIB! Generate dengan: php artisan key:generate
APP_DEBUG=false
APP_URL=https://tomoegozen.co.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_db
DB_PASSWORD=password_db

FILESYSTEM_DISK=public
SANCTUM_STATEFUL_DOMAINS=tomoegozen.co.id

# Mail Configuration (sesuaikan dengan provider)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@tomoegozen.co.id"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🔍 Troubleshooting

### Error: "No application encryption key"
**Solusi:** Jalankan `php artisan key:generate`

### Error: "SQLSTATE[HY000] [2002] Connection refused"
**Solusi:** Cek kredensial database di `.env` (DB_HOST, DB_USERNAME, DB_PASSWORD)

### Error: "The stream or file could not be opened"
**Solusi:** 
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan storage:link
```

### Error: "Route [login] not defined"
**Solusi:** Clear route cache: `php artisan route:clear`

### Assets tidak muncul (CSS/JS)
**Solusi:** 
```bash
npm run build
# Pastikan folder public/build ada dan berisi file assets
```

### Upload gambar tidak muncul
**Solusi:**
```bash
php artisan storage:link
# Pastikan symlink storage/app/public -> public/storage sudah ada
```

---

## 📦 Platform-Specific Guides

### cPanel / Shared Hosting

1. Upload semua file ke `public_html` (atau subfolder)
2. Pastikan `public` folder adalah document root
3. Jalankan command via Terminal di cPanel:
   ```bash
   cd ~/public_html
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force
   php artisan storage:link
   ```

### VPS / Cloud Server (Ubuntu/Debian)

```bash
# Install dependencies
sudo apt update
sudo apt install php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl
sudo apt install composer nodejs npm

# Setup project
cd /var/www/tomoegozen-apps
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
```

### Render.com / Railway / DigitalOcean App Platform

Gunakan build command:
```bash
composer install --no-dev --optimize-autoloader && npm ci && npm run build && php artisan key:generate --force && php artisan migrate --force && php artisan storage:link
```

Start command:
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## ✅ Post-Deployment Checklist

- [ ] Akses website: `https://tomoegozen.co.id` bisa dibuka
- [ ] Login admin berhasil: `admin@example.com / Admin123!`
- [ ] Halaman produk muncul
- [ ] Upload gambar berfungsi
- [ ] API endpoints bisa diakses
- [ ] HTTPS aktif (SSL certificate)
- [ ] Error logging aktif (cek `storage/logs/laravel.log`)

---

**Catatan:** Setelah setiap update/deploy, selalu jalankan:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

