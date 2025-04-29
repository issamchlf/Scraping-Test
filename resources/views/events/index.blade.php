<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 text-gray-800">

    <div class="container mx-auto py-12 px-4">
        <h1 class="text-5xl font-extrabold text-center mb-12 text-gray-900">
            Upcoming Events
        </h1>

        @if(session('success'))
            <div class="max-w-xl mx-auto bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($events as $event)
                <div
                    data-aos="fade-up"
                    data-aos-delay="{{ $loop->index * 100 }}"
                    class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition-transform hover:scale-105 duration-300 ease-in-out"
                >
                    @if($event->image_url)
                        <div class="h-56 overflow-hidden">
                            <img
                                src="{{ $event->image_url }}"
                                alt="{{ $event->title }}"
                                class="w-full h-full object-cover"
                            />
                        </div>
                    @endif

                    <div class="p-6">
                        <h2 class="text-2xl font-semibold mb-2 hover:text-blue-600 transition-colors">
                            {{ $event->title }}
                        </h2>
                        @if($event->date)
                            <p class="text-sm text-gray-500 mb-4">
                                {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                            </p>
                        @endif

                        <p class="text-gray-700 mb-6 line-clamp-3">
                            {{ $event->description }}
                        </p>

                        <a
                            href="{{ $event->link }}"
                            target="_blank"
                            class="inline-block px-5 py-2 bg-blue-600 text-white rounded-full font-medium hover:bg-blue-700 transform hover:-translate-y-1 transition-all duration-200"
                        >
                            Read more →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20" data-aos="fade-in">
                    <p class="text-xl text-gray-500">No events found.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 500,
            once: true,
            offset: 50
        });
    </script>

    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            overflow: hidden;
        }
    </style>
</body>
</html>
