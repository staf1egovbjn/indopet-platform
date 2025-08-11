<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $makananCategory = Category::where('slug', 'makanan-hewan')->first();
        $obatCategory = Category::where('slug', 'obat-vitamin')->first();
        $groomingCategory = Category::where('slug', 'peralatan-grooming')->first();
        $aksesorisCategory = Category::where('slug', 'aksesoris')->first();

        $products = [
            // Makanan Hewan
            [
                'category_id' => $makananCategory->id,
                'name' => 'Royal Canin Adult Dog Food',
                'slug' => 'royal-canin-adult-dog-food',
                'description' => 'Makanan anjing dewasa dengan nutrisi lengkap dan seimbang. Diformulasikan khusus untuk memenuhi kebutuhan nutrisi anjing dewasa.',
                'short_description' => 'Makanan anjing dewasa premium dengan nutrisi lengkap',
                'price' => 450000.00,
                'sale_price' => 400000.00,
                'sku' => 'RC-AD-001',
                'stock_quantity' => 50,
                'is_featured' => true,
                'status' => 'active'
            ],
            [
                'category_id' => $makananCategory->id,
                'name' => 'Whiskas Dry Cat Food',
                'slug' => 'whiskas-dry-cat-food',
                'description' => 'Makanan kering untuk kucing dengan rasa tuna dan salmon yang lezat.',
                'short_description' => 'Makanan kucing kering rasa tuna dan salmon',
                'price' => 85000.00,
                'sku' => 'WSK-CF-001',
                'stock_quantity' => 100,
                'is_featured' => true,
                'status' => 'active'
            ],
            // Obat & Vitamin
            [
                'category_id' => $obatCategory->id,
                'name' => 'Blackmores PAW Multivitamin',
                'slug' => 'blackmores-paw-multivitamin',
                'description' => 'Multivitamin lengkap untuk anjing dan kucing. Mengandung vitamin dan mineral penting untuk kesehatan optimal.',
                'short_description' => 'Multivitamin untuk anjing dan kucing',
                'price' => 125000.00,
                'sku' => 'BM-MV-001',
                'stock_quantity' => 30,
                'is_featured' => false,
                'status' => 'active'
            ],
            [
                'category_id' => $obatCategory->id,
                'name' => 'Obat Tetes Mata Hewan',
                'slug' => 'obat-tetes-mata-hewan',
                'description' => 'Obat tetes mata untuk mengatasi iritasi dan infeksi mata pada hewan peliharaan.',
                'short_description' => 'Obat tetes mata untuk hewan peliharaan',
                'price' => 65000.00,
                'sale_price' => 55000.00,
                'sku' => 'EYE-DR-001',
                'stock_quantity' => 25,
                'status' => 'active'
            ],
            // Peralatan Grooming
            [
                'category_id' => $groomingCategory->id,
                'name' => 'Shampoo Anjing Anti Kutu',
                'slug' => 'shampoo-anjing-anti-kutu',
                'description' => 'Shampoo khusus anjing dengan formula anti kutu dan jamur. Aman dan efektif untuk digunakan.',
                'short_description' => 'Shampoo anti kutu untuk anjing',
                'price' => 45000.00,
                'sku' => 'SHP-AK-001',
                'stock_quantity' => 40,
                'is_featured' => true,
                'status' => 'active'
            ],
            [
                'category_id' => $groomingCategory->id,
                'name' => 'Sisir Bulu Kucing',
                'slug' => 'sisir-bulu-kucing',
                'description' => 'Sisir khusus untuk merawat bulu kucing. Mengurangi kerontokan dan menjaga kebersihan bulu.',
                'short_description' => 'Sisir perawatan bulu kucing',
                'price' => 35000.00,
                'sku' => 'CMB-CT-001',
                'stock_quantity' => 60,
                'status' => 'active'
            ],
            // Aksesoris
            [
                'category_id' => $aksesorisCategory->id,
                'name' => 'Kalung Anjing LED',
                'slug' => 'kalung-anjing-led',
                'description' => 'Kalung anjing dengan lampu LED yang dapat berkedip. Cocok untuk jalan malam hari.',
                'short_description' => 'Kalung anjing dengan lampu LED',
                'price' => 75000.00,
                'sale_price' => 65000.00,
                'sku' => 'COL-LED-001',
                'stock_quantity' => 35,
                'status' => 'active'
            ],
            [
                'category_id' => $aksesorisCategory->id,
                'name' => 'Mainan Kucing Bola Bulu',
                'slug' => 'mainan-kucing-bola-bulu',
                'description' => 'Mainan bola bulu untuk kucing. Membantu kucing tetap aktif dan terhibur.',
                'short_description' => 'Mainan bola bulu untuk kucing',
                'price' => 25000.00,
                'sku' => 'TOY-FB-001',
                'stock_quantity' => 80,
                'is_featured' => false,
                'status' => 'active'
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
