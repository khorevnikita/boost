<?php

use Illuminate\Database\Seeder;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'games' => [
                [
                    'name' => "DESTINY 2",
                    'categories' => [
                        [
                            'name' => "Trials of Osiris",
                            'url' => "https://boostmytoon.com/product-category/destiny/trials_of_osiris/"
                        ],
                        [
                            'name' => "The Dawning",
                            'url' => "https://boostmytoon.com/product-category/destiny/d2_dawning/"
                        ],
                        [
                            'name' => "Season of Dawn",
                            'url' => "https://boostmytoon.com/product-category/destiny/d2_season_of_dawn/"
                        ],
                        [
                            'name' => "Bundles",
                            'url' => "https://boostmytoon.com/product-category/destiny/d2_bundles/"
                        ],
                        [
                            'name' => "Catalysts",
                            'url' => "https://boostmytoon.com/product-category/destiny/d2_cat/"
                        ],
                    ]
                ],
                [
                    'name' => "WORLD OF WARCRAFT",
                    'categories' => [
                        [
                            'name' => "PVP",
                            "url" => "https://boostmytoon.com/product-category/wow/wow_pvp/"
                        ],
                        [
                            'name' => "Mythic Keystone",
                            "url" => "https://boostmytoon.com/product-category/wow/wow_mythic_keystone/"
                        ],
                        [
                            'name' => "Raid",
                            "url" => "https://boostmytoon.com/product-category/wow/wow_raids/"
                        ],

                        [
                            'name' => "Powerleveling",
                            'url' => "https://boostmytoon.com/product-category/wow/wow_leveling/"
                        ],
                    ],
                ],
                [
                    'name' => "DIABLO III",
                    "categories" => [
                        [
                            'name' => "Gearing",
                            "url" => "https://boostmytoon.com/product-category/diablo3/gearing_d3/"
                        ],
                    ]
                ]
            ]
        ];

        foreach ($data['games'] as $game) {
            $g = new \App\Game();
            $g->title = $game['name'];
          #  $g->save();
            foreach ($game['categories'] as $category) {
                $c = new \App\Category();
                $c->title = $category['name'];
                $c->game_id = $g->id;
              #  $c->save();

                $html = file_get_contents($category['url']);
                $dom = pQuery::parseStr($html);
                $products = $dom->query('.product');
                foreach ($products as $product) {
                    $link = $product->tagName("a")->attr("href");
                    var_dump($link);exit;
                }
            }
        }
    }
}
