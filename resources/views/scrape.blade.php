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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($products as $product)
                <div class="bg-white shadow-md rounded-lg p-6">
                    <img src="{{ $product['image_url'] }}" alt="{{ $product['title'] }}" class="w-full h-48 object-cover rounded-md mb-4">
                    <h2 class="text-xl font-semibold mb-2">{{ $product['title'] }}</h2>
                    <p class="text-gray-600 mb-2">{{ $product['description'] }}</p>
                    <p class="text-green-600 font-bold text-lg">${{ number_format($product['price'], 2) }}</p>
                </div>
            @empty
                <div class="col-span-full text-center">
                    <p class="text-gray-500">No products found.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>