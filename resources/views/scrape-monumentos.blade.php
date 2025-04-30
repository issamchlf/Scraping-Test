<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraped Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto py-10 px-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-center animate-fade-in">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600">
                    Discover monumentos of Malaga
                </span>
            </h1>
            <a href="{{ url('/scrape') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition-colors">
                Go to Meseuos
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($monumentos as $monumento) 
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-2 group">
                <div class="relative overflow-hidden rounded-t-xl">
                    <img src="{{ $monumento['image'] }}" alt="{{ $monumento['title'] }}" class="w-full h-48 object-cover rounded-t-xl">
                    <div class="prose max-w-none p-6 transition-opacity duration-300">
                        <h2 class="text-xl font-bold mb-2">{{ $monumento['title'] }}</h2>
                        {!! $monumento['description'] !!}
                        <p class="text-sm text-gray-500 mt-2">
                            <strong>Latitude:</strong> {{ $monumento['latitude'] }}<br>
                            <strong>Longitude:</strong> {{ $monumento['longitude'] }}
                        </p>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                
                <div class="p-4 border-t border-gray-100">
                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition-colors">
                        <a href="{{ $monumento['link'] }}" target="_blank">More Details</a> 
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="inline-block p-6 bg-white rounded-xl shadow-lg animate-bounce">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="mt-4 text-gray-500 font-medium">No monumentos found</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</body>
</html>