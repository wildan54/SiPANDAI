<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use Illuminate\Support\Str;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            [
                'title' => 'Laporan Harta Penyelenggara Negara (LHKPN) Kepala Dinas 2021',
                'description' => 'LHKPN atau Laporan Harta Kekayaan Penyelenggara Negara tahun 2021 yang wajib disampaikan oleh Kepala Dinas mengenai harta kekayaan saat menjabat.',
                'document_type_id' => 17,
                'unit_id' => 1,
                'year' => 2021,
                'slug' => Str::slug('lhkpn-kepala-dinas-2021'),
                'meta_title' => 'LHKPN Kepala Dinas Tahun 2021',
                'meta_description' => 'Laporan Harta Kekayaan Penyelenggara Negara (LHKPN) Kepala Dinas tahun 2021.',
                'file_source' => 'embed',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/NgMfyMPBd8ZqoXf',
                'uploaded_by' => 5,
            ],
            [
                'title' => 'Laporan Harta Penyelenggara Negara (LHKPN) Kepala Dinas 2022',
                'description' => 'LHKPN atau Laporan Harta Kekayaan Penyelenggara Negara tahun 2022 yang wajib disampaikan oleh Kepala Dinas mengenai harta kekayaan saat menjabat.',
                'document_type_id' => 17,
                'unit_id' => 1,
                'year' => 2022,
                'slug' => Str::slug('lhkpn-kepala-dinas-2022'),
                'meta_title' => 'LHKPN Kepala Dinas Tahun 2022',
                'meta_description' => 'Laporan Harta Kekayaan Penyelenggara Negara (LHKPN) Kepala Dinas tahun 2022.',
                'file_source' => 'embed',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/oGn76LP7SJqntTb',
                'uploaded_by' => 5,
            ],
            [
                'title' => 'Laporan Harta Penyelenggara Negara (LHKPN) Kepala Dinas 2024',
                'description' => 'LHKPN atau Laporan Harta Kekayaan Penyelenggara Negara tahun 2024 yang wajib disampaikan oleh Kepala Dinas mengenai harta kekayaan saat menjabat.',
                'document_type_id' => 17,
                'unit_id' => 1,
                'year' => 2024,
                'slug' => Str::slug('lhkpn-kepala-dinas-2024'),
                'meta_title' => 'LHKPN Kepala Dinas Tahun 2024',
                'meta_description' => 'Laporan Harta Kekayaan Penyelenggara Negara (LHKPN) Kepala Dinas tahun 2024.',
                'file_source' => 'embed',
                'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/JTxygxQT3AbH3fo',
                'uploaded_by' => 5,
            ],
        ];

        foreach ($documents as $doc) {
            Document::create($doc);
        }
    }
}