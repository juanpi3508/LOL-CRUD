@extends('layouts.app')

@section('title', 'Catálogo de Campeones - League of Legends')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b border-[#1e282d] gap-4">
        <div>
            <span class="text-xs uppercase tracking-widest text-[#c89b3c] font-semibold">Grieta del Invocador</span>
            <h1 class="text-3xl font-extrabold text-[#f0e6d2] tracking-wide mt-1">
                Catálogo de Campeones
            </h1>
            <p class="text-sm text-gray-400 mt-1">Explora, filtra y consulta los detalles de los campeones registrados.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('champions.create') }}" class="hextech-btn px-5 py-2.5 rounded font-bold text-sm tracking-wider flex items-center justify-center shadow-lg">
                <i class="fa-solid fa-plus mr-2 text-[#c89b3c]"></i> Invocar Campeón
            </a>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros -->
    <form action="{{ route('champions.index') }}" method="GET" class="bg-[#091428]/80 border border-[#1e282d] rounded-lg p-4 mb-8 shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Input de Búsqueda -->
            <div class="md:col-span-5 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por nombre, título o lore..."
                    class="w-full hextech-input pl-9 pr-3 py-2 rounded text-sm text-gray-200 placeholder-gray-500 focus:ring-1 focus:ring-[#c89b3c]">
            </div>

            <!-- Filtro de Rol -->
            <div class="md:col-span-3">
                <select name="role" class="w-full hextech-input px-3 py-2 rounded text-sm text-gray-200">
                    <option value="">-- Todos los Roles --</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro de Dificultad -->
            <div class="md:col-span-2">
                <select name="difficulty" class="w-full hextech-input px-3 py-2 rounded text-sm text-gray-200">
                    <option value="">-- Dificultad --</option>
                    @foreach($difficulties as $diff)
                        <option value="{{ $diff }}" {{ request('difficulty') === $diff ? 'selected' : '' }}>
                            {{ $diff }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Botones de Acción -->
            <div class="md:col-span-2 flex items-center space-x-2">
                <button type="submit" class="hextech-btn flex-1 py-2 px-3 rounded text-sm font-semibold flex items-center justify-center">
                    <i class="fa-solid fa-filter mr-1.5 text-xs text-[#c89b3c]"></i> Filtrar
                </button>
                @if(request()->hasAny(['search', 'role', 'difficulty']) && (request('search') || request('role') || request('difficulty')))
                    <a href="{{ route('champions.index') }}" title="Limpiar filtros"
                        class="bg-[#1e282d] hover:bg-red-900/60 text-gray-300 hover:text-white px-3 py-2 rounded text-sm transition">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </div>

        @if(request()->hasAny(['search', 'role', 'difficulty']) && (request('search') || request('role') || request('difficulty')))
            <div class="mt-3 pt-3 border-t border-[#1e282d] flex items-center text-xs text-gray-400 gap-2">
                <span>Filtros activos:</span>
                @if(request('search'))
                    <span class="bg-[#010a13] px-2 py-0.5 rounded border border-[#785a28] text-[#c89b3c]">
                        Búsqueda: "{{ request('search') }}"
                    </span>
                @endif
                @if(request('role'))
                    <span class="bg-[#010a13] px-2 py-0.5 rounded border border-[#785a28] text-[#c89b3c]">
                        Rol: {{ $roles[request('role')] ?? request('role') }}
                    </span>
                @endif
                @if(request('difficulty'))
                    <span class="bg-[#010a13] px-2 py-0.5 rounded border border-[#785a28] text-[#c89b3c]">
                        Dificultad: {{ request('difficulty') }}
                    </span>
                @endif
                <a href="{{ route('champions.index') }}" class="text-[#0ac8b9] hover:underline ml-auto">Quitar todos</a>
            </div>
        @endif
    </form>

    <!-- Listado de Campeones -->
    @if($champions->isEmpty())
        <div class="bg-[#091428]/60 border border-[#1e282d] rounded-lg p-12 text-center my-8 shadow-inner">
            <div class="w-16 h-16 rounded-full border border-[#785a28] flex items-center justify-center bg-[#010a13] mx-auto mb-4 shadow">
                <i class="fa-solid fa-ghost text-2xl text-[#c89b3c]"></i>
            </div>
            @if(request()->hasAny(['search', 'role', 'difficulty']) && (request('search') || request('role') || request('difficulty')))
                <h3 class="text-lg font-bold text-gray-200">No se encontraron campeones</h3>
                <p class="text-sm text-gray-400 mt-1 max-w-md mx-auto">No hay campeones registrados que coincidan con los criterios de búsqueda o filtros seleccionados.</p>
                <div class="mt-5 flex items-center justify-center gap-3">
                    <a href="{{ route('champions.index') }}" class="hextech-btn inline-flex items-center px-4 py-2 rounded text-sm font-semibold">
                        <i class="fa-solid fa-rotate-left mr-2 text-[#c89b3c]"></i> Limpiar Filtros
                    </a>
                    <a href="{{ route('champions.create') }}" class="bg-[#1e282d] hover:bg-[#785a28] text-gray-300 hover:text-white inline-flex items-center px-4 py-2 rounded text-sm font-semibold transition">
                        <i class="fa-solid fa-plus mr-2 text-[#c89b3c]"></i> Invocar Campeón
                    </a>
                </div>
            @else
                <h3 class="text-lg font-bold text-gray-200">Aún no hay campeones invocados</h3>
                <p class="text-sm text-gray-400 mt-1 max-w-md mx-auto">Comienza agregando el primer campeón con el formulario de invocación.</p>
                <a href="{{ route('champions.create') }}" class="hextech-btn inline-flex items-center px-4 py-2 rounded text-sm font-semibold mt-4">
                    <i class="fa-solid fa-plus mr-2 text-[#c89b3c]"></i> Crear Primer Campeón
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($champions as $champion)
                <div class="group bg-[#091428] border border-[#1e282d] hover:border-[#c89b3c] rounded-lg overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-[#c89b3c]/10 transition-all duration-300 flex flex-col">
                    <!-- Splash Art con Overlay -->
                    <div class="h-48 relative overflow-hidden bg-[#010a13]">
                        <img src="{{ $champion->display_image }}" alt="{{ $champion->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            onerror="this.src='https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_0.jpg'">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#091428] via-[#091428]/40 to-transparent"></div>
                        
                        <!-- Badges Superiores -->
                        <div class="absolute top-3 right-3 flex items-center gap-1.5">
                            @php
                                $diffColors = [
                                    'Baja' => 'bg-emerald-950/80 text-emerald-400 border-emerald-800',
                                    'Media' => 'bg-amber-950/80 text-amber-400 border-amber-800',
                                    'Alta' => 'bg-rose-950/80 text-rose-400 border-rose-800',
                                ];
                                $diffClass = $diffColors[$champion->difficulty] ?? 'bg-gray-900/80 text-gray-300 border-gray-700';
                            @endphp
                            <span class="text-[11px] px-2 py-0.5 rounded border {{ $diffClass }} backdrop-blur font-semibold">
                                {{ $champion->difficulty }}
                            </span>
                        </div>

                        <!-- Título y Nombre en la parte inferior de la imagen -->
                        <div class="absolute bottom-3 left-4 right-4">
                            <span class="text-xs uppercase tracking-wider font-semibold text-[#c89b3c] block truncate">
                                {{ $champion->title }}
                            </span>
                            <h3 class="text-2xl font-black text-[#f0e6d2] tracking-wide truncate">
                                {{ $champion->name }}
                            </h3>
                        </div>
                    </div>

                    <!-- Contenido y Atributos -->
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs mb-3">
                                <span class="bg-[#010a13] px-2.5 py-1 rounded border border-[#1e282d] text-gray-300 font-medium">
                                    <i class="fa-solid fa-shield-halved mr-1 text-[#c89b3c]"></i> {{ \App\Models\Champion::ROLES[$champion->role] ?? $champion->role }}
                                </span>
                                <span class="bg-[#010a13] px-2.5 py-1 rounded border border-[#1e282d] text-gray-300">
                                    <i class="fa-solid fa-bolt mr-1 text-[#0ac8b9]"></i> {{ $champion->resource_type }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-400 line-clamp-3 mb-4 leading-relaxed">
                                {{ $champion->lore }}
                            </p>
                        </div>

                        <!-- Botón Ver Detalles y Acciones Rápidas -->
                        <div class="pt-3 border-t border-[#1e282d] flex items-center justify-between">
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('champions.edit', $champion) }}" title="Editar campeón"
                                    class="text-gray-400 hover:text-[#c89b3c] p-1.5 rounded hover:bg-[#010a13] transition text-xs">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" title="Eliminar campeón"
                                    onclick="abrirModalEliminar('{{ route('champions.destroy', $champion) }}', '{{ $champion->name }}', '{{ $champion->title }}')"
                                    class="text-gray-400 hover:text-red-400 p-1.5 rounded hover:bg-[#010a13] transition text-xs">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                            <a href="{{ route('champions.show', $champion) }}"
                                class="text-xs font-semibold text-[#c89b3c] hover:text-[#f0e6d2] flex items-center group-hover:translate-x-1 transition-transform">
                                Ver Ficha Completa <i class="fa-solid fa-arrow-right ml-1.5 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación si corresponde -->
        @if($champions->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $champions->links() }}
            </div>
        @endif
    @endif

    <!-- Modal Reutilizable de Confirmación de Eliminación -->
    <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-[#091428] border-2 border-red-600/70 rounded-xl max-w-md w-full p-6 shadow-2xl relative">
            <div class="flex items-center space-x-3 mb-4 text-red-400">
                <div class="w-12 h-12 rounded-full bg-red-950/60 border border-red-600 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[#f0e6d2]">¿Eliminar Campeón?</h3>
                    <p class="text-xs text-red-400">Acción destructiva irreversible</p>
                </div>
            </div>

            <p class="text-sm text-gray-300 mb-6 leading-relaxed">
                ¿Estás seguro de que deseas eliminar a <strong id="delete-champion-name" class="text-[#c89b3c]"></strong> (<span id="delete-champion-title" class="italic text-gray-400"></span>) del catálogo?
            </p>

            <form id="delete-form" action="" method="POST" class="flex items-center justify-end space-x-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                    class="px-4 py-2 bg-[#1e282d] hover:bg-gray-700 text-gray-300 hover:text-white rounded text-xs font-semibold transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-gradient-to-r from-red-700 to-red-900 hover:from-red-600 hover:to-red-800 text-white rounded text-xs font-bold tracking-wider shadow-lg flex items-center transition">
                    <i class="fa-solid fa-trash-can mr-1.5"></i> Confirmar Eliminación
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function abrirModalEliminar(actionUrl, name, title) {
        document.getElementById('delete-form').action = actionUrl;
        document.getElementById('delete-champion-name').textContent = name;
        document.getElementById('delete-champion-title').textContent = title;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
</script>
@endsection
