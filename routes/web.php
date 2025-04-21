<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/scrape', function () {
    $url = 'https://www.oklocated.com/'; // Replace with the URL you want to scrape
    $html = file_get_contents($url);
    
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    
    $xpath = new DOMXPath($dom);
    
    // Example: Get all links
    $links = $xpath->query('//a');
    
    foreach ($links as $link) {
        if ($link instanceof DOMElement) {
            echo $link->getAttribute('href') . '<br>';
        }
    }
});
