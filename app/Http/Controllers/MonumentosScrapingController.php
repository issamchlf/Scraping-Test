<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class MonumentosScrapingController extends Controller
{
    public function scrape()
    {
        $httpClient = HttpClient::create([
            'headers' => [
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
                                     'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ],
        ]);

        $browser = new HttpBrowser($httpClient);
        $browser->request('GET', 'https://visita.malaga.eu/es/que-ver-y-hacer/visitas/monumentos-historicos/monumentos');

        $html = $browser->getResponse()->getContent();
        $htmlUtf8 = mb_convert_encoding($html, 'UTF-8', 'HTML-ENTITIES');
        $crawler = new Crawler($htmlUtf8);

        $monumentos = $crawler->filter('article.four.columns')->each(function (Crawler $node) use ($browser) {
            $title = $node->filter('h1')->text('');
            $description = $node->filter('p')->text('');
            $image = $node->filter('img')->count() ? 
            ($node->filter('img')->attr('data-src') ?? $node->filter('img')->attr('src')) 
            : null;
            $link = $node->filter('a')->attr('href');

            // Inicializar coordenadas
            $lat = null;
            $lng = null;

            // Visitar página individual si el link es válido
            if ($link && str_starts_with($link, '/')) {
                $fullLink = 'https://visita.malaga.eu' . $link;
                try {
                    $detailCrawler = $browser->request('GET', $fullLink);
                    $iframe = $detailCrawler->filter('iframe')->first();
                    if ($iframe->count()) {
                        $src = $iframe->attr('src');
                        if (preg_match('/@([-0-9.]+),([-0-9.]+)/', $src, $matches)) {
                            $lat = $matches[1];
                            $lng = $matches[2];
                        }
                    }
                } catch (\Exception $e) {
                }
            }

            return [
                'title' => trim($title),
                'description' => trim($description),
                'image' => trim($image),
                'link' => trim($link),
                'latitude' => $lat,
                'longitude' => $lng,
            ];
        });

        return view('scrape-monumentos', ['monumentos' => $monumentos]);
    }
}
