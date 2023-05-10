<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Tagd\Core\Database\Seeders\DatabaseSeeder as DbSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DbSeeder::class,
        ]);
    }
}
