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
        // Create HttpClient with headers
        $httpClient = HttpClient::create([
            'headers' => [
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
                                     'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ],
        ]);

        // Instantiate HttpBrowser
        $browser = new HttpBrowser($httpClient);

        // Make the request 
        $crawler = $browser->request('GET', 'https://www.esmadrid.com/agenda-eventos-madrid');

        $events = $crawler->filter('div.field-item')->each(function (Crawler $node) {
            $title = $node->filter('h2')->count() ? trim($node->filter('h2')->text()) : null;

            // Handle multiple <p> tags and decode if JSON-like
            $descriptionParagraphs = $node->filter('p')->each(function (Crawler $pNode) {
                $text = trim($pNode->text());
                if ($decoded = json_decode($text, true)) {
                    return is_array($decoded) ? implode(" ", $decoded) : $decoded;
                }
                return $text;
            });

            // format for Remove empty/nulls and join paragraphs into one
            $description = implode("\n", array_filter($descriptionParagraphs));

            return [
                'title'       => $title,
                'description' => $description ?: null,
                'image_url' => $node->filter('img')->count() ? 
                ($node->filter('img')->attr('data-src') ?? $node->filter('img')->attr('src')) 
                : null,
                'link'        => $node->filter('a')->count() ? $node->filter('a')->attr('href') : null,
                'date'        => null,
            ];
        });

        foreach ($events as $event) {
            if (empty($event['title']) || empty($event['description'])) {
                continue;
            }

            Event::updateOrCreate(
                ['title' => $event['title']],
                $event
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
