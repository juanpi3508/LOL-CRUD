@extends('layouts.app')

@section('title', 'Editar Campeón: ' . $champion->name . ' - League of Legends')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Encabezado de la página -->
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-[#1e282d]">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 mb-1">
                <a href="{{ route('champions.index') }}" class="hover:text-[#c89b3c] transition">Catálogo</a>
                <span>/</span>
                <a href="{{ route('champions.show', $champion) }}" class="hover:text-[#c89b3c] transition">{{ $champion->name }}</a>
                <span>/</span>
                <span class="text-[#c89b3c] font-semibold">Editar</span>
            </div>
            <h1 class="text-3xl font-extrabold text-[#f0e6d2] tracking-wide">
                Modificar Atributos de {{ $champion->name }}
            </h1>
            <p class="text-sm text-gray-400 mt-1">Actualiza las características de combate, biografía o imagen oficial.</p>
        </div>
        <div>
            <a href="{{ route('champions.show', $champion) }}" class="bg-[#1e282d] hover:bg-[#785a28] text-gray-300 hover:text-white px-4 py-2 rounded text-xs font-semibold flex items-center transition shadow">
                <i class="fa-solid fa-arrow-left mr-1.5 text-xs text-[#c89b3c]"></i> Cancelar y Volver
            </a>
        </div>
    </div>

    <!-- Formulario de Edición -->
    <form action="{{ route('champions.update', $champion) }}" method="POST" class="space-y-6 bg-[#091428]/80 border border-[#1e282d] rounded-lg p-6 sm:p-8 shadow-xl">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-[#c89b3c] mb-2">
                    Nombre del Campeón <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $champion->name) }}" required
                    class="w-full hextech-input rounded px-3 py-2 text-sm focus:ring-1 focus:ring-[#c89b3c]"
                    placeholder="Ej. Ahri, Yasuo, Jinx...">
                @error('name')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Título -->
            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-[#c89b3c] mb-2">
                    Título Oficial <span class="text-red-400">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $champion->title) }}" required
                    class="w-full hextech-input rounded px-3 py-2 text-sm focus:ring-1 focus:ring-[#c89b3c]"
                    placeholder="Ej. La Zorra de Nueve Colas">
                @error('title')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rol -->
            <div>
                <label for="role" class="block text-xs font-semibold uppercase tracking-wider text-[#c89b3c] mb-2">
                    Rol Principal <span class="text-red-400">*</span>
                </label>
                <select name="role" id="role" required class="w-full hextech-input rounded px-3 py-2 text-sm focus:ring-1 focus:ring-[#c89b3c]">
                    <option value="">Selecciona un rol...</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role', $champion->role) === $key ? 'selected' : '' }}>
                            {{ $label }} ({{ $key }})
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipo de Recurso -->
            <div>
                <label for="resource_type" class="block text-xs font-semibold uppercase tracking-wider text-[#c89b3c] mb-2">
                    Tipo de Recurso <span class="text-red-400">*</span>
                </label>
                <select name="resource_type" id="resource_type" required class="w-full hextech-input rounded px-3 py-2 text-sm focus:ring-1 focus:ring-[#c89b3c]">
                    <option value="">Selecciona un recurso...</option>
                    @foreach($resourceTypes as $resource)
                        <option value="{{ $resource }}" {{ old('resource_type', $champion->resource_type) === $resource ? 'selected' : '' }}>
                            {{ $resource }}
                        </option>
                    @endforeach
                </select>
                @error('resource_type')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Dificultad -->
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#c89b3c] mb-2">
                    Nivel de Dificultad <span class="text-red-400">*</span>
                </label>
                <div class="grid grid-cols-3 gap-4">
                    @foreach($difficulties as $diff)
                        <label class="flex items-center space-x-2 bg-[#010a13] border border-[#1e282d] p-3 rounded cursor-pointer hover:border-[#c89b3c] transition">
                            <input type="radio" name="difficulty" value="{{ $diff }}"
                                {{ old('difficulty', $champion->difficulty) === $diff ? 'checked' : '' }}
                                class="text-[#c89b3c] focus:ring-[#c89b3c]">
                            <span class="text-sm font-medium text-gray-200">{{ $diff }}</span>
                        </label>
                    @endforeach
                </div>
                @error('difficulty')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- URL de Imagen / Splash Art -->
            <div class="md:col-span-2">
                <label for="image_url" class="block text-xs font-semibold uppercase tracking-wider text-[#c89b3c] mb-2">
                    URL del Splash Art Oficial (Opcional)
                </label>
                <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $champion->image_url) }}"
                    class="w-full hextech-input rounded px-3 py-2 text-sm focus:ring-1 focus:ring-[#c89b3c]"
                    placeholder="https://ddragon.leagueoflegends.com/cdn/img/champion/splash/..."
                    oninput="actualizarPreview(this.value)">
                <p class="text-[11px] text-gray-400 mt-1">Si se deja en blanco, se generará automáticamente a partir del CDN de Riot Games.</p>
                @error('image_url')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror

                <!-- Previsualizador de Splash Art -->
                <div class="mt-4 border border-[#1e282d] rounded-lg p-3 bg-[#010a13]">
                    <span class="text-[11px] text-gray-400 block mb-2 font-semibold">Previsualización de Imagen Actual / Modificada:</span>
                    <div class="h-40 w-full relative rounded overflow-hidden bg-black flex items-center justify-center">
                        <img id="splash-preview" src="{{ $champion->display_image }}" alt="Previsualización"
                            class="w-full h-full object-cover"
                            onerror="this.src='https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_0.jpg'">
                    </div>
                </div>
            </div>

            <!-- Lore / Biografía -->
            <div class="md:col-span-2">
                <label for="lore" class="block text-xs font-semibold uppercase tracking-wider text-[#c89b3c] mb-2">
                    Biografía / Lore del Campeón <span class="text-red-400">*</span>
                </label>
                <textarea name="lore" id="lore" rows="5" required
                    class="w-full hextech-input rounded px-3 py-2 text-sm focus:ring-1 focus:ring-[#c89b3c] leading-relaxed"
                    placeholder="Escribe la historia o trasfondo oficial del campeón...">{{ old('lore', $champion->lore) }}</textarea>
                @error('lore')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="pt-6 border-t border-[#1e282d] flex flex-col sm:flex-row items-center justify-end space-y-3 sm:space-y-0 sm:space-x-4">
            <a href="{{ route('champions.show', $champion) }}"
                class="w-full sm:w-auto px-5 py-2.5 rounded text-sm text-gray-300 hover:text-white bg-[#1e282d] hover:bg-red-950/40 text-center transition">
                <i class="fa-solid fa-xmark mr-1.5 text-xs"></i> Cancelar
            </a>
            <button type="submit"
                class="w-full sm:w-auto hextech-btn px-6 py-2.5 rounded text-sm font-bold tracking-wider flex items-center justify-center shadow-lg">
                <i class="fa-solid fa-floppy-disk mr-2 text-[#c89b3c]"></i> Guardar Modificaciones
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function actualizarPreview(url) {
        const preview = document.getElementById('splash-preview');
        if (url && url.trim().length > 0) {
            preview.src = url.trim();
        } else {
            const name = document.getElementById('name').value.trim();
            if (name) {
                preview.src = `https://ddragon.leagueoflegends.com/cdn/img/champion/splash/${encodeURIComponent(name)}_0.jpg`;
            } else {
                preview.src = 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_0.jpg';
            }
        }
    }
</script>
@endsection
