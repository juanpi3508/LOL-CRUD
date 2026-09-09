@extends('layouts.app')

@section('title', 'Invocar Nuevo Campeón - League of Legends')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Encabezado de la página -->
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-[#1e282d]">
        <div>
            <span class="text-xs uppercase tracking-widest text-[#c89b3c] font-semibold">Invocación</span>
            <h1 class="text-3xl font-extrabold text-[#f0e6d2] tracking-wide mt-1">
                Registrar Nuevo Campeón
            </h1>
            <p class="text-sm text-gray-400 mt-1">Completa los atributos del campeón para agregarlo a la Grieta del Invocador.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" onclick="cargarEjemplo('ahri')" class="text-xs bg-[#1e282d] hover:bg-[#785a28] text-gray-300 hover:text-white px-3 py-1.5 rounded transition">
                <i class="fa-solid fa-wand-magic-sparkles mr-1 text-[#c89b3c]"></i> Ejemplo: Ahri
            </button>
            <button type="button" onclick="cargarEjemplo('jinx')" class="text-xs bg-[#1e282d] hover:bg-[#785a28] text-gray-300 hover:text-white px-3 py-1.5 rounded transition">
                <i class="fa-solid fa-wand-magic-sparkles mr-1 text-[#c89b3c]"></i> Ejemplo: Jinx
            </button>
        </div>
    </div>

    <!-- Formulario -->
    <form action="{{ route('champions.store') }}" method="POST" class="space-y-6 bg-[#091428]/70 border border-[#1e282d] rounded-lg p-6 sm:p-8 shadow-xl">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-[#c89b3c] mb-2">
                    Nombre del Campeón <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
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
                <select name="role" id="role" required
                    class="w-full hextech-input rounded px-3 py-2 text-sm focus:ring-1 focus:ring-[#c89b3c]">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Selecciona un rol...</option>
                    @foreach($roles as $key => $roleName)
                        <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                            {{ $roleName }} ({{ $key }})
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
                <select name="resource_type" id="resource_type" required
                    class="w-full hextech-input rounded px-3 py-2 text-sm focus:ring-1 focus:ring-[#c89b3c]">
                    <option value="" disabled {{ old('resource_type') ? '' : 'selected' }}>Selecciona el recurso...</option>
                    @foreach($resourceTypes as $resource)
                        <option value="{{ $resource }}" {{ old('resource_type') == $resource ? 'selected' : '' }}>
                            {{ $resource }}
                        </option>
                    @endforeach
                </select>
                @error('resource_type')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Dificultad -->
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#c89b3c] mb-2">
                Nivel de Dificultad <span class="text-red-400">*</span>
            </label>
            <div class="grid grid-cols-3 gap-4">
                @foreach($difficulties as $diff)
                    <label class="flex items-center space-x-2 bg-[#030a13] border border-[#1e282d] hover:border-[#c89b3c] p-3 rounded cursor-pointer transition">
                        <input type="radio" name="difficulty" value="{{ $diff }}" {{ old('difficulty', 'Media') == $diff ? 'checked' : '' }}
                            class="text-[#c89b3c] focus:ring-[#c89b3c]">
                        <span class="text-sm font-medium
                            {{ $diff == 'Baja' ? 'text-green-400' : ($diff == 'Media' ? 'text-yellow-400' : 'text-red-400') }}">
                            {{ $diff }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('difficulty')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Lore / Biografía -->
        <div>
            <label for="lore" class="block text-xs font-semibold uppercase tracking-wider text-[#c89b3c] mb-2">
                Biografía / Lore del Campeón <span class="text-red-400">*</span>
            </label>
            <textarea name="lore" id="lore" rows="4" required
                class="w-full hextech-input rounded px-3 py-2 text-sm focus:ring-1 focus:ring-[#c89b3c]"
                placeholder="Escribe la historia o trasfondo narrativo del campeón...">{{ old('lore') }}</textarea>
            @error('lore')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- URL de Imagen / Splash Art -->
        <div>
            <label for="image_url" class="block text-xs font-semibold uppercase tracking-wider text-[#c89b3c] mb-2">
                URL del Splash Art / Imagen Oficial
            </label>
            <div class="flex space-x-3 items-center">
                <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}"
                    oninput="actualizarPreview(this.value)"
                    class="flex-1 hextech-input rounded px-3 py-2 text-sm focus:ring-1 focus:ring-[#c89b3c]"
                    placeholder="https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_0.jpg">
                <button type="button" onclick="generarRiotUrl()" class="text-xs hextech-btn px-3 py-2 rounded">
                    <i class="fa-solid fa-link mr-1"></i> Auto Riot CDN
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">Si lo dejas vacío, se usará automáticamente la ilustración oficial de Riot Data Dragon según el nombre.</p>
            @error('image_url')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror

            <!-- Previsualización de Imagen -->
            <div id="previewContainer" class="mt-4 hidden">
                <p class="text-xs text-gray-400 mb-1">Previsualización del Splash Art:</p>
                <div class="relative w-full h-48 rounded overflow-hidden border border-[#c89b3c]/50">
                    <img id="previewImage" src="" alt="Splash Art Preview" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-[#1e282d]">
            <a href="{{ route('champions.index') }}" class="px-5 py-2 rounded text-sm text-gray-400 hover:text-white transition">
                Cancelar
            </a>
            <button type="submit" class="hextech-btn px-6 py-2.5 rounded font-bold text-sm tracking-wider flex items-center shadow-lg">
                <i class="fa-solid fa-sparkles mr-2 text-[#c89b3c]"></i> Invocar Campeón (Guardar)
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function actualizarPreview(url) {
        const container = document.getElementById('previewContainer');
        const img = document.getElementById('previewImage');
        if (url && url.trim().length > 10) {
            img.src = url;
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function generarRiotUrl() {
        const nameInput = document.getElementById('name').value.trim();
        if (!nameInput) {
            alert('Ingresa primero el nombre del campeón (ej. Ahri, Yasuo, Jinx).');
            return;
        }
        const formattedName = nameInput.charAt(0).toUpperCase() + nameInput.slice(1).toLowerCase();
        const url = `https://ddragon.leagueoflegends.com/cdn/img/champion/splash/${encodeURIComponent(formattedName)}_0.jpg`;
        document.getElementById('image_url').value = url;
        actualizarPreview(url);
    }

    function cargarEjemplo(tipo) {
        if (tipo === 'ahri') {
            document.getElementById('name').value = 'Ahri';
            document.getElementById('title').value = 'La Zorra de Nueve Colas';
            document.getElementById('role').value = 'Mage';
            document.getElementById('resource_type').value = 'Maná';
            document.querySelector('input[name="difficulty"][value="Media"]').checked = true;
            document.getElementById('lore').value = 'Dotada de una conexión innata con la magia latente de Runaterra, Ahri es una vastaya con aspecto de zorro que transforma la magia en orbes de pura energía para manipular las emociones de sus presas.';
            document.getElementById('image_url').value = 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_0.jpg';
            actualizarPreview('https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_0.jpg');
        } else if (tipo === 'jinx') {
            document.getElementById('name').value = 'Jinx';
            document.getElementById('title').value = 'La Bala Perdida';
            document.getElementById('role').value = 'Marksman';
            document.getElementById('resource_type').value = 'Maná';
            document.querySelector('input[name="difficulty"][value="Media"]').checked = true;
            document.getElementById('lore').value = 'Una criminal impulsiva y maniática de Zaun, a Jinx le encanta sembrar el caos sin importarle las consecuencias con su arsenal de armas mortales y divertidas.';
            document.getElementById('image_url').value = 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_0.jpg';
            actualizarPreview('https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_0.jpg');
        }
    }

    // Inicializar preview si hay old value
    window.addEventListener('DOMContentLoaded', () => {
        const urlInput = document.getElementById('image_url');
        if (urlInput && urlInput.value) {
            actualizarPreview(urlInput.value);
        }
    });
</script>
@endsection
