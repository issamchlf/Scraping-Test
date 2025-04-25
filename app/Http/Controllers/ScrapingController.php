<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class ScrapingController extends Controller
{
    public function scrape()
    {
        //  Create HttpClient with headers if needed
        $httpClient = HttpClient::create([
            'headers' => [
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
                                     'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ],
        ]);

        //  Instantiate HttpBrowser with the HTTP client
        $browser = new HttpBrowser($httpClient);

        //  Make the request
        $crawler = $browser->request('GET', 'https://webscraper.io/test-sites/e-commerce/static');

        // Extract product details with DomCrawler
        $products = $crawler->filter('div.thumbnail')->each(function (Crawler $node) {
            return [
                'title'       => trim($node->filter('div.caption h4 a')->text()),
                'description' => trim($node->filter('div.caption p')->text()),
                'price'       => floatval(str_replace('$', '', $node->filter('h4.price')->text())),
                'image_url'   => $node->filter('img')->attr('src'),
            ];
        });

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['title' => $product['title']], 
                $product 
            );
        }

        $savedProducts = Product::all();

        return view('scrape', ['products' => $savedProducts]);
    }
}