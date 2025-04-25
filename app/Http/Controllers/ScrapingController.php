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
        // 1) Create HttpClient with headers
        $httpClient = HttpClient::create([
            'headers' => [
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
                                     'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ],
        ]);

        // 2) Instantiate HttpBrowser with the HTTP client
        $browser = new HttpBrowser($httpClient);

        // 3) Make the request to the target website
        $crawler = $browser->request('GET', 'https://www.esmadrid.com/');

        // 4) Extract event details with DomCrawler
        $products = $crawler->filter('.card-event')->each(function (Crawler $node) {
            return [
                'title'       => trim($node->filter('.card-title')->text()),
                'description' => trim($node->filter('.card-text')->text()),
                'price'       => null, // Assuming no price is available
                'image_url'   => $node->filter('img')->attr('src'),
            ];
        });

        // 5) Save products to the database
        foreach ($products as $product) {
            Product::updateOrCreate(
                ['title' => $product['title']], // Unique identifier
                $product // Data to update or insert
            );
        }

        // 6) Return the view with the scraped data
        $savedProducts = Product::all();
        return view('scrape', ['products' => $savedProducts]);
    }
}