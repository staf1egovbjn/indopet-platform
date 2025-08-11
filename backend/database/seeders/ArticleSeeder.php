<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@indopet.com')->first();

        $articles = [
            [
                'user_id' => $adminUser->id,
                'title' => '5 Tips Merawat Kesehatan Anjing di Musim Hujan',
                'slug' => '5-tips-merawat-kesehatan-anjing-di-musim-hujan',
                'excerpt' => 'Musim hujan dapat menjadi tantangan tersendiri dalam merawat kesehatan anjing peliharaan. Berikut tips penting yang perlu Anda ketahui.',
                'content' => '<p>Musim hujan seringkali membawa tantangan khusus dalam merawat hewan peliharaan, terutama anjing. Kelembapan udara yang tinggi dan lingkungan yang basah dapat meningkatkan risiko berbagai masalah kesehatan.</p>

<h3>1. Jaga Kebersihan Kaki dan Bulu</h3>
<p>Setelah berjalan-jalan di luar saat hujan, pastikan untuk membersihkan kaki anjing dengan handuk kering atau lap basah. Keringkan juga bulu yang basah untuk mencegah pertumbuhan jamur.</p>

<h3>2. Perhatikan Makanan dan Air Minum</h3>
<p>Pastikan makanan anjing disimpan di tempat yang kering dan tidak lembap. Ganti air minum secara regular untuk mencegah kontaminasi bakteri.</p>

<h3>3. Vaksinasi dan Vitamin</h3>
<p>Konsultasikan dengan dokter hewan mengenai jadwal vaksinasi dan pemberian vitamin untuk meningkatkan daya tahan tubuh anjing.</p>

<h3>4. Lingkungan yang Nyaman</h3>
<p>Sediakan tempat tidur yang hangat dan kering. Hindari membiarkan anjing tidur di lantai yang lembap.</p>

<h3>5. Rutin Periksa Kesehatan</h3>
<p>Lakukan pemeriksaan rutin ke dokter hewan, terutama jika anjing menunjukkan gejala tidak biasa seperti batuk, bersin, atau lesu.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'is_featured' => true,
                'tags' => ['anjing', 'kesehatan', 'musim hujan', 'perawatan'],
                'views' => 150,
                'meta_title' => '5 Tips Merawat Kesehatan Anjing di Musim Hujan - IndoPet',
                'meta_description' => 'Pelajari cara merawat kesehatan anjing peliharaan saat musim hujan. Tips praktis untuk menjaga anjing tetap sehat dan nyaman.'
            ],
            [
                'user_id' => $adminUser->id,
                'title' => 'Nutrisi Penting untuk Kucing: Panduan Lengkap Makanan Sehat',
                'slug' => 'nutrisi-penting-untuk-kucing-panduan-lengkap-makanan-sehat',
                'excerpt' => 'Memahami kebutuhan nutrisi kucing sangat penting untuk menjaga kesehatan dan kebahagiaan hewan peliharaan kesayangan Anda.',
                'content' => '<p>Kucing adalah hewan karnivora obligat yang memiliki kebutuhan nutrisi spesifik. Memahami kebutuhan ini akan membantu Anda memberikan makanan terbaik untuk kucing kesayangan.</p>

<h3>Protein Hewani</h3>
<p>Kucing membutuhkan protein hewani berkualitas tinggi. Protein ini mengandung asam amino esensial seperti taurin yang tidak dapat diproduksi sendiri oleh tubuh kucing.</p>

<h3>Lemak Sehat</h3>
<p>Omega-3 dan Omega-6 sangat penting untuk kesehatan kulit dan bulu kucing. Sumber lemak sehat dapat diperoleh dari ikan salmon dan minyak ikan.</p>

<h3>Vitamin dan Mineral</h3>
<p>Vitamin A, B, C, D, E dan mineral seperti kalsium, fosfor sangat dibutuhkan untuk pertumbuhan dan metabolisme yang optimal.</p>

<h3>Air Bersih</h3>
<p>Kucing membutuhkan akses ke air bersih setiap saat. Dehidrasi dapat menyebabkan masalah ginjal yang serius pada kucing.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(14),
                'is_featured' => true,
                'tags' => ['kucing', 'nutrisi', 'makanan', 'kesehatan'],
                'views' => 89,
                'meta_title' => 'Nutrisi Penting untuk Kucing - Panduan Lengkap IndoPet',
                'meta_description' => 'Panduan lengkap nutrisi kucing: protein, vitamin, mineral yang dibutuhkan untuk kesehatan optimal kucing peliharaan.'
            ],
            [
                'user_id' => $adminUser->id,
                'title' => 'Cara Melatih Anak Anjing: Tips untuk Pemula',
                'slug' => 'cara-melatih-anak-anjing-tips-untuk-pemula',
                'excerpt' => 'Melatih anak anjing membutuhkan kesabaran dan teknik yang tepat. Berikut panduan lengkap untuk pemilik anjing pemula.',
                'content' => '<p>Melatih anak anjing adalah investasi jangka panjang untuk hubungan yang harmonis antara Anda dan hewan peliharaan. Semakin dini dimulai, semakin baik hasilnya.</p>

<h3>Pelatihan Toilet</h3>
<p>Ajarkan anak anjing untuk buang air di tempat yang tepat dengan konsistensi dan penguatan positif.</p>

<h3>Perintah Dasar</h3>
<p>Mulai dengan perintah sederhana seperti "duduk", "diam", dan "kemari". Gunakan reward berupa makanan atau pujian.</p>

<h3>Sosialisasi</h3>
<p>Kenalkan anak anjing dengan berbagai situasi, orang, dan hewan lain untuk mengembangkan kepercayaan diri.</p>

<h3>Jadwal Rutin</h3>
<p>Buat jadwal makan, tidur, dan bermain yang teratur untuk membantu anak anjing beradaptasi.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(21),
                'is_featured' => false,
                'tags' => ['anjing', 'pelatihan', 'anak anjing', 'tips'],
                'views' => 203,
                'meta_title' => 'Cara Melatih Anak Anjing - Tips Pelatihan IndoPet',
                'meta_description' => 'Panduan lengkap melatih anak anjing untuk pemula. Tips efektif toilet training dan perintah dasar.'
            ]
        ];

        foreach ($articles as $articleData) {
            Article::create($articleData);
        }
    }
}
