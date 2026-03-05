<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex">
        <!-- Sidebar -->
        @include('components.vendeur-sidebar')

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Top Bar -->
            <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">@yield('page-title')</h1>
                        <p class="text-sm text-gray-600 mt-1">@yield('page-subtitle')</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- Search Bar (Optional) -->
                        @yield('top-actions')
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-8">
                <!-- Flash Messages -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                        <p class="font-semibold">Erreur</p>
                        <ul class="text-sm mt-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded flex justify-between items-center">
                        <div>
                            <p class="font-semibold">Succès</p>
                            <p class="text-sm mt-1">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-500 hover:text-green-700">✕</button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-4 p-4 bg-orange-50 border-l-4 border-orange-500 text-orange-700 rounded flex justify-between items-center">
                        <div>
                            <p class="font-semibold">Attention</p>
                            <p class="text-sm mt-1">{{ session('warning') }}</p>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-orange-500 hover:text-orange-700">✕</button>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </main>
    </div>

    @include('components.confirmation-modal')
    @stack('scripts')
</body>
</html>
