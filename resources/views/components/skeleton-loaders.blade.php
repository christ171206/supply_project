<!-- Product Card Skeleton -->
<div class="bg-white rounded-lg shadow-lg p-4 overflow-hidden">
    <!-- Image Skeleton -->
    <div class="w-full h-48 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg mb-4 animate-pulse"></div>

    <!-- Price Skeleton -->
    <div class="h-6 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded w-1/2 mb-3 animate-pulse"></div>

    <!-- Title Skeleton -->
    <div class="space-y-2 mb-4">
        <div class="h-4 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded animate-pulse"></div>
        <div class="h-4 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded w-5/6 animate-pulse"></div>
    </div>

    <!-- Button Skeleton -->
    <div class="h-10 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg animate-pulse"></div>
</div>

<!-- Full Page Loading Skeleton -->
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="h-8 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded w-1/4 animate-pulse mb-4"></div>
            <div class="flex gap-4">
                <div class="h-10 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg w-32 animate-pulse"></div>
                <div class="h-10 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg w-32 animate-pulse"></div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="max-w-7xl mx-auto p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @for($i = 0; $i < 6; $i++)
                <div class="bg-white rounded-lg shadow-lg p-4 overflow-hidden">
                    <!-- Image Skeleton -->
                    <div class="w-full h-48 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg mb-4 animate-pulse"></div>

                    <!-- Price Skeleton -->
                    <div class="h-6 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded w-1/2 mb-3 animate-pulse"></div>

                    <!-- Title Skeleton -->
                    <div class="space-y-2 mb-4">
                        <div class="h-4 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded animate-pulse"></div>
                        <div class="h-4 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded w-5/6 animate-pulse"></div>
                    </div>

                    <!-- Button Skeleton -->
                    <div class="h-10 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg animate-pulse"></div>
                </div>
            @endfor
        </div>
    </div>
</div>

<!-- Table Skeleton -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header Row -->
    <div class="border-b border-gray-200 p-4 flex gap-4">
        <div class="h-4 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded flex-1 animate-pulse"></div>
        <div class="h-4 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded flex-1 animate-pulse"></div>
        <div class="h-4 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded flex-1 animate-pulse"></div>
    </div>

    <!-- Body Rows -->
    @for($i = 0; $i < 5; $i++)
        <div class="border-b border-gray-100 p-4 flex gap-4">
            <div class="h-4 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded flex-1 animate-pulse"></div>
            <div class="h-4 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded flex-1 animate-pulse"></div>
            <div class="h-4 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded flex-1 animate-pulse"></div>
        </div>
    @endfor
</div>

<!-- Dashboard Card Skeleton -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    @for($i = 0; $i < 4; $i++)
        <div class="bg-white rounded-lg shadow-lg p-6 overflow-hidden">
            <!-- Icon/Header -->
            <div class="w-12 h-12 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg mb-4 animate-pulse"></div>

            <!-- Title -->
            <div class="h-4 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded w-3/4 mb-3 animate-pulse"></div>

            <!-- Value -->
            <div class="h-8 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded w-1/2 animate-pulse"></div>

            <!-- Subtitle -->
            <div class="h-3 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded w-2/3 mt-3 animate-pulse"></div>
        </div>
    @endfor
</div>

<style>
@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

.animate-skeleton-shimmer {
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
}
</style>
