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
            <a href="{{ route('champions.edit', $champion) }}" class="bg-[#1e282d] hover:bg-[#785a28] text-gray-200 hover:text-white px-3 py-1.5 rounded text-xs font-semibold flex items-center shadow transition">
                <i class="fa-solid fa-pen-to-square mr-1.5 text-[#c89b3c]"></i> Editar Campeón
            </a>
            <a href="{{ route('champions.create') }}" class="hextech-btn px-3 py-1.5 rounded text-xs font-semibold flex items-center shadow">
                <i class="fa-solid fa-plus mr-1.5 text-[#c89b3c]"></i> Nuevo Campeón
            </a>
        </div>
    </div>

    <!-- Banner Hero del Campeón -->
    <div class="relative rounded-xl overflow-hidden border border-[#785a28] shadow-2xl mb-8 bg-[#091428]">
        <!-- Imagen de Fondo Hero -->
        <div class="h-80 sm:h-96 relative">
            <img src="{{ $champion->display_image }}" alt="{{ $champion->name }}"
                class="w-full h-full object-cover object-top"
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
        <!-- Columna Izquierda (Lore & Ficha) -->
        <div class="lg:col-span-2 space-y-6">
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

            <!-- Vista del Splash Art Completo -->
            <div class="bg-[#091428]/80 border border-[#1e282d] rounded-lg p-6 shadow-xl">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#1e282d]">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full border border-[#c89b3c] flex items-center justify-center bg-[#010a13]">
                            <i class="fa-solid fa-image text-xs text-[#c89b3c]"></i>
                        </div>
                        <h2 class="text-xl font-bold text-[#f0e6d2] tracking-wide">Arte Conceptual / Splash Art</h2>
                    </div>
                    <a href="{{ $champion->display_image }}" target="_blank" class="text-xs text-[#0ac8b9] hover:underline flex items-center">
                        Abrir original <i class="fa-solid fa-up-right-from-square ml-1 text-[10px]"></i>
                    </a>
                </div>
                <div class="rounded overflow-hidden border border-[#1e282d]">
                    <img src="{{ $champion->display_image }}" alt="Splash Art de {{ $champion->name }}"
                        class="w-full h-auto object-cover hover:scale-[1.02] transition-transform duration-500">
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
                        <button type="button" disabled title="Disponible en Fase 4"
                            class="w-full bg-[#010a13] border border-[#1e282d] text-gray-500 py-2 px-3 rounded text-xs flex items-center justify-between cursor-not-allowed opacity-75">
                            <span><i class="fa-solid fa-trash mr-1.5 text-gray-500"></i> Eliminar Campeón</span>
                            <span class="bg-[#1e282d] px-1.5 py-0.5 rounded text-[10px] text-gray-400">Fase 4</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
