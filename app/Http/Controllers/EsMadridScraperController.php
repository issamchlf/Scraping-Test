<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class EsMadridScraperController extends Controller
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

        // 2) Instantiate HttpBrowser
        $browser = new HttpBrowser($httpClient);

        // 3) Make the request to the target website
        $crawler = $browser->request('GET', 'https://www.esmadrid.com/calendario-eventos-madrid');
        // 4) Extract event details
        $events = $crawler->filter('.card-event')->each(function (Crawler $node) {
            return [
                'title'       => trim($node->filter('.card-title')->text()),
                'date'        => $node->filter('.date')->count() ? date('Y-m-d', strtotime($node->filter('.date')->text())) : null,
                'description' => $node->filter('.card-text')->count() ? trim($node->filter('.card-text')->text()) : null,
                'image_url'   => $node->filter('img')->count() ? $node->filter('img')->attr('src') : null,
                'link'        => $node->filter('a')->count() ? $node->filter('a')->attr('href') : null,
            ];
        });

        // 5) Save events to the database
        foreach ($events as $event) {
            Event::updateOrCreate(
                ['title' => $event['title']], // Unique identifier
                $event // Data to update or insert
            );
        }

        return redirect()->route('events.index')->with('success', 'Events scraped successfully!');
    }

    public function index()
    {
        $events = Event::orderBy('date', 'desc')->get();
        return view('events.index', compact('events'));
    }
}