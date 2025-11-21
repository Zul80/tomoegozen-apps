# 📊 Database Schema Documentation

## 📁 File SQL

### `schema.sql`
File SQL lengkap untuk membuat semua tabel database. File ini bisa langsung di-import ke MySQL/MariaDB.

## 🚀 Cara Import Database

### Opsi 1: Via Command Line (MySQL)

```bash
# Login ke MySQL
mysql -u root -p

# Buat database (jika belum ada)
CREATE DATABASE tomoegozen_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tomoegozen_db;

# Import schema
source database/schema.sql;

# Atau langsung dari command line:
mysql -u root -p tomoegozen_db < database/schema.sql
```

### Opsi 2: Via phpMyAdmin

1. Login ke phpMyAdmin
2. Pilih database (atau buat baru)
3. Klik tab **Import**
4. Pilih file `database/schema.sql`
5. Klik **Go** / **Import**

### Opsi 3: Via Laravel Migration (Recommended)

```bash
# Jalankan migration (akan membuat semua tabel)
php artisan migrate

# Jika perlu seed data sample:
php artisan db:seed --class=ShopSeeder
```

## 📋 Struktur Tabel

### Core Tables

#### `users`
- **Deskripsi:** Tabel user/admin/customer
- **Kolom penting:**
  - `id` - Primary key
  - `email` - Unique, untuk login
  - `role` - Enum: 'admin' atau 'customer'
  - `password` - Hashed password
- **Indexes:** `email` (unique), `username` (unique)

#### `categories`
- **Deskripsi:** Kategori produk
- **Kolom penting:**
  - `id` - Primary key
  - `name` - Nama kategori
  - `slug` - URL-friendly identifier (unique)
  - `is_active` - Status aktif/tidak
- **Indexes:** `slug` (unique)

#### `collections`
- **Deskripsi:** Koleksi produk (series, drops, dll)
- **Kolom penting:**
  - `id` - Primary key
  - `title` - Judul koleksi
  - `slug` - URL-friendly identifier (unique)
  - `is_featured` - Tampilkan di homepage
- **Indexes:** `slug` (unique)

#### `products`
- **Deskripsi:** Produk/barang yang dijual
- **Kolom penting:**
  - `id` - Primary key
  - `sku` - Stock Keeping Unit (unique)
  - `name` - Nama produk
  - `slug` - URL-friendly identifier (unique)
  - `category_id` - Foreign key ke `categories`
  - `collection_id` - Foreign key ke `collections` (nullable)
  - `price` - Harga normal (bigint, dalam satuan terkecil)
  - `sale_price` - Harga diskon (nullable)
  - `currency` - Mata uang (default: 'IDR')
  - `colors` - JSON array warna tersedia
  - `sizes` - JSON array ukuran tersedia
  - `stock_by_size` - JSON object: `{"M": 10, "L": 5, ...}`
  - `tags` - JSON array tags
  - `image_url` - URL gambar produk
  - `is_featured` - Tampilkan di homepage
- **Foreign Keys:**
  - `category_id` → `categories.id` (CASCADE DELETE)
  - `collection_id` → `collections.id` (SET NULL ON DELETE)
- **Indexes:** `sku` (unique), `slug` (unique), `category_id`, `collection_id`

#### `carts`
- **Deskripsi:** Keranjang belanja
- **Kolom penting:**
  - `id` - Primary key
  - `user_id` - Foreign key ke `users` (nullable, untuk guest cart)
  - `session_id` - UUID untuk guest cart (unique)
  - `currency` - Mata uang
  - `items_count` - Jumlah item di cart
  - `subtotal` - Total harga
- **Foreign Keys:**
  - `user_id` → `users.id` (SET NULL ON DELETE)
- **Indexes:** `session_id` (unique), `user_id`

#### `cart_items`
- **Deskripsi:** Item-item dalam keranjang
- **Kolom penting:**
  - `id` - Primary key
  - `cart_id` - Foreign key ke `carts`
  - `product_id` - Foreign key ke `products`
  - `sku`, `name`, `color`, `size` - Snapshot data produk
  - `quantity` - Jumlah item
  - `unit_price` - Harga per unit
  - `line_total` - Total harga (quantity × unit_price)
- **Foreign Keys:**
  - `cart_id` → `carts.id` (CASCADE DELETE)
  - `product_id` → `products.id` (CASCADE DELETE)
