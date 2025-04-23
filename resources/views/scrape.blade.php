{{-- filepath: c:\xampp\htdocs\ScrapingTest\resources\views\scrape.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraped Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto py-10">
        <h1 class="text-4xl font-bold text-center mb-8">Scraped Product Details</h1>
        <div class="grid grid-cols-1">
            <div class="bg-white shadow-md rounded-lg p-6">
                @if (!empty($titles))
                    <h2 class="text-xl font-semibold mb-4">Product Titles</h2>
                    <ul class="list-disc pl-5">
                        @foreach ($titles as $title)
                            <li>{{ $title }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No products found.</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>