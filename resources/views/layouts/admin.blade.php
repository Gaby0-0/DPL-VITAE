<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administrador')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f3f3f3] text-gray-800 min-h-screen">

    <div class="flex min-h-screen">

        <!--sidebar -->
        <aside class="w-72 bg-[#d90000] text-white flex flex-col justify-between shadow-xl">
            <div>
                <div class="bg-white text-black px-8 py-6">
                    <h1 class="text-4xl font-bold tracking-tight">DPL-VITAE</h1>
                </div>

                <nav class="px-6 py-8 space-y-3">
                    <a href="/admin/dashboard" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-red-700 transition">
                        <span class="text-xl">jj</span>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('empresa.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-red-700 transition">
                        <span class="text-xl">🏢</span>
                        <span class="font-medium">Empresa</span>
                    </a>

                    <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-red-700 transition">
                        <span class="text-xl">✚</span>
                        <span class="font-medium">Cotizaciones</span>
                    </a>

                    <a href="{{ route('ambulancias.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-red-700 transition">
                        <span class="text-xl">✚</span>
                        <span class="font-medium">Ambulancias</span>
                    </a>

                    <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-red-700 transition">
                        <span class="text-xl">✚</span>
                        <span class="font-medium">Personal</span>
                    </a>

                    <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-red-700 transition">
                        <span class="text-xl">✚</span>
                        <span class="font-medium">Insumos</span>
                    </a>

                    <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-red-700 transition">
                        <span class="text-xl">♡</span>
                        <span class="font-medium">Padecimientos</span>
                    </a>
                </nav>
            </div>

            <div class="px-6 py-8">
                <button class="w-14 h-14 rounded-full border-2 border-white flex items-center justify-center text-2xl hover:bg-white hover:text-[#d90000] transition">
                    ←
                </button>
            </div>
        </aside>

        <!-- Content -->
        <div class="flex-1 flex flex-col">

            <!-- Topbar -->
            <header class="flex justify-between items-center px-10 py-6 bg-[#f3f3f3]">
                <div>
                    <h2 class="text-4xl font-bold text-black">@yield('header', 'Dashboard')</h2>
                    <div class="w-96 h-[2px] bg-gray-300 mt-2"></div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center font-bold">
                        A
                    </div>
                    <div class="text-gray-700 font-semibold uppercase tracking-wide">
                        Administrador
                    </div>
                    <div class="text-gray-600 text-xl">⌄</div>
                </div>
            </header>

            <main class="px-10 pb-10">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>