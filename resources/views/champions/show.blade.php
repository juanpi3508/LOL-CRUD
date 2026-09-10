@extends('layouts.app')

@section('title', $champion->name . ' - ' . $champion->title . ' - League of Legends')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Migas de pan / Navegación rápida -->
    <div class="flex items-center justify-between mb-6 text-sm">
        <div class="flex items-center space-x-2 text-gray-400">
            <a href="{{ route('champions.index') }}" class="hover:text-[#c89b3c] transition flex items-center">
                <i class="fa-solid fa-arrow-left mr-1.5 text-xs"></i> Catálogo
            </a>
            <span>/</span>
            <span class="text-[#c89b3c] font-semibold">{{ $champion->name }}</span>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="document.getElementById('add-skin-modal').classList.remove('hidden')"
                class="bg-[#0ac8b9]/20 hover:bg-[#0ac8b9]/30 text-[#0ac8b9] border border-[#0ac8b9]/60 px-3 py-1.5 rounded text-xs font-semibold flex items-center shadow transition">
                <i class="fa-solid fa-wand-magic-sparkles mr-1.5"></i> Invocar Aspecto
            </button>
            <a href="{{ route('champions.edit', $champion) }}" class="bg-[#1e282d] hover:bg-[#785a28] text-gray-200 hover:text-white px-3 py-1.5 rounded text-xs font-semibold flex items-center shadow transition">
                <i class="fa-solid fa-pen-to-square mr-1.5 text-[#c89b3c]"></i> Editar Campeón
            </a>
            <a href="{{ route('champions.create') }}" class="hextech-btn px-3 py-1.5 rounded text-xs font-semibold flex items-center shadow">
                <i class="fa-solid fa-plus mr-1.5 text-[#c89b3c]"></i> Nuevo Campeón
            </a>
        </div>
    </div>

    <!-- Banner Hero del Campeón (Splash Dinámico) -->
    <div class="relative rounded-xl overflow-hidden border border-[#785a28] shadow-2xl mb-8 bg-[#091428]">
        <!-- Imagen de Fondo Hero con id para cambio dinámico -->
        <div class="h-80 sm:h-96 relative">
            <img id="hero-splash-img" src="{{ $champion->display_image }}" alt="{{ $champion->name }}"
                class="w-full h-full object-cover object-top transition-opacity duration-500"
                onerror="this.src='https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_0.jpg'">
            <div class="absolute inset-0 bg-gradient-to-t from-[#091428] via-[#091428]/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#091428] via-transparent to-transparent"></div>

            <!-- Información Hero Superpuesta -->
            <div class="absolute bottom-6 left-6 right-6 sm:bottom-8 sm:left-8">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <!-- Badges -->
                    <span class="bg-[#010a13]/90 px-3 py-1 rounded border border-[#c89b3c] text-xs font-bold text-[#c89b3c] tracking-wider uppercase backdrop-blur">
                        <i class="fa-solid fa-shield-halved mr-1"></i> {{ \App\Models\Champion::ROLES[$champion->role] ?? $champion->role }}
                    </span>
                    <span class="bg-[#010a13]/90 px-3 py-1 rounded border border-[#1e282d] text-xs font-semibold text-[#0ac8b9] backdrop-blur">
                        <i class="fa-solid fa-bolt mr-1"></i> {{ $champion->resource_type }}
                    </span>
                    @php
                        $diffColors = [
                            'Baja' => 'bg-emerald-950/90 text-emerald-400 border-emerald-700',
                            'Media' => 'bg-amber-950/90 text-amber-400 border-amber-700',
                            'Alta' => 'bg-rose-950/90 text-rose-400 border-rose-700',
                        ];
                        $diffClass = $diffColors[$champion->difficulty] ?? 'bg-gray-900/90 text-gray-300 border-gray-700';
                    @endphp
                    <span class="px-3 py-1 rounded border {{ $diffClass }} text-xs font-bold tracking-wider uppercase backdrop-blur">
                        Dificultad: {{ $champion->difficulty }}
                    </span>
                    <span class="bg-[#010a13]/90 px-3 py-1 rounded border border-[#785a28] text-xs font-bold text-[#f0e6d2] tracking-wider uppercase backdrop-blur flex items-center">
                        <i class="fa-solid fa-masks-theater text-[#c89b3c] mr-1.5"></i> {{ $champion->skins->count() + 1 }} Aspectos
                    </span>
                </div>

                <h2 class="text-sm sm:text-base font-semibold uppercase tracking-widest text-[#c89b3c]">
                    {{ $champion->title }}
                </h2>
                <h1 class="text-4xl sm:text-6xl font-black text-[#f0e6d2] tracking-wide mt-1 drop-shadow-md">
                    {{ $champion->name }}
                </h1>
            </div>
        </div>
    </div>

    <!-- Contenido Detallado: Grid de 2 Columnas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Columna Izquierda (Lore & Galería Hextech de Aspectos) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Lore / Biografía -->
            <div class="bg-[#091428]/80 border border-[#1e282d] rounded-lg p-6 sm:p-8 shadow-xl">
                <div class="flex items-center space-x-3 mb-4 pb-3 border-b border-[#1e282d]">
                    <div class="w-8 h-8 rounded-full border border-[#c89b3c] flex items-center justify-center bg-[#010a13]">
                        <i class="fa-solid fa-book-open text-xs text-[#c89b3c]"></i>
                    </div>
                    <h2 class="text-xl font-bold text-[#f0e6d2] tracking-wide">Biografía Oficial</h2>
                </div>
                <div class="relative text-gray-300 text-sm sm:text-base leading-relaxed whitespace-pre-line font-normal pl-4 border-l-2 border-[#785a28]">
                    {{ $champion->lore }}
                </div>
            </div>

            <!-- Galería Interactiva de Aspectos / Skins Showcase -->
            <div class="bg-[#091428]/80 border border-[#785a28]/60 rounded-xl p-6 shadow-2xl relative overflow-hidden">
                <!-- Efecto visual Hextech de fondo -->
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-[#c89b3c]/5 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Cabecera de la sección -->
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-4 border-b border-[#1e282d]">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full border border-[#c89b3c] flex items-center justify-center bg-[#010a13] shadow-[0_0_12px_rgba(200,155,60,0.25)]">
                            <i class="fa-solid fa-masks-theater text-sm text-[#c89b3c]"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-[#f0e6d2] tracking-wide flex items-center">
                                Colección de Aspectos
                                <span class="ml-2.5 text-xs font-bold px-2 py-0.5 rounded-full bg-[#1e282d] text-[#c89b3c] border border-[#785a28]">
                                    {{ $champion->skins->count() + 1 }}
                                </span>
                            </h2>
                            <p class="text-xs text-gray-400 mt-0.5">Explora las skins oficiales y personalizadas del campeón</p>
                        </div>
                    </div>

                    <button type="button" onclick="document.getElementById('add-skin-modal').classList.remove('hidden')"
                        class="hextech-btn px-4 py-2 rounded text-xs font-bold flex items-center shadow-lg hover:scale-105 transition-transform">
                        <i class="fa-solid fa-plus mr-1.5 text-[#c89b3c]"></i> Invocar Nuevo Aspecto
                    </button>
                </div>

                <!-- Visor Dinámico Principal de la Skin Activa -->
                <div class="mb-6 rounded-lg overflow-hidden border border-[#785a28] shadow-2xl relative bg-[#010a13] group">
                    <div class="relative h-72 sm:h-96 w-full overflow-hidden">
                        <img id="active-skin-image" src="{{ $champion->display_image }}" alt="Aspecto activo"
                            class="w-full h-full object-cover object-top transition duration-700 group-hover:scale-105"
                            onerror="this.src='https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_0.jpg'">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#010a13] via-[#010a13]/30 to-transparent"></div>
                    </div>

                    <!-- Datos superpuestos del Aspecto Activo -->
                    <div class="absolute bottom-0 inset-x-0 p-5 bg-gradient-to-t from-[#010a13] via-[#010a13]/90 to-transparent flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-1.5">
                                <span id="active-skin-tier" class="px-2.5 py-0.5 rounded border text-[11px] font-bold uppercase tracking-wider backdrop-blur text-gray-300 border-gray-600 bg-gray-900/70">
                                    Clásica
                                </span>
                                <span id="active-skin-price" class="text-xs font-semibold text-[#0ac8b9] flex items-center bg-[#010a13]/80 px-2 py-0.5 rounded border border-[#1e282d]">
                                    <i class="fa-solid fa-gem mr-1"></i> Aspecto Base
                                </span>
                            </div>
                            <h3 id="active-skin-name" class="text-2xl sm:text-3xl font-black text-[#f0e6d2] tracking-wide drop-shadow-md">
                                {{ $champion->name }} (Clásica)
                            </h3>
                        </div>

                        <!-- Acciones sobre la Skin Activa -->
                        <div class="flex items-center space-x-2">
                            <a id="active-skin-link" href="{{ $champion->display_image }}" target="_blank"
                                class="bg-[#1e282d]/80 hover:bg-[#785a28] text-gray-200 hover:text-white px-3 py-1.5 rounded text-xs font-semibold flex items-center shadow transition backdrop-blur">
                                <i class="fa-solid fa-up-right-from-square mr-1.5 text-[10px]"></i> Ver HD
                            </a>
                            <!-- Botón para eliminar skin (solo visible si no es la base) -->
                            <form id="delete-skin-form" action="" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button id="delete-skin-btn" type="submit" onclick="return confirm('¿Estás seguro de eliminar este aspecto?')"
                                    class="hidden bg-rose-950/70 hover:bg-rose-900 border border-rose-700/60 text-rose-300 hover:text-white px-3 py-1.5 rounded text-xs font-semibold items-center shadow transition backdrop-blur">
                                    <i class="fa-solid fa-trash-can mr-1.5"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Carrusel / Selector de Miniaturas de Aspectos -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#c89b3c] mb-3 flex items-center">
                        <i class="fa-solid fa-layer-group mr-1.5"></i> Selecciona un aspecto para visualizarlo:
                    </h4>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        <!-- Miniatura 1: Aspecto Clásico / Base -->
                        <button type="button"
                            onclick="selectSkin(this)"
                            data-name="{{ $champion->name }} (Aspecto Clásico)"
                            data-image="{{ $champion->display_image }}"
                            data-tier="Clásica"
                            data-tier-class="text-gray-300 border-gray-600 bg-gray-900/70"
                            data-price="Aspecto Base"
                            data-delete-url=""
                            class="skin-thumb text-left rounded-lg overflow-hidden border-2 border-[#c89b3c] ring-2 ring-[#c89b3c]/50 bg-[#010a13] hover:border-[#c89b3c] transition-all p-1.5 group shadow-md">
                            <div class="relative h-24 rounded overflow-hidden mb-2 bg-[#091428]">
                                <img src="{{ $champion->display_image }}" alt="{{ $champion->name }}"
                                    class="w-full h-full object-cover object-top group-hover:scale-110 transition duration-500">
                                <span class="absolute top-1 right-1 bg-[#010a13]/90 text-[10px] text-[#c89b3c] font-bold px-1.5 py-0.5 rounded border border-[#785a28]">
                                    Base
                                </span>
                            </div>
                            <div class="px-1">
                                <p class="text-xs font-bold text-[#f0e6d2] truncate">Clásica</p>
                                <p class="text-[10px] text-gray-400">Por defecto</p>
                            </div>
                        </button>

                        <!-- Miniaturas de las Skins Registradas -->
                        @foreach ($champion->skins as $skin)
                            <button type="button"
                                onclick="selectSkin(this)"
                                data-name="{{ $skin->name }}"
                                data-image="{{ $skin->display_image }}"
                                data-tier="{{ $skin->tier }}"
                                data-tier-class="{{ $skin->tier_color }}"
                                data-price="{{ number_format($skin->price_rp) }} RP"
                                data-delete-url="{{ route('skins.destroy', $skin) }}"
                                class="skin-thumb text-left rounded-lg overflow-hidden border-2 border-[#1e282d] bg-[#010a13] hover:border-[#c89b3c] transition-all p-1.5 group shadow-md">
                                <div class="relative h-24 rounded overflow-hidden mb-2 bg-[#091428]">
                                    <img src="{{ $skin->display_image }}" alt="{{ $skin->name }}"
                                        class="w-full h-full object-cover object-top group-hover:scale-110 transition duration-500"
                                        onerror="this.src='{{ $champion->display_image }}'">
                                    <span class="absolute top-1 right-1 {{ $skin->tier_color }} text-[9px] font-bold px-1.5 py-0.5 rounded border">
                                        {{ $skin->tier }}
                                    </span>
                                </div>
                                <div class="px-1">
                                    <p class="text-xs font-bold text-[#f0e6d2] truncate" title="{{ $skin->name }}">{{ $skin->name }}</p>
                                    <p class="text-[10px] text-[#0ac8b9] font-medium flex items-center mt-0.5">
                                        <i class="fa-solid fa-gem mr-1 text-[9px]"></i> {{ number_format($skin->price_rp) }} RP
                                    </p>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    @if ($champion->skins->isEmpty())
                        <div class="mt-4 p-4 rounded-lg bg-[#010a13]/60 border border-dashed border-[#785a28] text-center">
                            <p class="text-xs text-gray-400 mb-2">
                                <i class="fa-solid fa-sparkles text-[#c89b3c] mr-1"></i>
                                Este campeón aún no tiene aspectos adicionales en su inventario.
                            </p>
                            <button type="button" onclick="document.getElementById('add-skin-modal').classList.remove('hidden')"
                                class="text-xs text-[#0ac8b9] hover:text-[#c89b3c] font-semibold underline transition">
                                ¡Haz clic aquí para forjar la primera skin!
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna Derecha (Ficha Técnica y Acciones) -->
        <div class="space-y-6">
            <!-- Ficha Técnica -->
            <div class="bg-[#091428]/80 border border-[#1e282d] rounded-lg p-6 shadow-xl">
                <h3 class="text-base font-bold text-[#f0e6d2] uppercase tracking-wider mb-4 pb-2 border-b border-[#1e282d] flex items-center">
                    <i class="fa-solid fa-sliders text-[#c89b3c] mr-2"></i> Atributos del Campeón
                </h3>

                <dl class="space-y-4 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-[#1e282d]/50">
                        <dt class="text-gray-400">Rol de Combate:</dt>
                        <dd class="font-bold text-[#f0e6d2] flex items-center">
                            <i class="fa-solid fa-shield-halved text-[#c89b3c] mr-1.5 text-xs"></i>
                            {{ \App\Models\Champion::ROLES[$champion->role] ?? $champion->role }}
                        </dd>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-[#1e282d]/50">
                        <dt class="text-gray-400">Tipo de Recurso:</dt>
                        <dd class="font-bold text-[#0ac8b9] flex items-center">
                            <i class="fa-solid fa-bolt mr-1.5 text-xs"></i>
                            {{ $champion->resource_type }}
                        </dd>
                    </div>

                    <div class="py-2 border-b border-[#1e282d]/50">
                        <div class="flex justify-between items-center mb-1.5">
                            <dt class="text-gray-400">Dificultad:</dt>
                            <dd class="font-bold text-[#f0e6d2]">{{ $champion->difficulty }}</dd>
                        </div>
                        <!-- Barra de Dificultad -->
                        @php
                            $diffPercentage = match($champion->difficulty) {
                                'Baja' => '33%',
                                'Media' => '66%',
                                'Alta' => '100%',
                                default => '50%',
                            };
                            $diffBarColor = match($champion->difficulty) {
                                'Baja' => 'bg-emerald-500',
                                'Media' => 'bg-amber-500',
                                'Alta' => 'bg-rose-500',
                                default => 'bg-[#c89b3c]',
                            };
                        @endphp
                        <div class="w-full bg-[#010a13] rounded-full h-2 border border-[#1e282d] overflow-hidden">
                            <div class="{{ $diffBarColor }} h-full rounded-full" style="width: {{ $diffPercentage }}"></div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-[#1e282d]/50">
                        <dt class="text-gray-400">Aspectos Disponibles:</dt>
                        <dd class="font-bold text-[#c89b3c] flex items-center">
                            <i class="fa-solid fa-masks-theater mr-1 text-xs"></i> {{ $champion->skins->count() + 1 }}
                        </dd>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-[#1e282d]/50">
                        <dt class="text-gray-400">Identificador:</dt>
                        <dd class="font-mono text-xs text-gray-300">#{{ $champion->id }}</dd>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <dt class="text-gray-400">Invocado el:</dt>
                        <dd class="text-xs text-gray-300">{{ $champion->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Panel de Acciones -->
            <div class="bg-[#091428]/80 border border-[#1e282d] rounded-lg p-6 shadow-xl">
                <h3 class="text-base font-bold text-[#f0e6d2] uppercase tracking-wider mb-4 pb-2 border-b border-[#1e282d] flex items-center">
                    <i class="fa-solid fa-gamepad text-[#c89b3c] mr-2"></i> Acciones del Sistema
                </h3>

                <div class="space-y-3">
                    <button type="button" onclick="document.getElementById('add-skin-modal').classList.remove('hidden')"
                        class="w-full bg-gradient-to-r from-[#0ac8b9]/20 to-[#0ac8b9]/10 hover:from-[#0ac8b9]/30 hover:to-[#0ac8b9]/20 border border-[#0ac8b9]/70 text-[#0ac8b9] py-2.5 px-4 rounded text-sm font-semibold flex items-center justify-center transition shadow">
                        <i class="fa-solid fa-wand-magic-sparkles mr-2"></i> Invocar Nuevo Aspecto
                    </button>

                    <a href="{{ route('champions.index') }}" class="w-full bg-[#1e282d] hover:bg-[#785a28] text-gray-200 hover:text-white py-2.5 px-4 rounded text-sm font-semibold flex items-center justify-center transition shadow">
                        <i class="fa-solid fa-list mr-2 text-[#c89b3c]"></i> Volver al Catálogo
                    </a>

                    <a href="{{ route('champions.create') }}" class="w-full hextech-btn py-2.5 px-4 rounded text-sm font-semibold flex items-center justify-center shadow">
                        <i class="fa-solid fa-plus mr-2 text-[#c89b3c]"></i> Invocar Otro Campeón
                    </a>

                    <!-- Acciones de Gestión -->
                    <div class="pt-4 mt-4 border-t border-[#1e282d] space-y-2">
                        <a href="{{ route('champions.edit', $champion) }}"
                            class="w-full bg-[#010a13] hover:bg-[#1e282d] border border-[#c89b3c]/50 hover:border-[#c89b3c] text-gray-200 py-2.5 px-3 rounded text-xs flex items-center justify-between transition shadow">
                            <span class="font-semibold"><i class="fa-solid fa-pen-to-square mr-1.5 text-[#c89b3c]"></i> Editar Atributos</span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-[#c89b3c]"></i>
                        </a>
                        <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                            class="w-full bg-[#010a13] hover:bg-rose-950/40 border border-red-900/50 hover:border-red-600 text-rose-300 hover:text-white py-2.5 px-3 rounded text-xs flex items-center justify-between transition shadow">
                            <span class="font-semibold"><i class="fa-solid fa-trash mr-1.5 text-rose-500"></i> Eliminar Campeón</span>
                            <i class="fa-solid fa-triangle-exclamation text-[10px] text-rose-500"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Invocar / Añadir Nueva Skin -->
    <div id="add-skin-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-[#091428] border-2 border-[#c89b3c] rounded-xl max-w-lg w-full p-6 shadow-2xl relative">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#1e282d]">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-[#010a13] border border-[#c89b3c] flex items-center justify-center text-[#c89b3c]">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#f0e6d2]">Invocar Aspecto para {{ $champion->name }}</h3>
                        <p class="text-xs text-gray-400">Añade una skin personalizada o de Riot a este campeón</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('add-skin-modal').classList.add('hidden')"
                    class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('champions.skins.store', $champion) }}" method="POST" class="space-y-4">
                @csrf
                <!-- Nombre de la Skin -->
                <div>
                    <label for="skin_name" class="block text-xs font-bold uppercase tracking-wider text-[#c89b3c] mb-1">
                        Nombre del Aspecto *
                    </label>
                    <input type="text" id="skin_name" name="name" required placeholder="Ej: {{ $champion->name }} Guardiana Estelar"
                        class="w-full bg-[#010a13] border border-[#1e282d] focus:border-[#c89b3c] rounded px-3 py-2 text-sm text-[#f0e6d2] placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-[#c89b3c]">
                </div>

                <!-- Grid de 2 Columnas: Rareza y Precio RP -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Rareza / Tier -->
                    <div>
                        <label for="skin_tier" class="block text-xs font-bold uppercase tracking-wider text-[#c89b3c] mb-1">
                            Categoría / Rareza *
                        </label>
                        <select id="skin_tier" name="tier" required
                            class="w-full bg-[#010a13] border border-[#1e282d] focus:border-[#c89b3c] rounded px-3 py-2 text-sm text-[#f0e6d2] focus:outline-none focus:ring-1 focus:ring-[#c89b3c]">
                            @foreach ($skinTiers ?? \App\Models\Skin::TIERS as $key => $tierLabel)
                                <option value="{{ $key }}" {{ $key === 'Épica' ? 'selected' : '' }}>
                                    {{ $tierLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Precio RP -->
                    <div>
                        <label for="skin_price_rp" class="block text-xs font-bold uppercase tracking-wider text-[#c89b3c] mb-1">
                            Precio en RP *
                        </label>
                        <input type="number" id="skin_price_rp" name="price_rp" required min="0" step="5" value="1350"
                            class="w-full bg-[#010a13] border border-[#1e282d] focus:border-[#c89b3c] rounded px-3 py-2 text-sm text-[#f0e6d2] focus:outline-none focus:ring-1 focus:ring-[#c89b3c]">
                    </div>
                </div>

                <!-- URL del Splash Art -->
                <div>
                    <label for="skin_splash_art_url" class="block text-xs font-bold uppercase tracking-wider text-[#c89b3c] mb-1">
                        URL del Splash Art (Opcional)
                    </label>
                    <input type="url" id="skin_splash_art_url" name="splash_art_url"
                        placeholder="https://ddragon.leagueoflegends.com/cdn/img/champion/splash/..."
                        class="w-full bg-[#010a13] border border-[#1e282d] focus:border-[#c89b3c] rounded px-3 py-2 text-sm text-[#f0e6d2] placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-[#c89b3c]">
                    <p class="text-[11px] text-gray-400 mt-1">
                        Puedes pegar una URL directa en HD o dejarla vacía para usar el arte predeterminado.
                    </p>
                </div>

                <!-- Botones del Modal -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-[#1e282d]">
                    <button type="button" onclick="document.getElementById('add-skin-modal').classList.add('hidden')"
                        class="px-4 py-2 bg-[#1e282d] hover:bg-gray-700 text-gray-300 hover:text-white rounded text-xs font-semibold transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="hextech-btn px-5 py-2 rounded text-xs font-bold tracking-wider shadow-lg flex items-center">
                        <i class="fa-solid fa-wand-magic-sparkles mr-1.5 text-[#c89b3c]"></i> Forjar Aspecto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación del Campeón -->
    <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-[#091428] border-2 border-red-600/70 rounded-xl max-w-md w-full p-6 shadow-2xl relative">
            <div class="flex items-center space-x-3 mb-4 text-red-400">
                <div class="w-12 h-12 rounded-full bg-red-950/60 border border-red-600 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[#f0e6d2]">¿Desterrar a {{ $champion->name }}?</h3>
                    <p class="text-xs text-red-400">Acción destructiva irreversible</p>
                </div>
            </div>

            <p class="text-sm text-gray-300 mb-6 leading-relaxed">
                ¿Estás seguro de que deseas eliminar a <strong class="text-[#c89b3c]">{{ $champion->name }}</strong> (<span class="italic text-gray-400">{{ $champion->title }}</span>) de la Grieta del Invocador? Se desterrarán también todos sus aspectos asociados.
            </p>

            <form action="{{ route('champions.destroy', $champion) }}" method="POST" class="flex items-center justify-end space-x-3">
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

<script>
    function selectSkin(button) {
        const name = button.getAttribute('data-name');
        const image = button.getAttribute('data-image');
        const tier = button.getAttribute('data-tier');
        const tierClass = button.getAttribute('data-tier-class');
        const price = button.getAttribute('data-price');
        const deleteUrl = button.getAttribute('data-delete-url');

        // Actualizar imagen activa y hero
        const activeImg = document.getElementById('active-skin-image');
        const heroImg = document.getElementById('hero-splash-img');
        if (activeImg) activeImg.src = image;
        if (heroImg) heroImg.src = image;

        // Actualizar textos y badges
        const nameEl = document.getElementById('active-skin-name');
        if (nameEl) nameEl.textContent = name;

        const tierEl = document.getElementById('active-skin-tier');
        if (tierEl) {
            tierEl.textContent = tier;
            tierEl.className = 'px-2.5 py-0.5 rounded border text-[11px] font-bold uppercase tracking-wider backdrop-blur ' + tierClass;
        }

        const priceEl = document.getElementById('active-skin-price');
        if (priceEl) {
            priceEl.innerHTML = '<i class="fa-solid fa-gem mr-1"></i> ' + price;
        }

        const linkEl = document.getElementById('active-skin-link');
        if (linkEl) {
            linkEl.href = image;
        }

        // Controlar botón de eliminar skin
        const deleteBtn = document.getElementById('delete-skin-btn');
        const deleteForm = document.getElementById('delete-skin-form');
        if (deleteUrl && deleteForm && deleteBtn) {
            deleteForm.action = deleteUrl;
            deleteBtn.classList.remove('hidden');
            deleteBtn.classList.add('inline-flex');
        } else if (deleteBtn) {
            deleteBtn.classList.add('hidden');
            deleteBtn.classList.remove('inline-flex');
        }

        // Actualizar estilos activos de las miniaturas
        document.querySelectorAll('.skin-thumb').forEach(el => {
            el.classList.remove('border-[#c89b3c]', 'ring-2', 'ring-[#c89b3c]/50');
            el.classList.add('border-[#1e282d]');
        });
        button.classList.remove('border-[#1e282d]');
        button.classList.add('border-[#c89b3c]', 'ring-2', 'ring-[#c89b3c]/50');
    }
</script>
@endsection
