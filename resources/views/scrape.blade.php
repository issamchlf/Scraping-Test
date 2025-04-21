<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraping Test</title>
</head>
<body>
    <h1>Scraped Links</h1>
    <ul>
        @php
            $url = 'https://www.oklocated.com/'; // Replace with the URL you want to scrape
            $html = file_get_contents($url);

            // Use DOMDocument to parse the HTML
            $dom = new DOMDocument();
            @$dom->loadHTML($html);

            // Use DOMXPath to query the document
            $xpath = new DOMXPath($dom);

            // Example: Get all links
            $links = $xpath->query('//a');

            foreach ($links as $link) {
                if ($link instanceof DOMElement) {
                    $href = $link->getAttribute('href');
                    echo "<li><a href=\"$href\" target=\"_blank\">$href</a></li>";
                }
            }
        @endphp
    </ul>
</body>
</html>