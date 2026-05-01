{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris App - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style> body { font-family: 'Inter', sans-serif; background: #f1f5f9; } </style>
    @stack('styles')
</head>
<body class="antialiased">
    <nav class="bg-white shadow-md border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex space-x-8">
                    <div class="flex items-center">
                        <i class="fas fa-cubes text-blue-600 text-xl mr-2"></i>
                        <span class="font-bold text-xl text-gray-800">InventarisPro</span>
                    </div>
                    <div class="hidden md:flex space-x-4 items-center">
                        <a href="{{ route('barang.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('barang.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Barang</a>
                        <a href="{{ route('peminjaman.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('peminjaman.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Peminjaman</a>
                        <a href="{{ route('laporan.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('laporan.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <main class="py-6">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mb-4">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded shadow-sm">{{ session('success') }}</div>
            </div>
        @endif
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>