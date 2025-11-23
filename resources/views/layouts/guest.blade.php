<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/">
                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 200 150"
                    class="shrink-0 h-16 w-auto">
                    <rect width="100%" height="100%" fill="transparent" />
                    <text x="20" y="100" font-family="Arial, sans-serif" font-weight="bold" font-size="80" fill="#3498DB">M</text>
                    <text x="75" y="100" font-family="Arial, sans-serif" font-weight="bold" font-size="80" fill="#3498DB">P</text>
                    <text x="130" y="100" font-family="Arial, sans-serif" font-weight="bold" font-size="80" fill="#3498DB">J</text>
                    <path d="M10,70 L30,60 L50,65 L70,50 L90,80 L110,40 L130,60 L150,45"
                        fill="none" stroke="#ff0000ff" stroke-width="4" />
                    <line x1="10" y1="120" x2="190" y2="120" stroke="#ff0000ff" stroke-width="3" />
                </svg>
            </a>

        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>