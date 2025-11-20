<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Signature Series', 'slug' => 'signature-series'],
            ['name' => 'Limited Drops', 'slug' => 'limited-drops'],
            ['name' => 'Core Essentials', 'slug' => 'core-essentials'],
            ['name' => 'Collabs', 'slug' => 'collabs'],
        ])->map(fn ($category) => Category::updateOrCreate(
            ['slug' => $category['slug']],
            [
                'name' => $category['name'],
                'description' => 'Curated Japanese streetwear inspired collections.',
                'hero_image' => "/images/collections/{$category['slug']}.svg",
                'is_active' => true,
            ],
        ));

        $featuredCollection = Collection::updateOrCreate(
            ['slug' => 'noir-tokyo'],
            [
                'title' => 'Noir Tokyo Drop',
                'description' => 'Monochrome silhouettes with bold red accents.',
                'hero_image' => '/images/collections/noir-tokyo.svg',
                'is_featured' => true,
            ],
        );

        $productsPayload = $this->buildProductsPayload();
        $products = collect($productsPayload)->map(function (array $payload) use ($categories, $featuredCollection) {
            $category = $categories->random();

            return Product::updateOrCreate(
                ['sku' => $payload['sku']],
                array_merge($payload, [
                    'category_id' => $category->id,
                    'collection_id' => $featuredCollection->id,
                ]),
            );
        });

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Shop Admin',
                'username' => 'admin',
                'role' => 'admin',
                'password' => Hash::make('Admin123!'),
            ],
        );

        $customer = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'First Customer',
                'username' => 'customer',
                'role' => 'customer',
                'password' => Hash::make('User123!'),
            ],
        );

        $this->seedOrders($products, $customer);

        $this->command?->info('ShopSeeder finished creating products, users, and orders.');
    }

    private function buildProductsPayload(): array
    {
        $baseProducts = [
            [
                "sku" => "TGZ-001",
                "name" => "Tomoe Oversize Tee — Sakura Blade",
                "slug" => "tomoe-oversize-tee-sakura-blade",
                "description" => "Oversize cotton tee with Japanese sakura & blade print. Relaxed fit, pre-shrunk.",
                "price" => 299000,
                "sale_price" => 249000,
                "currency" => "IDR",
                "colors" => ["black", "red"],
                "sizes" => ["M", "L", "XL", "XXL"],
                "stock_by_size" => ["M" => 12, "L" => 10, "XL" => 8, "XXL" => 6],
                "tags" => ["sakura", "japan", "oversize"],
                "image_url" => "/images/products/tg01.svg",
                "is_featured" => true,
            ],
            [
                "sku" => "TGZ-002",
                "name" => "Kitsune Shadow Tee",
                "slug" => "kitsune-shadow-tee",
                "description" => "Shadow fox illustration with reflective ink layering.",
                "price" => 289000,
                "sale_price" => null,
                "currency" => "IDR",
                "colors" => ["black", "white"],
                "sizes" => ["S", "M", "L", "XL", "XXL"],
                "stock_by_size" => ["S" => 10, "M" => 15, "L" => 10, "XL" => 7, "XXL" => 5],
                "tags" => ["kitsune", "reflective"],
                "image_url" => "/images/products/tg02.svg",
                "is_featured" => true,
            ],
            [
                "sku" => "TGZ-003",
                "name" => "Shibuya Overdrive",
                "slug" => "shibuya-overdrive",
                "description" => "Neon gradient print inspired by Shibuya crossing nights.",
                "price" => 310000,
                "sale_price" => 279000,
                "currency" => "IDR",
                "colors" => ["black", "neon"],
                "sizes" => ["M", "L", "XL"],
                "stock_by_size" => ["M" => 11, "L" => 11, "XL" => 8],
                "tags" => ["shibuya", "neon"],
                "image_url" => "/images/products/tg03.svg",
                "is_featured" => false,
            ],
        ];

        $generated = [];
        $sizeSets = [
            ["S", "M", "L", "XL", "XXL"],
            ["M", "L", "XL"],
            ["L", "XL", "XXL"],
        ];
        $colorSets = [
            ["black", "red"],
            ["white", "charcoal"],
            ["midnight", "crimson"],
            ["slate", "scarlet"],
        ];

        for ($i = 4; $i <= 30; $i++) {
            $sizes = Arr::random($sizeSets);
            $stock = collect($sizes)->mapWithKeys(fn ($size) => [$size => random_int(5, 20)])->toArray();
            $colorSet = Arr::random($colorSets);
            $slug = Str::slug("neo drop {$i}");

            $generated[] = [
                "sku" => sprintf('TGZ-%03d', $i),
                "name" => "Neo Drop {$i}",
                "slug" => $slug,
                "description" => "Limited run oversized tee {$i} with textured puff print.",
                "price" => random_int(280000, 350000),
                "sale_price" => random_int(0, 1) ? random_int(250000, 299000) : null,
                "currency" => "IDR",
                "colors" => $colorSet,
                "sizes" => $sizes,
                "stock_by_size" => $stock,
                "tags" => ["neo", "drop", "oversize"],
                "image_url" => "/images/products/tg" . str_pad((string) (($i % 5) + 1), 2, '0', STR_PAD_LEFT) . ".svg",
                "is_featured" => $i % 5 === 0,
            ];
        }

        return array_merge($baseProducts, $generated);
    }

    private function seedOrders($products, User $customer): void
    {
        $products = $products instanceof \Illuminate\Support\Collection ? $products : collect($products);

        for ($i = 1; $i <= 10; $i++) {
            $selected = $products->random(3);
            $subtotal = 0;
            $itemsPayload = [];

            foreach ($selected as $product) {
                $quantity = random_int(1, 3);
                $unitPrice = $product->sale_price ?? $product->price;
                $lineTotal = $unitPrice * $quantity;
                $subtotal += $lineTotal;

                $itemsPayload[] = [
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'color' => Arr::first($product->colors),
                    'size' => Arr::first($product->sizes),
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $order = Order::updateOrCreate(
                ['order_number' => 'TGZ-ORD-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT)],
                [
                    'user_id' => $customer->id,
                    'status' => Arr::random(['pending', 'processing', 'shipped']),
                    'payment_status' => 'paid',
                    'subtotal' => $subtotal,
                    'grand_total' => $subtotal,
                    'items_count' => count($itemsPayload),
                    'currency' => 'IDR',
                    'shipping_address' => [
                        'line1' => 'Jl. Senopati No. ' . $i,
                        'city' => 'Jakarta',
                        'postal_code' => '12190',
                        'country' => 'ID',
                    ],
                ],
            );

            foreach ($itemsPayload as $payload) {
                OrderItem::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'product_id' => $payload['product_id'],
                    ],
                    $payload + ['order_id' => $order->id],
                );
            }
        }
    }
}

