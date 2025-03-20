<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Downloader</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-lg w-96">
        <h2 class="text-2xl font-semibold text-center mb-4">YouTube Video Downloader</h2>

        <!-- Display Error message -->
        @if (session('error'))
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Display Success message -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
                @if (isset($fileUrl))
                    <p class="mt-2">Click below to download the video:</p>
                    <a href="{{ $fileUrl }}" class="text-blue-500 underline">Download Video</a>
                @endif
            </div>
        @endif

        <form action="/download" method="POST" class="space-y-4" id="downloadForm">
            @csrf
            <input type="text" name="url" placeholder="Enter YouTube URL" required
                class="w-full p-3 border border-gray-300 rounded-md">
            <button type="submit"
                class="w-full bg-blue-500 text-white py-3 rounded-md hover:bg-blue-600 transition">Download</button>
        </form>

        <!-- Loading Spinner (Tailwind CSS) -->
        <div id="loading" class="hidden mt-4 text-center">
            <div
                class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full border-t-transparent border-blue-500">
            </div>
            <p class="text-center mt-2">Downloading...</p>
        </div>
    </div>

    <script>
        const form = document.getElementById('downloadForm');
        const loadingElement = document.getElementById('loading');

        // Show loading spinner when form is submitted
        form.addEventListener('submit', function() {
            loadingElement.classList.remove('hidden'); // Show loading spinner
        });
    </script>
</body>

</html>
