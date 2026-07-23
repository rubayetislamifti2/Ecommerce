<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Tailwind CSS (Vite setup) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="min-h-screen flex">

    <!-- Left Sidebar Navbar Component -->
    @include('components.sidebar')

    <!-- Right Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Top Header Component -->
        @include('components.header')

        <!-- Main Content Area -->
        <main class="p-6 lg:p-8 flex-1">

            <!-- Alert Messages (Success/Error) -->
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Dynamic Content Load -->
            @yield('admin-content')

        </main>
    </div>

</div>

</body>
</html>
