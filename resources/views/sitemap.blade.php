<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($others as $link)
        <url>
            <loc>{{ url($link['url']) }}</loc>
            <lastmod>{{ gmdate(DateTime::W3C, strtotime($link['updated_at'])) }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>1.0</priority>
        </url>
    @endforeach
    @foreach($pages as $page)
        <url>
            <loc>{{ url($page->key) }}</loc>
            <lastmod>{{ gmdate(DateTime::W3C, strtotime($page->updated_at)) }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>1.0</priority>
        </url>
    @endforeach

    @foreach($games as $game)
        <url>
            <loc>{{ url($game->rewrite) }}</loc>
            <lastmod>{{ gmdate(DateTime::W3C, strtotime($game->updated_at)) }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>1.0</priority>
        </url>
    @endforeach

    @foreach($products as $product)
        @if($product->url)
            <url>
                <loc>{{ url($product->url) }}</loc>
                <lastmod>{{ gmdate(DateTime::W3C, strtotime($product->updated_at)) }}</lastmod>
                <changefreq>daily</changefreq>
                <priority>1.0</priority>
            </url>
        @endif
    @endforeach
</urlset>
