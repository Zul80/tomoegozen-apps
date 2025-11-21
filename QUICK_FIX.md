# 🔧 Quick Fix: Error Setelah Deploy

## 1. MissingAppKeyException

## ⚠️ Error yang Terjadi
```
Illuminate\Encryption\MissingAppKeyException
No application encryption key has been specified.
```

## ✅ Solusi Cepat (3 Langkah)

### Opsi 1: Via SSH/Terminal (Recommended)

```bash
# 1. Masuk ke folder project di server
cd /path/to/tomoegozen-apps

# 2. Pastikan file .env ada
# Jika belum ada, copy dari .env.example:
cp .env.example .env

# 3. Generate APP_KEY
php artisan key:generate --force
```

**Selesai!** Refresh browser, error seharusnya sudah hilang.

---

### Opsi 2: Via cPanel File Manager

1. **Login ke cPanel**
2. **Buka File Manager**
3. **Cari file `.env` di root project**
   - Jika tidak ada, buat file baru bernama `.env`
   - Copy isi dari `.env.example` (jika ada)
4. **Buka Terminal di cPanel** (atau SSH)
5. **Jalankan command:**
   ```bash
   cd ~/public_html  # atau path project Anda
   php artisan key:generate --force
   ```

---

### Opsi 3: Manual Edit .env (Tidak Recommended)

Jika tidak bisa akses terminal, Anda bisa generate key secara manual:

1. **Generate key di local/development:**
   ```bash
   php artisan key:generate
   ```
   
2. **Copy baris `APP_KEY=base64:...` dari `.env` local**

3. **Paste ke file `.env` di server**

⚠️ **Catatan:** Metode ini kurang aman, gunakan Opsi 1 atau 2 jika memungkinkan.

---

## 🔍 Verifikasi

Setelah generate key, pastikan di file `.env` ada baris seperti ini:

```env
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

Panjang string setelah `base64:` biasanya sekitar 44-88 karakter.

---

## 📋 Checklist Lengkap Setelah Fix

- [ ] `APP_KEY` sudah di-generate
- [ ] File `.env` ada di root project
- [ ] Database credentials sudah benar di `.env`
- [ ] `APP_URL` sudah di-set ke domain production
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] Storage link sudah dibuat: `php artisan storage:link`
- [ ] Migrations sudah dijalankan: `php artisan migrate --force`

---

## 🚨 Masih Error?

Jika masih error setelah generate key:

1. **Clear config cache:**
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

2. **Pastikan file `.env` readable:**
   ```bash
   chmod 644 .env
   ```

3. **Cek apakah ada file `.env` lain yang conflict:**
   ```bash
   ls -la | grep .env
   ```

4. **Restart web server/PHP-FPM:**
   ```bash
   # Nginx
   sudo systemctl restart nginx
   sudo systemctl restart php8.2-fpm
   
   # Apache
   sudo systemctl restart apache2
   ```

---

---

## 2. Error: "Failed to open stream: public/index.php: No such file or directory"

**Penyebab:** File `public/index.php` tidak ada (struktur Laravel 11+ berbeda).

**Solusi:**

File `public/index.php` sudah dibuat. Jika masih error:

1. **Pastikan file ada:**
   ```bash
   ls -la public/index.php
   ```

2. **Pastikan web server mengarah ke folder `public`:**
   - Apache: `DocumentRoot` harus ke `/path/to/tomoegozen-apps/public`
   - Nginx: `root` harus ke `/path/to/tomoegozen-apps/public`

3. **Jika menggunakan `php artisan serve`:**
   ```bash
   php artisan serve
   # Server akan otomatis menggunakan public/index.php
   ```

---

**Butuh bantuan lebih lanjut?** Cek file `DEPLOYMENT.md` untuk panduan lengkap.

