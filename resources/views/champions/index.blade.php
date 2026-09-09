@extends('layouts.app')

@section('title', 'Catálogo de Campeones - League of Legends')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-[#1e282d] gap-4">
        <div>
            <span class="text-xs uppercase tracking-widest text-[#c89b3c] font-semibold">Grieta del Invocador</span>
            <h1 class="text-3xl font-extrabold text-[#f0e6d2] tracking-wide mt-1">
                Catálogo de Campeones
            </h1>
            <p class="text-sm text-gray-400 mt-1">Explora los campeones registrados en el sistema.</p>
        </div>
        <a href="{{ route('champions.create') }}" class="hextech-btn px-5 py-2.5 rounded font-bold text-sm tracking-wider flex items-center justify-center shadow-lg">
            <i class="fa-solid fa-plus mr-2 text-[#c89b3c]"></i> Invocar Campeón
        </a>
    </div>

    @if($champions->isEmpty())
        <div class="bg-[#091428]/60 border border-[#1e282d] rounded-lg p-12 text-center my-8">
            <div class="w-16 h-16 rounded-full border border-[#785a28] flex items-center justify-center bg-[#010a13] mx-auto mb-4">
                <i class="fa-solid fa-ghost text-2xl text-[#c89b3c]"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-200">Aún no hay campeones invocados</h3>
            <p class="text-sm text-gray-400 mt-1 max-w-md mx-auto">Comienza agregando el primer campeón con el formulario de invocación.</p>
            <a href="{{ route('champions.create') }}" class="hextech-btn inline-flex items-center px-4 py-2 rounded text-sm font-semibold mt-4">
                <i class="fa-solid fa-plus mr-2 text-[#c89b3c]"></i> Crear Primer Campeón
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($champions as $champion)
                <div class="bg-[#091428] border border-[#1e282d] hover:border-[#c89b3c] rounded-lg overflow-hidden shadow-lg transition duration-300">
                    <div class="h-44 relative overflow-hidden bg-gray-900">
                        <img src="{{ $champion->display_image }}" alt="{{ $champion->name }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#091428] via-transparent to-transparent"></div>
                        <div class="absolute bottom-3 left-4">
                            <span class="text-xs uppercase tracking-wider font-semibold text-[#c89b3c]">{{ $champion->title }}</span>
                            <h3 class="text-xl font-bold text-white">{{ $champion->name }}</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between text-xs mb-3">
                            <span class="bg-[#010a13] px-2.5 py-1 rounded border border-[#1e282d] text-gray-300 font-medium">
                                <i class="fa-solid fa-shield-halved mr-1 text-[#c89b3c]"></i> {{ \App\Models\Champion::ROLES[$champion->role] ?? $champion->role }}
                            </span>
                            <span class="bg-[#010a13] px-2.5 py-1 rounded border border-[#1e282d] text-gray-300">
                                <i class="fa-solid fa-bolt mr-1 text-blue-400"></i> {{ $champion->resource_type }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 line-clamp-2 mb-4">{{ $champion->lore }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
