-- Sample Products SQL dari folder uploads
-- Pastikan untuk menjalankan ini setelah migrations

-- 1. Insert Categories (jika belum ada)
INSERT INTO `categories` (`name`, `slug`, `description`, `hero_image`, `is_active`, `created_at`, `updated_at`) VALUES
('Signature Series', 'signature-series', 'Curated Japanese streetwear inspired collections.', '/images/collections/signature-series.svg', 1, NOW(), NOW()),
('Limited Drops', 'limited-drops', 'Exclusive limited edition drops.', '/images/collections/limited-drops.svg', 1, NOW(), NOW()),
('Core Essentials', 'core-essentials', 'Essential pieces for your wardrobe.', '/images/collections/core-essentials.svg', 1, NOW(), NOW()),
('Collabs', 'collabs', 'Special collaboration collections.', '/images/collections/collabs.svg', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 2. Insert Collection (jika belum ada)
INSERT INTO `collections` (`title`, `slug`, `description`, `hero_image`, `is_featured`, `created_at`, `updated_at`) VALUES
('Noir Tokyo Drop', 'noir-tokyo', 'Monochrome silhouettes with bold red accents.', '/images/collections/noir-tokyo.svg', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 3. Insert Products dari folder uploads
-- Menggunakan category_id = 1 dan collection_id = 1 (sesuaikan jika berbeda)

INSERT INTO `products` (`sku`, `name`, `slug`, `category_id`, `collection_id`, `description`, `price`, `sale_price`, `currency`, `colors`, `sizes`, `stock_by_size`, `tags`, `image_url`, `is_featured`, `created_at`, `updated_at`) VALUES
('TGZ-UPL-001', 'Tomoe Oversize Tee - Design 1', 'tomoe-oversize-tee-design-1', 1, 1, 'Oversize cotton tee with premium Japanese design. Relaxed fit, pre-shrunk fabric.', 299000, 249000, 'IDR', '["black", "red"]', '["M", "L", "XL", "XXL"]', '{"M": 12, "L": 10, "XL": 8, "XXL": 6}', '["oversize", "japan", "premium"]', '/uploads/image-1762235708210-vxx8j.png', 1, NOW(), NOW()),

('TGZ-UPL-002', 'Tomoe Oversize Tee - Design 2', 'tomoe-oversize-tee-design-2', 1, 1, 'Oversize cotton tee with unique Japanese streetwear aesthetic. Comfortable and stylish.', 289000, NULL, 'IDR', '["black", "white"]', '["S", "M", "L", "XL"]', '{"S": 10, "M": 15, "L": 10, "XL": 7}', '["oversize", "streetwear"]', '/uploads/image-1762235813458-570i1q.png', 0, NOW(), NOW()),

('TGZ-UPL-003', 'Tomoe Oversize Tee - Design 3', 'tomoe-oversize-tee-design-3', 2, 1, 'Limited edition oversize tee with exclusive design. Limited stock available.', 310000, 279000, 'IDR', '["black", "neon"]', '["M", "L", "XL"]', '{"M": 11, "L": 11, "XL": 8}', '["limited", "exclusive"]', '/uploads/image-1762236209570-temhub.png', 1, NOW(), NOW()),

('TGZ-UPL-004', 'Tomoe Oversize Tee - Design 4', 'tomoe-oversize-tee-design-4', 1, 1, 'Premium oversize tee with Japanese inspired graphics. High quality cotton blend.', 295000, NULL, 'IDR', '["white", "charcoal"]', '["M", "L", "XL", "XXL"]', '{"M": 14, "L": 12, "XL": 9, "XXL": 5}', '["premium", "japan"]', '/uploads/image-1762236214108-2Rp3v.png', 0, NOW(), NOW()),

('TGZ-UPL-005', 'Tomoe Oversize Tee - Design 5', 'tomoe-oversize-tee-design-5', 3, 1, 'Essential oversize tee with minimalist design. Perfect for everyday wear.', 275000, 249000, 'IDR', '["black", "slate"]', '["S", "M", "L", "XL"]', '{"S": 8, "M": 13, "L": 11, "XL": 6}', '["essential", "minimalist"]', '/uploads/image-1762239219386-q9gxv.png', 0, NOW(), NOW()),

('TGZ-UPL-006', 'Tomoe Oversize Tee - Design 6', 'tomoe-oversize-tee-design-6', 2, 1, 'Limited drop oversize tee with unique pattern. Get yours before it\'s gone.', 320000, 289000, 'IDR', '["midnight", "crimson"]', '["M", "L", "XL", "XXL"]', '{"M": 9, "L": 8, "XL": 7, "XXL": 4}', '["limited", "drop"]', '/uploads/image-1762239229117-i5ag7w.png', 1, NOW(), NOW()),

('TGZ-UPL-007', 'Tomoe Oversize Tee - Design 7', 'tomoe-oversize-tee-design-7', 1, 1, 'Signature series oversize tee with premium design. Comfort meets style.', 305000, NULL, 'IDR', '["black", "red"]', '["S", "M", "L", "XL", "XXL"]', '{"S": 7, "M": 12, "L": 10, "XL": 8, "XXL": 5}', '["signature", "premium"]', '/uploads/image-1762239236773-ufcmpb.png', 1, NOW(), NOW()),

('TGZ-UPL-008', 'Tomoe Oversize Tee - Design 8', 'tomoe-oversize-tee-design-8', 3, 1, 'Core essential oversize tee. Versatile design for any occasion.', 280000, 259000, 'IDR', '["white", "black"]', '["M", "L", "XL"]', '{"M": 15, "L": 13, "XL": 10}', '["core", "essential"]', '/uploads/image-1762331921896-32af3q.png', 0, NOW(), NOW()),

('TGZ-UPL-009', 'Tomoe Oversize Tee - Design 9', 'tomoe-oversize-tee-design-9', 4, 1, 'Special collaboration oversize tee. Unique design from our partners.', 315000, 299000, 'IDR', '["black", "white", "red"]', '["M", "L", "XL", "XXL"]', '{"M": 10, "L": 9, "XL": 7, "XXL": 6}', '["collab", "special"]', '/uploads/image-1762331929075-2khn7.png', 1, NOW(), NOW()),

('TGZ-UPL-010', 'Tomoe Oversize Tee - Design 10', 'tomoe-oversize-tee-design-10', 1, 1, 'Signature oversize tee with bold graphics. Make a statement.', 300000, NULL, 'IDR', '["black", "charcoal"]', '["S", "M", "L", "XL"]', '{"S": 9, "M": 11, "L": 9, "XL": 6}', '["signature", "bold"]', '/uploads/image-1762331933907-iaxxbe.png', 0, NOW(), NOW()),

('TGZ-UPL-011', 'Tomoe Oversize Tee - Design 11', 'tomoe-oversize-tee-design-11', 2, 1, 'Limited edition oversize tee. Exclusive design for true collectors.', 325000, 299000, 'IDR', '["midnight", "slate"]', '["M", "L", "XL"]', '{"M": 8, "L": 7, "XL": 5}', '["limited", "collector"]', '/uploads/image-1762332057474-60ooy.png', 1, NOW(), NOW()),

('TGZ-UPL-012', 'Tomoe Oversize Tee - Design 12', 'tomoe-oversize-tee-design-12', 3, 1, 'Essential oversize tee with clean design. Perfect for layering.', 285000, 269000, 'IDR', '["white", "slate"]', '["S", "M", "L", "XL", "XXL"]', '{"S": 6, "M": 14, "L": 12, "XL": 9, "XXL": 4}', '["essential", "clean"]', '/uploads/image-1762410873621-veyg8t.png', 0, NOW(), NOW()),

('TGZ-UPL-013', 'Tomoe Oversize Tee - Design 13', 'tomoe-oversize-tee-design-13', 1, 1, 'Signature series oversize tee. Premium quality with unique design.', 298000, NULL, 'IDR', '["black", "crimson"]', '["M", "L", "XL", "XXL"]', '{"M": 13, "L": 11, "XL": 8, "XXL": 5}', '["signature", "unique"]', '/uploads/image-1762418553969-csccvn.png', 1, NOW(), NOW()),

('TGZ-UPL-014', 'Tomoe Oversize Tee - Design 14', 'tomoe-oversize-tee-design-14', 4, 1, 'Collaboration oversize tee. Special edition from our design partners.', 312000, 289000, 'IDR', '["white", "red"]', '["M", "L", "XL"]', '{"M": 11, "L": 10, "XL": 7}', '["collab", "special"]', '/uploads/image-1762419140284-vla56.png', 0, NOW(), NOW()),

('TGZ-UPL-015', 'Tomoe Oversize Tee - Design 15', 'tomoe-oversize-tee-design-15', 2, 1, 'Limited drop oversize tee. Exclusive design with premium materials.', 318000, 299000, 'IDR', '["black", "neon", "red"]', '["M", "L", "XL", "XXL"]', '{"M": 9, "L": 8, "XL": 6, "XXL": 4}', '["limited", "premium"]', '/uploads/image-1762419231072-ey9z3e.png', 1, NOW(), NOW()),

('TGZ-UPL-016', 'Tomoe Oversize Tee - Design 16', 'tomoe-oversize-tee-design-16', 1, 1, 'Signature oversize tee with Japanese aesthetic. Comfortable and stylish.', 302000, NULL, 'IDR', '["charcoal", "crimson"]', '["S", "M", "L", "XL"]', '{"S": 8, "M": 12, "L": 10, "XL": 7}', '["signature", "japan"]', '/uploads/image-1762419278233-5lcgz.png', 0, NOW(), NOW()),

('TGZ-UPL-017', 'Tomoe Oversize Tee - Design 17', 'tomoe-oversize-tee-design-17', 3, 1, 'Core essential oversize tee. Versatile piece for your wardrobe.', 288000, 269000, 'IDR', '["black", "white"]', '["M", "L", "XL", "XXL"]', '{"M": 15, "L": 13, "XL": 10, "XXL": 6}', '["core", "versatile"]', '/uploads/image-1762419278327-b3jwq.png', 0, NOW(), NOW()),

('TGZ-UPL-018', 'Tomoe Oversize Tee - Design 18', 'tomoe-oversize-tee-design-18', 2, 1, 'Limited edition oversize tee. Get yours before stock runs out.', 322000, 299000, 'IDR', '["midnight", "slate", "red"]', '["M", "L", "XL"]', '{"M": 10, "L": 9, "XL": 7}', '["limited", "exclusive"]', '/uploads/image-1762419290002-wk9eu.png', 1, NOW(), NOW()),

('TGZ-UPL-019', 'Tomoe Oversize Tee - Design 19', 'tomoe-oversize-tee-design-19', 1, 1, 'Signature series oversize tee. Premium design with attention to detail.', 304000, NULL, 'IDR', '["black", "charcoal", "crimson"]', '["S", "M", "L", "XL", "XXL"]', '{"S": 7, "M": 13, "L": 11, "XL": 8, "XXL": 5}', '["signature", "detail"]', '/uploads/image-1762419301156-x6cvt.png', 1, NOW(), NOW()),

('TGZ-UPL-020', 'Tomoe Oversize Tee - Design 20', 'tomoe-oversize-tee-design-20', 4, 1, 'Collaboration oversize tee. Unique design from special partnership.', 316000, 289000, 'IDR', '["white", "black", "red"]', '["M", "L", "XL", "XXL"]', '{"M": 11, "L": 10, "XL": 8, "XXL": 5}', '["collab", "unique"]', '/uploads/image-1762419307974-z5k9z.png', 0, NOW(), NOW()),

('TGZ-UPL-021', 'Tomoe Oversize Tee - Design 21', 'tomoe-oversize-tee-design-21', 3, 1, 'Essential oversize tee with minimalist approach. Clean and simple.', 283000, 259000, 'IDR', '["slate", "white"]', '["S", "M", "L", "XL"]', '{"S": 9, "M": 14, "L": 12, "XL": 9}', '["essential", "minimalist"]', '/uploads/image-1762419308061-tlrjx.png', 0, NOW(), NOW()),

('TGZ-UPL-022', 'Tomoe Oversize Tee - Design 22', 'tomoe-oversize-tee-design-22', 2, 1, 'Limited drop oversize tee. Exclusive design for true fans.', 319000, 299000, 'IDR', '["black", "neon"]', '["M", "L", "XL"]', '{"M": 8, "L": 7, "XL": 5}', '["limited", "exclusive"]', '/uploads/image-1762419572890-3gk0o.png', 1, NOW(), NOW()),

('TGZ-UPL-023', 'Tomoe Oversize Tee - Design 23', 'tomoe-oversize-tee-design-23', 1, 1, 'Signature oversize tee with bold graphics. Stand out from the crowd.', 301000, NULL, 'IDR', '["charcoal", "crimson", "red"]', '["M", "L", "XL", "XXL"]', '{"M": 12, "L": 10, "XL": 8, "XXL": 6}', '["signature", "bold"]', '/uploads/image-1762419573092-za4jcl.png', 1, NOW(), NOW()),

('TGZ-UPL-024', 'Tomoe Oversize Tee - Design 24', 'tomoe-oversize-tee-design-24', 4, 1, 'Special collaboration oversize tee. Limited edition partnership design.', 314000, 289000, 'IDR', '["midnight", "white", "red"]', '["S", "M", "L", "XL"]', '{"S": 6, "M": 11, "L": 9, "XL": 7}', '["collab", "limited"]', '/uploads/image-1763019380420-oipzq.png', 1, NOW(), NOW()),

('TGZ-UPL-025', 'Tomoe Oversize Tee - Design 25', 'tomoe-oversize-tee-design-25', 3, 1, 'Core essential oversize tee. Perfect for everyday comfort and style.', 287000, 269000, 'IDR', '["black", "slate", "white"]', '["M", "L", "XL", "XXL"]', '{"M": 14, "L": 12, "XL": 9, "XXL": 5}', '["core", "comfort"]', '/uploads/image-1763094414582-oemt7h.png', 0, NOW(), NOW());

-- Catatan:
-- 1. File gambar ada di resources/views/uploads/ - Anda perlu memindahkannya ke public/uploads/ atau storage/app/public/uploads/
-- 2. Jika menggunakan storage link (php artisan storage:link), path menjadi /storage/uploads/...
-- 3. Jika file di public/uploads/, gunakan path /uploads/...
-- 4. category_id dan collection_id diasumsikan menggunakan ID 1 (sesuaikan jika berbeda setelah insert categories/collections)
-- 5. Harga dalam IDR (Rupiah)
-- 6. JSON fields (colors, sizes, stock_by_size, tags) sudah diformat untuk MySQL JSON
-- 7. Untuk menjalankan: mysql -u root -p tomoegozen < database/sample_products_from_uploads.sql
--    atau import via phpMyAdmin / MySQL Workbench

