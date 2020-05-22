<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');
Artisan::command('test', function () {
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
        $g->save();

        echo "Game $g->title \n";
        foreach ($game['categories'] as $category) {
            $c = new \App\Category();
            $c->title = $category['name'];
            $c->game_id = $g->id;
            $c->save();

            echo "Category $c->title \n";

            $html = file_get_contents($category['url']);
            $dom = pQuery::parseStr($html);
            $products = $dom->query('.product');
            foreach ($products as $product) {
                $link = $product->query("a");
                $url = null;
                foreach ($link as $l) {
                    $url = $l->attr("href");
                    break;
                }
                $img = $product->query(".attachment-woocommerce_thumbnail");
                $banner = null;
                foreach ($img as $i) {
                    $banner = $i->attr("data-src");
                    break;
                }
                if ($url) {
                    $itemPage = file_get_contents($url);
                    $itemDom = pQuery::parseStr($itemPage);
                    $title = $itemDom->query(".product_title")->text();
                    $short_description = $itemDom->query(".woocommerce-product-details__short-description")->text();
                    $description = $itemDom->query(".woocommerce-Tabs-panel--description")->html();

                    $price = $itemDom->query("ins > .woocommerce-Price-amount")->text();
                    $priceV = (int)explode('.', $price)[0];
                    $product = new \App\Product();
                    $product->title = $title;
                    $product->short_description = $short_description;
                    $product->description = $description;
                    $product->category_id = $c->id;
                    $product->price = $priceV;
                    $product->save();
                    if ($banner) {
                        $b = new \App\Image();
                        $b->product_id = $product->id;
                        $path = "/images/$product->id/banner.jpg";
                        \Illuminate\Support\Facades\Storage::disk('public')->put($path, file_get_contents($banner), 'public');
                        $b->url = $path;
                        $b->save();
                    }

                    $options = $itemDom->query(".tmcp-field-wrap");
                    foreach ($options as $option) {
                        $optionName = $option->query(".tc-label-wrap")->text();
                        if (!$optionName) {
                            continue;
                        }
                        $optionPrice = $option->query(".tc-price")->text();
                        $optionPriceV = (int)explode('.', $optionPrice)[0];

                        $o = \App\Option::where("title", $optionName)->first();
                        if (!$o) {
                            $o = new \App\Option();
                            $o->title = $optionName;
                            $o->price = $optionPriceV;
                            $o->type = "abs";
                            $o->save();

                        }
                        $product->options()->attach($o->id);
                    }
                }

                echo "Product $product->title \n";
                #exit;
            }
        }
    }
});
