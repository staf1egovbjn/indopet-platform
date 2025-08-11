<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Makanan Hewan',
                'slug' => 'makanan-hewan',
                'description' => 'Berbagai jenis makanan untuk anjing, kucing, dan hewan peliharaan lainnya',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Obat & Vitamin',
                'slug' => 'obat-vitamin',
                'description' => 'Obat-obatan dan vitamin untuk menjaga kesehatan hewan peliharaan',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Peralatan Grooming',
                'slug' => 'peralatan-grooming',
                'description' => 'Alat-alat untuk merawat dan membersihkan hewan peliharaan',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Aksesoris',
                'slug' => 'aksesoris',
                'description' => 'Kalung, tali, mainan, dan aksesoris lainnya untuk hewan peliharaan',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Kandang & Tempat Tidur',
                'slug' => 'kandang-tempat-tidur',
                'description' => 'Kandang, tempat tidur, dan tempat istirahat untuk hewan peliharaan',
                'is_active' => true,
                'sort_order' => 5
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
