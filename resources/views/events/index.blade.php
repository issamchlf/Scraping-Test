{{-- filepath: c:\xampp\htdocs\ScrapingTest\resources\views\events\index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto py-10">
        <h1 class="text-4xl font-bold text-center mb-8">Upcoming Events</h1>
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($events as $event)
                <div class="bg-white shadow-md rounded-lg p-6">
                    @if ($event->image_url)
                        <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-48 object-cover rounded-md mb-4">
                    @endif
                    <h2 class="text-xl font-semibold mb-2">{{ $event->title }}</h2>
                    <p class="text-gray-600 mb-2">{{ $event->description }}</p>
                    <p class="text-gray-500 mb-4">{{ $event->date ? \Carbon\Carbon::parse($event->date)->format('F j, Y') : 'No date available' }}</p>
                    <a href="{{ $event->link }}" target="_blank" class="text-blue-500 hover:underline">Read more</a>
                </div>
            @empty
                <div class="col-span-full text-center">
                    <p class="text-gray-500">No events found.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>