- **Indexes:** `cart_id`, `product_id`

#### `orders`
- **Deskripsi:** Pesanan/order
- **Kolom penting:**
  - `id` - Primary key
  - `order_number` - Nomor order (unique)
  - `user_id` - Foreign key ke `users` (nullable)
  - `status` - Enum: 'pending', 'processing', 'shipped', 'delivered', 'cancelled'
  - `payment_status` - Enum: 'unpaid', 'paid', 'refunded'
  - `subtotal` - Subtotal harga
  - `discount_total` - Total diskon
  - `tax_total` - Total pajak
  - `grand_total` - Total akhir
  - `currency` - Mata uang
  - `items_count` - Jumlah item
  - `shipping_address` - JSON alamat pengiriman
- **Foreign Keys:**
  - `user_id` → `users.id` (SET NULL ON DELETE)
- **Indexes:** `order_number` (unique), `user_id`

#### `order_items`
- **Deskripsi:** Item-item dalam pesanan
- **Kolom penting:**
  - `id` - Primary key
  - `order_id` - Foreign key ke `orders`
  - `product_id` - Foreign key ke `products`
  - `sku`, `name`, `color`, `size` - Snapshot data produk
  - `quantity` - Jumlah item
  - `unit_price` - Harga per unit saat order
  - `line_total` - Total harga
- **Foreign Keys:**
  - `order_id` → `orders.id` (CASCADE DELETE)
  - `product_id` → `products.id` (CASCADE DELETE)
- **Indexes:** `order_id`, `product_id`

### System Tables

#### `sessions`
- Session storage untuk Laravel
- **Indexes:** `user_id`, `last_activity`

#### `cache` & `cache_locks`
- Cache storage untuk Laravel
- **Indexes:** `key` (primary)

#### `jobs`, `job_batches`, `failed_jobs`
- Queue system untuk background jobs
- **Indexes:** `queue` (jobs), `uuid` (failed_jobs, unique)

#### `password_reset_tokens`
- Token untuk reset password
- **Indexes:** `email` (primary)

## 🔗 Relasi Tabel

```
users
  ├── orders (1:N)
  └── carts (1:N)

categories
  └── products (1:N)

collections
  └── products (1:N, nullable)

products
  ├── cart_items (1:N)
  └── order_items (1:N)

carts
  └── cart_items (1:N)

orders
  └── order_items (1:N)
```

## 💡 Tips

### 1. Harga Disimpan sebagai BigInt
Harga disimpan dalam satuan terkecil (misal: 299000 untuk Rp 299.000). Ini untuk menghindari masalah floating point.

### 2. JSON Columns
Beberapa kolom menggunakan JSON:
- `colors`, `sizes`, `tags` di `products`
- `stock_by_size` di `products`
- `shipping_address` di `orders` dan `users`
- `meta` di berbagai tabel

### 3. Soft Delete vs Hard Delete
- **CASCADE DELETE:** `products` → `cart_items`, `order_items`
- **SET NULL:** `users` → `orders`, `carts` (jika user dihapus, order tetap ada)
- **CASCADE DELETE:** `orders` → `order_items` (jika order dihapus, items ikut terhapus)

### 4. Session Cart vs User Cart
- Jika `user_id` ada → cart milik user (persistent)
- Jika `session_id` ada → cart guest (temporary)

## 🔍 Query Examples

### Get all products with category
```sql
SELECT p.*, c.name as category_name 
FROM products p 
JOIN categories c ON p.category_id = c.id;
```

### Get user's active cart with items
```sql
SELECT c.*, ci.*, p.name as product_name
FROM carts c
LEFT JOIN cart_items ci ON c.id = ci.cart_id
LEFT JOIN products p ON ci.product_id = p.id
WHERE c.user_id = 1;
```

### Get orders with total items
```sql
SELECT o.*, COUNT(oi.id) as total_items
FROM orders o
LEFT JOIN order_items oi ON o.id = oi.order_id
GROUP BY o.id;
```

## 📝 Notes

- Semua tabel menggunakan `utf8mb4` charset untuk support emoji dan special characters
- Timestamps (`created_at`, `updated_at`) otomatis di-handle oleh Laravel
- Foreign keys menggunakan constraint untuk data integrity
- Indexes sudah dioptimasi untuk query umum

