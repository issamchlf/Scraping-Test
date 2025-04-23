<?php

namespace App\Http\Controllers;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;


use Illuminate\Http\JsonResponse;

class ScrapingController extends Controller
{
    public function scrape(): JsonResponse
    {
        $client = new HttpBrowser(HttpClient::create());
        $crawler = $client->request('GET', 'https://example.com/');
        $titles = $crawler->filter('h2.title')->each(function (Crawler $node) {
            return trim($node->text());
        });

        return response()->json([
            'titles' => $titles,
        ]);

    }
}
