<?php

use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            'main' => "Main",
            'faq' => "FAQ",
            'agreement' => "User agreement",
            'details' => "Company details",
        ];

        foreach ($pages as $k => $page) {
            \Illuminate\Support\Facades\DB::table('pages')->insert(['key' => $k, 'name' => $page]);
        }
    }
}
