<?php

namespace App\Http\Controllers;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Http\JsonResponse;

class ScrapingController extends Controller
{
    public function scrape(): JsonResponse
    {
        // 1) Create HttpClient with headers if needed
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

        // 3) Make the request
        $crawler = $browser->request('GET', 'https://webscraper.io/test-sites/e-commerce/static');

        // 4) Extract product titles with DomCrawler
        $titles = $crawler
            ->filter('.thumbnail .title')
            ->each(fn(Crawler $node) => trim($node->text()));

        // 5) Return JSON
        return response()->json(['titles' => $titles]);
    }
}
