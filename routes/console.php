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

Artisan::command('rr', function () {
$example = '{ "account": { "number": "541333******0019", "token": "59449efb2bcf5a69a690d1ec60dfe2ef3339d8fd28698680caf8d82c21333354", "type": "mastercard", "card_holder": "NIK KHO", "expiry_month": "04", "expiry_year": "2027" }, "project_id": 14571, "payment": { "id": "7", "type": "purchase", "status": "success", "date": "2020-05-28T12:30:18+0000", "method": "card", "sum": { "amount": 37100, "currency": "EUR" }, "description": "Тест" }, "operation": { "id": 66428000046911, "type": "sale", "status": "success", "date": "2020-05-28T12:30:18+0000", "created_date": "2020-05-28T12:30:11+0000", "request_id": "43b03a5cf893e7adc4ef55bd89fe829e11d1e50e-8fca9658f8527f82fbb1614f5b51561e2bbfd1df-00066429", "sum_initial": { "amount": 37100, "currency": "EUR" }, "sum_converted": { "amount": 2904196, "currency": "RUB" }, "code": "0", "message": "Success", "eci": "05", "provider": { "id": 6, "payment_id": "15906690175177", "auth_code": "563253", "endpoint_id": 6, "date": "2018-02-07T08:34:24+0000" } }, "signature": "6F/JH/VUOyjevC2Y//InOKIoK5GFbXWKkMZDPAenl1A/WwtHjTjEMNk0v3JeUiYnrd62PrmZKzDr4Cz3dT9kWw==" }';
dd(json_decode($example,true));
});
