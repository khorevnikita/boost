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
            "about_us"=>"About us",
            "terms"=>"Terms of Service",
            "privacy"=>"Privacy Policy",
            "refund"=>"Refund Policy",
        ];

        foreach ($pages as $k => $page) {
            \Illuminate\Support\Facades\DB::table('pages')->updateOrInsert(['key' => $k, 'name' => $page]);
        }
    }
}
