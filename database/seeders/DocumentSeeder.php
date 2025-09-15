<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use Illuminate\Support\Str;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Laporan Kinerja Sekretariat 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/HsF7QKmfptFYk6Z',
                'unit_id' => 1,
            ],
            [
                'title' => 'Laporan Kinerja Bidang Kawasan Permukiman 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/6qERonpPGHNPXdk',
                'unit_id' => 2,
            ],
            [
                'title' => 'Laporan Kinerja Bidang Bina Marga 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/Gy46eixDZ8E8b4D',
                'unit_id' => 3,
            ],
            [
                'title' => 'Laporan Kinerja Bidang Bina Konstruksi dan Pengendalian Mutu 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/NW7MwyeRqnwK9kb',
                'unit_id' => 4,
            ],
            [
                'title' => 'Laporan Kinerja Bidang Sumber Daya Air 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/Ee32qNGFLz93n4F',
                'unit_id' => 5,
            ],
            [
                'title' => 'Laporan Kinerja Bidang Penataan Ruang 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/C2Z2BA9G5zyXYWs',
                'unit_id' => 6,
            ],
            [
                'title' => 'Laporan Kinerja Bidang Perumahan dan Tata Bangunan 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/tbbe964ezAkRZ4H',
                'unit_id' => 7,
            ],
            [
                'title' => 'Laporan Kinerja UPTD Sambit Tahun 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/nDeT8Dx4ztDPge5',
                'unit_id' => 8,
            ],
            [
                'title' => 'Laporan Kinerja UPTD Pulung Tahun 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/wgk8F7RpPCHXSb7',
                'unit_id' => 9,
            ],
            [
                'title' => 'Laporan Kinerja UPTD Karangan Tahun 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/bmxboj8o6dSTj3i',
                'unit_id' => 10,
            ],
            [
                'title' => 'Laporan Kinerja UPTD Babadan Tahun 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/nyFqqoDtFjYeJDn',
                'unit_id' => 11,
            ],
            [
                'title' => 'Laporan Kinerja UPTD Sumoroto Tahun 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/ESaYdGpzRaSnCGa',
                'unit_id' => 12,
            ],
            [
                'title' => 'Laporan Kinerja UPTD IPALD 2023',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/yGgz2DEzRp3mYAd',
                'unit_id' => 13,
            ],
        ];

        foreach ($data as $item) {
            Document::create([
                'title'            => $item['title'],
                'description'      => "Laporan Kinerja tahun 2023 untuk {$this->getUnitName($item['unit_id'])}, berisi capaian kinerja dan hasil pelaksanaan program sesuai dengan tugas dan fungsi unit terkait.",
                'document_type_id' => 8,
                'unit_id'          => $item['unit_id'],
                'year'             => 2023,
                'slug'             => Str::slug($item['title']),
                'meta_title'       => $item['title'],
                'meta_description' => "Laporan Kinerja 2023 {$this->getUnitName($item['unit_id'])}",
                'file_source'      => 'embed',
                'file_embed'       => $item['file_embed'],
                'uploaded_by'      => 7,
                'upload_date'      => now(),
                'updated_at'       => now(),
            ]);
        }
    }

    private function getUnitName($unitId)
    {
        $units = [
            1 => 'Sekretariat',
            2 => 'Bidang Kawasan Permukiman',
            3 => 'Bidang Bina Marga',
            4 => 'Bidang Bina Konstruksi dan Pengendalian Mutu',
            5 => 'Bidang Sumber Daya Air',
            6 => 'Bidang Penataan Ruang',
            7 => 'Bidang Perumahan dan Tata Bangunan',
            8 => 'UPTD Sambit',
            9 => 'UPTD Pulung',
            10 => 'UPTD Karangan',
            11 => 'UPTD Babadan',
            12 => 'UPTD Sumoroto',
            13 => 'UPTD IPALD',
        ];

        return $units[$unitId] ?? 'Unit Terkait';
    }
}
