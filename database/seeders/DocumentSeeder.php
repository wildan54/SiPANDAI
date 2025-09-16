<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use Illuminate\Support\Str;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $description = "Penyusunan Laporan Kinerja merupakan upaya pemerintah untuk mendorong tata kelola yang transparan dan akuntabel, dengan melaporkan capaian kinerja instansi dalam memberikan pelayanan publik. Laporan Kinerja Dinas PUPKP Kabupaten Ponorogo Tahun 2022 menyajikan capaian program dan kegiatan dinas sesuai dengan tugas dan fungsinya, sebagai bentuk pertanggungjawaban dan upaya peningkatan kapasitas kelembagaan.";

        $documents = [
            [
                'title' => 'Laporan Kinerja Sekretariat 2022',
                'unit_id' => 2,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/9CAqrRWznzrgZbx',
            ],
            [
                'title' => 'Laporan Kinerja Bidang Kawasan Permukiman 2022',
                'unit_id' => 10,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/dRbNxjbG99J9t8S',
            ],
            [
                'title' => 'Laporan Kinerja Bidang Bina Marga 2022',
                'unit_id' => 7,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/EoTrDHNnpDXBf47',
            ],
            [
                'title' => 'Laporan Kinerja Bidang Bina Konstruksi dan Pengendalian Mutu 2022',
                'unit_id' => 11,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/EoTrDHNnpDXBf47',
            ],
            [
                'title' => 'Laporan Kinerja Bidang Sumber Daya Air 2022',
                'unit_id' => 6,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/WKkAXFBmQP9tYtG',
            ],
            [
                'title' => 'Laporan Kinerja Bidang Penataan Ruang 2022',
                'unit_id' => 8,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/8mf3jg7ByKEgtwg',
            ],
            [
                'title' => 'Laporan Kinerja Bidang Perumahan dan Tata Bangunan 2022',
                'unit_id' => 9,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/9LFYrxpewDzaZ4n',
            ],
            [
                'title' => 'Laporan Kinerja UPTD Sambit Tahun 2022',
                'unit_id' => 4,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/L2JAFmzmoPjiQzE',
            ],
            [
                'title' => 'Laporan Kinerja UPTD Pulung Tahun 2022',
                'unit_id' => 14,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/AE8DmiXMsJ7mYT6',
            ],
            [
                'title' => 'Laporan Kinerja UPTD Karangan Tahun 2022',
                'unit_id' => 12,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/AqM2KJbLmSMf7sc',
            ],
            [
                'title' => 'Laporan Kinerja UPTD Babadan Tahun 2022',
                'unit_id' => 13,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/ix5HFmJdJJ8GjsW',
            ],
            [
                'title' => 'Laporan Kinerja UPTD Sumoroto Tahun 2022',
                'unit_id' => 3,
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/4jFLzWG23HTJBLC',
            ],
        ];

        foreach ($documents as $doc) {
            Document::create([
                'title' => $doc['title'],
                'description' => $description,
                'document_type_id' => 8,
                'unit_id' => $doc['unit_id'],
                'year' => 2022,
                'slug' => Str::slug($doc['title']),
                'meta_title' => $doc['title'],
                'meta_description' => $doc['title'] . ' Tahun 2022.',
                'file_source' => 'embed',
                'file_embed' => $doc['file_embed'],
                'uploaded_by' => 7,
            ]);
        }
    }
}
