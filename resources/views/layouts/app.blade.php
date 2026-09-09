<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'League of Legends - Campeones CRUD')</title>
    <!-- Tailwind CSS CDN para estilos rápidos y modernos -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #030a13;
            color: #f0e6d2;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-image: radial-gradient(circle at top, #091428 0%, #030a13 70%);
            min-height: 100vh;
        }
        .hextech-gold {
            color: #c89b3c;
        }
        .hextech-border {
            border-color: #785a28;
        }
        .hextech-border-glow {
            border: 1px solid #c89b3c;
            box-shadow: 0 0 10px rgba(200, 155, 60, 0.25);
        }
        .hextech-btn {
            background: linear-gradient(180deg, #1e282d 0%, #091428 100%);
            border: 1px solid #c89b3c;
            color: #f0e6d2;
            transition: all 0.3s ease;
        }
        .hextech-btn:hover {
            background: linear-gradient(180deg, #c89b3c 0%, #785a28 100%);
            color: #030a13;
            font-weight: 600;
            box-shadow: 0 0 15px rgba(200, 155, 60, 0.4);
        }
        .hextech-input {
            background-color: #091428;
            border: 1px solid #1e282d;
            color: #f0e6d2;
        }
        .hextech-input:focus {
            border-color: #c89b3c;
            outline: none;
            box-shadow: 0 0 8px rgba(200, 155, 60, 0.3);
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <!-- Barra Superior / Navbar -->
    <header class="border-b border-[#785a28] bg-[#091428]/90 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full border border-[#c89b3c] flex items-center justify-center bg-[#010a13] shadow-md shadow-[#c89b3c]/20">
                    <i class="fa-solid fa-shield-halved text-[#c89b3c] text-lg"></i>
                </div>
                <div>
                    <a href="{{ route('champions.index') }}" class="text-xl font-bold tracking-wider text-[#f0e6d2] uppercase">
                        LoL <span class="text-[#c89b3c]">Champions</span>
                    </a>
                    <span class="text-xs block text-gray-400">Panel de Gestión CRUD</span>
                </div>
            </div>

            <!-- Navegación -->
            <nav class="flex items-center space-x-4">
                <a href="{{ route('champions.index') }}" class="text-sm px-3 py-1.5 rounded text-gray-300 hover:text-[#c89b3c] transition">
                    <i class="fa-solid fa-list mr-1"></i> Catálogo
                </a>
                <a href="{{ route('champions.create') }}" class="hextech-btn px-4 py-1.5 rounded text-sm font-semibold tracking-wider flex items-center shadow">
                    <i class="fa-solid fa-plus mr-2 text-[#c89b3c]"></i> Nuevo Campeón
                </a>
            </nav>
        </div>
    </header>

    <!-- Indicador de Fase y Notificaciones Flash -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="bg-[#091428] border-l-4 border-[#0ac8b9] p-4 rounded shadow-lg mb-6 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-check text-[#0ac8b9] text-xl"></i>
                    <p class="text-sm text-gray-200 font-medium">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-[#091428] border-l-4 border-red-500 p-4 rounded shadow-lg mb-6">
                <div class="flex items-center space-x-3 mb-2">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 text-lg"></i>
                    <p class="text-sm text-red-300 font-bold">Por favor corrige los siguientes errores:</p>
                </div>
                <ul class="list-disc list-inside text-xs text-red-400 space-y-1 ml-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Contenido Principal -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-[#1e282d] bg-[#010a13] py-6 text-center text-xs text-gray-500 mt-auto">
        <p>CRUD de Campeones de League of Legends &bull; Laravel v{{ Illuminate\Foundation\Application::VERSION }}</p>
        <p class="mt-1 text-gray-600">Ambiente de desarrollo: <span class="text-[#c89b3c] font-semibold">jpmora</span> | QA: <span class="text-[#0ac8b9] font-semibold">develop</span> | Producción: <span class="text-yellow-500 font-semibold">main</span></p>
    </footer>

    @yield('scripts')
</body>
</html>
