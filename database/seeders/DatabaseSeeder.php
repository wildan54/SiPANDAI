<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\DocumentSeeder;

class DatabaseSeeder extends Seeder
{
public function run(): void
{
    $this->call(DocumentSeeder::class);
}
}
