{{-- resources/views/layouts/approver.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Approver Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-indigo-800 text-white w-64 py-6 px-4 fixed h-full">
            <div class="text-2xl font-bold mb-8 px-4">Approver Panel</div>
            
            <!-- Navigation Menu -->
            <nav class="space-y-2">
                <a href="{{ route('approver.dashboard') }}" 
                   class="block px-4 py-2 rounded-lg {{ request()->routeIs('approver.dashboard') ? 'bg-indigo-900' : 'hover:bg-indigo-700' }}">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
                <a href="{{ route('approver.approvals.index') }}" 
                   class="block px-4 py-2 rounded-lg {{ request()->routeIs('approver.approvals.*') ? 'bg-indigo-900' : 'hover:bg-indigo-700' }}">
                    <i class="fas fa-check-circle mr-2"></i> Persetujuan
                </a>
                <a href="{{ route('approver.approvals.history') }}" 
                   class="block px-4 py-2 rounded-lg {{ request()->routeIs('approver.history') ? 'bg-indigo-900' : 'hover:bg-indigo-700' }}">
                    <i class="fas fa-history mr-2"></i> Riwayat
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow">
                <div class="flex justify-between items-center py-4 px-8">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-4">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>