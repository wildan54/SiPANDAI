<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $description = "Standar Operasional Prosedur (SOP) adalah pedoman atau acuan baku dalam melaksanakan prosedur pekerjaan sesuai dengan tugas pokok dan fungsi Dinas Pekerjaan Umum, Perumahan, dan Kawasan Permukiman Kabupaten Ponorogo.";

        $documents = [
            ['title' => 'SOP Sekretariat 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/kDbpw5GrmDi4MNM', 'unit_id' => 2],
            ['title' => 'SOP Bidang Kawasan Permukiman 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/fst2xNMteYdMq3N', 'unit_id' => 10],
            ['title' => 'SOP Bidang Bina Marga 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/fSrJ2DHMZrenpdp', 'unit_id' => 7],
            ['title' => 'SOP Bidang Bina Konstruksi dan Pengendalian Mutu 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/6ijXQAm3tzSw6MB', 'unit_id' => 11],
            ['title' => 'SOP Bidang Sumber Daya Air 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/FGeDWZBQ6yPpJr8', 'unit_id' => 6],
            ['title' => 'SOP Bidang Penataan Ruang 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/nq9GseTQS7aHxZa', 'unit_id' => 8],
            ['title' => 'SOP Bidang Perumahan dan Tata Bangunan 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/7Gp3TmpccSHds3s', 'unit_id' => 9],
            ['title' => 'SOP UPTD Sambit Tahun 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/b8mg2Trp3sQyRDm', 'unit_id' => 4],
            ['title' => 'SOP UPTD Pulung Tahun 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/LsqFtpoRKLB6Ski', 'unit_id' => 14],
            ['title' => 'SOP UPTD Karangan Tahun 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/2SLDeTktJP3gbNs', 'unit_id' => 12],
            ['title' => 'SOP UPTD Babadan Tahun 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/ojJHeigw63E2s2M', 'unit_id' => 13],
            ['title' => 'SOP UPTD Sumoroto Tahun 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/cLSodBA7w6cjkXM', 'unit_id' => 3],
            ['title' => 'SOP UPTD IPALD 2024', 'file_embed' => 'https://dinz.ddns.net/nextcloud/index.php/s/Gqn488AyRjfDA6W', 'unit_id' => 5],
        ];

        foreach ($documents as $doc) {
            Document::create([
                'title' => $doc['title'],
                'description' => $description,
                'document_type_id' => 20,
                'unit_id' => $doc['unit_id'],
                'year' => 2024,
                'slug' => Str::slug($doc['title']),
                'meta_title' => $doc['title'],
                'meta_description' => $doc['title'] . ' Tahun 2024.',
                'file_source' => 'embed',
                'file_embed' => $doc['file_embed'],
                'uploaded_by' => 5,
            ]);
        }
    }
}