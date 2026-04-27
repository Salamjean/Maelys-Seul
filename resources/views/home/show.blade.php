@extends('home.layouts.app')

@section('title', $bien->typologie . ' à ' . $bien->commune . ' - Maelys-imo')

@section('content')
    {{-- A-Frame pour la 3D interactive --}}
    <script src="https://aframe.io/releases/1.4.0/aframe.min.js"></script>
    <style>
        .a-canvas {
            width: 100% !important;
            height: 100% !important;
            cursor: grab;
        }
        .a-canvas:active { cursor: grabbing; }
        .a-enter-vr-button { display: none !important; }
    </style>

    <div class="bg-gray-50 pt-24 pb-20">
        <div class="mx-auto" style="width:90%">
            
            {{-- Navigation --}}
            <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-secondary transition-colors">Accueil</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-[10px] mx-1"></i>
                            <span class="text-gray-400 capitalize">{{ $bien->type_bien }}s</span>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center text-gray-400">
                            <i class="fa-solid fa-chevron-right text-[10px] mx-1"></i>
                            <span>{{ $bien->reference }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Colonne de gauche : Media --}}
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100" 
                         x-data="{ 
                            activeMedia: { 
                                type: '{{ $bien->video_3d ? 'video' : 'image' }}', 
                                url: '{{ $bien->video_3d ? Storage::url($bien->video_3d) : Storage::url($bien->photo_principale) }}' 
                            }
                         }">
                        
                        <div class="relative group rounded-2xl overflow-hidden aspect-video bg-gray-100 mb-4 shadow-inner">
                            {{-- Image --}}
                            <template x-if="activeMedia.type === 'image'">
                                <img :src="activeMedia.url" class="w-full h-full object-cover">
                            </template>

                            {{-- Vidéo 3D --}}
                            <template x-if="activeMedia.type === 'video'">
                                <div class="w-full h-full relative" wire:ignore>
                                    <a-scene embedded loading-screen="enabled: false" device-orientation-permission-ui="enabled: false" class="w-full h-full">
                                        <a-assets>
                                            <video id="video3d" :src="activeMedia.url" autoplay loop muted crossorigin="anonymous"></video>
                                        </a-assets>
                                        <a-videosphere src="#video3d" rotation="0 -90 0"></a-videosphere>
                                        <a-entity camera="active: true; fov: 100" look-controls="pointerLockEnabled: false"></a-entity>
                                    </a-scene>
                                    <div class="absolute inset-0 pointer-events-none z-10 flex items-center justify-center bg-black/10 group-hover:bg-transparent transition-all">
                                        <div class="bg-white/20 backdrop-blur-md p-4 rounded-2xl border border-white/30 text-white text-center flex flex-col items-center group-hover:opacity-0 transition-opacity">
                                            <i class="fa-solid fa-arrows-up-down-left-right text-3xl mb-2 animate-pulse"></i>
                                            <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Cliquer & Faire glisser pour visiter en 3D</span>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div class="absolute top-4 right-4 flex gap-2 z-20">
                                <span class="px-4 py-1.5 bg-secondary text-white font-black text-xs rounded-full shadow-lg uppercase tracking-widest">
                                    {{ $bien->type_utilisation }}
                                </span>
                            </div>
                        </div>

                        {{-- Miniatures --}}
                        <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                            @if($bien->video_3d)
                                <button @click="activeMedia = { type: 'video', url: '{{ Storage::url($bien->video_3d) }}' }" 
                                        class="relative aspect-square rounded-lg overflow-hidden border-2 transition-all bg-black"
                                        :class="activeMedia.type === 'video' ? 'border-secondary' : 'border-transparent hover:border-gray-200'">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 z-10">
                                        <i class="fa-solid fa-person-walking text-white text-xl"></i>
                                    </div>
                                    <img src="{{ Storage::url($bien->photo_principale) }}" class="w-full h-full object-cover opacity-50">
                                </button>
                            @endif
                            <button @click="activeMedia = { type: 'image', url: '{{ Storage::url($bien->photo_principale) }}' }" 
                                    class="relative aspect-square rounded-lg overflow-hidden border-2 transition-all"
                                    :class="(activeMedia.type === 'image' && activeMedia.url === '{{ Storage::url($bien->photo_principale) }}') ? 'border-secondary' : 'border-transparent hover:border-gray-200'">
                                <img src="{{ Storage::url($bien->photo_principale) }}" class="w-full h-full object-cover">
                            </button>
                            @if($bien->photos_supplementaires)
                                @foreach($bien->photos_supplementaires as $photo)
                                    <button @click="activeMedia = { type: 'image', url: '{{ Storage::url($photo) }}' }" 
                                            class="relative aspect-square rounded-lg overflow-hidden border-2 transition-all"
                                            :class="(activeMedia.type === 'image' && activeMedia.url === '{{ Storage::url($photo) }}') ? 'border-secondary' : 'border-transparent hover:border-gray-200'">
                                        <img src="{{ Storage::url($photo) }}" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Caractéristiques --}}
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <h3 class="text-xl font-black text-primary mb-6 flex items-center gap-3">
                            <i class="fa-solid fa-list-check text-secondary"></i> Caractéristiques principales
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex flex-col items-center text-center">
                                <i class="fa-solid fa-maximize text-blue-500 mb-2"></i>
                                <span class="text-[10px] uppercase font-bold text-gray-400">Superficie</span>
                                <span class="font-bold text-primary">{{ (int)$bien->superficie }} m²</span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex flex-col items-center text-center">
                                <i class="fa-solid fa-bed text-purple-500 mb-2"></i>
                                <span class="text-[10px] uppercase font-bold text-gray-400">Pièces</span>
                                <span class="font-bold text-primary">{{ $bien->nb_pieces }}</span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex flex-col items-center text-center">
                                <i class="fa-solid fa-toilet text-yellow-500 mb-2"></i>
                                <span class="text-[10px] uppercase font-bold text-gray-400">Toilettes</span>
                                <span class="font-bold text-primary">{{ $bien->nb_toilettes }}</span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex flex-col items-center text-center">
                                <i class="fa-solid fa-car {{ $bien->garage ? 'text-green-500' : 'text-gray-300' }} mb-2"></i>
                                <span class="text-[10px] uppercase font-bold text-gray-400">Garage</span>
                                <span class="font-bold text-primary">{{ $bien->garage ? 'Inclus' : 'Non' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <h3 class="text-xl font-black text-primary mb-4">Description du bien</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $bien->description ?: 'Aucune description disponible.' }}</p>
                    </div>
                </div>

                {{-- Colonne de droite : Infos financières & Action --}}
                <div class="space-y-8">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 border-t-4 border-t-secondary">
                        <div class="mb-6 flex justify-between items-start">
                            <h2 class="text-2xl font-black text-primary uppercase">{{ $bien->typologie }}</h2>
                            <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black rounded-lg">DISPONIBLE</span>
                        </div>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-secondary">{{ number_format($bien->loyer_mensuel, 0, ',', ' ') }}</span>
                                <span class="text-xs font-bold text-gray-400 uppercase">FCFA / mois</span>
                            </div>
                            <p class="text-xs text-gray-400 flex items-center gap-2">
                                <i class="fa-solid fa-location-dot"></i> {{ $bien->commune }}
                            </p>
                        </div>

                        <div class="pt-6 border-t border-gray-50 space-y-6">
                            <a href="https://wa.me/2250102030405?text={{ urlencode("Bonjour, je suis intéressé par le bien " . $bien->reference . " à " . $bien->commune) }}" 
                               class="flex items-center justify-center gap-3 w-full py-4 bg-[#25D366] hover:bg-[#20ba59] text-white font-black rounded-2xl shadow-lg transition-all active:scale-95">
                                <i class="fa-brands fa-whatsapp text-2xl"></i>
                                CONTACTER WHATSAPP
                            </a>
                            <a href="{{ $bien->google_maps_url ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($bien->commune) }}" 
                               target="_blank"
                               class="flex items-center justify-center gap-3 w-full py-4 bg-primary hover:bg-primary/90 text-white font-black rounded-2xl shadow-lg transition-all">
                                <i class="fa-solid fa-map-location-dot"></i>
                                VOIR LA LOCALISATION
                            </a>
                        </div>
                    </div>

                    <div class="bg-primary/5 p-6 rounded-3xl border border-primary/10 flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-4">
                            <i class="fa-solid fa-qrcode text-primary/20 text-3xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-primary/40 uppercase tracking-[0.3em]">Code Référence</span>
                        <h4 class="text-xl font-black text-primary mt-1">{{ $bien->reference }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
