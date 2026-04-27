@extends('agent.layouts.app')

@section('title', 'Détails du bien - ' . $bien->reference)

@section('content')
    {{-- A-Frame pour la 3D interactive --}}
    <script src="https://aframe.io/releases/1.4.0/aframe.min.js"></script>
    <style>
        .a-canvas {
            width: 100% !important;
            height: 100% !important;
            cursor: grab;
        }
        .a-canvas:active {
            cursor: grabbing;
        }
        /* Masquer le bouton VR par défaut pour plus de propreté */
        .a-enter-vr-button { display: none !important; }
    </style>

    <div class="mx-auto h-full" style="width:90%">
        {{-- Barre d'actions supérieure --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-primary flex items-center gap-3">
                    <span class="bg-primary/10 p-2 rounded-xl">
                        @if($bien->type_bien === 'maison')
                            <i class="fa-solid fa-house-chimney text-primary"></i>
                        @elseif($bien->type_bien === 'appartement')
                            <i class="fa-solid fa-building text-primary"></i>
                        @else
                            <i class="fa-solid fa-briefcase text-primary"></i>
                        @endif
                    </span>
                    Détails du bien <span class="text-secondary">#{{ $bien->reference }}</span>
                </h1>
                <nav class="flex mt-2 text-sm text-gray-500" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('agent.dashboard') }}" class="hover:text-secondary transition-colors">Tableau de bord</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fa-solid fa-chevron-right text-[10px] mx-1"></i>
                                <a href="{{ route('agent.biens.index') }}" class="hover:text-secondary transition-colors">Biens</a>
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
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('agent.biens.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                    Retour
                </a>
                <a href="{{ route('agent.biens.edit', $bien->id) }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-secondary font-bold rounded-xl border border-secondary/20 shadow-sm hover:bg-secondary/5 transition-all">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Modifier
                </a>
                <form action="{{ route('agent.biens.destroy', $bien->id) }}" method="POST" id="delete-form-{{ $bien->id }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button" 
                            onclick="confirmDelete({{ $bien->id }})"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-50 text-red-600 font-bold rounded-xl border border-red-100 shadow-sm hover:bg-red-100 transition-all">
                        <i class="fa-solid fa-trash"></i>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Colonne de gauche : Images et Description --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Galerie d'images et Vidéo --}}
                <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100" 
                     x-data="{ 
                        activeMedia: { 
                            type: '{{ $bien->video_3d ? 'video' : 'image' }}', 
                            url: '{{ $bien->video_3d ? Storage::url($bien->video_3d) : Storage::url($bien->photo_principale) }}' 
                        }
                     }">
                    
                    {{-- Affichage Principal --}}
                    <div class="relative group rounded-2xl overflow-hidden aspect-video bg-gray-100 mb-4 shadow-inner">
                        {{-- Cas Image --}}
                        <template x-if="activeMedia.type === 'image'">
                            <img :src="activeMedia.url" alt="Média principal" class="w-full h-full object-cover transition-all duration-500 group-hover:scale-105">
                        </template>

                        {{-- Cas Vidéo Interactive A-Frame --}}
                        <template x-if="activeMedia.type === 'video'">
                            <div class="w-full h-full relative" wire:ignore>
                                <a-scene embedded loading-screen="enabled: false" device-orientation-permission-ui="enabled: false" class="w-full h-full">
                                    <a-assets>
                                        <video id="video3d" :src="activeMedia.url" autoplay loop muted crossorigin="anonymous"></video>
                                    </a-assets>
                                    <a-videosphere src="#video3d" rotation="0 -90 0"></a-videosphere>
                                    <a-entity camera="active: true; fov: 100" look-controls="pointerLockEnabled: false"></a-entity>
                                </a-scene>
                                
                                {{-- Overlay d'aide interactif --}}
                                <div class="absolute inset-0 pointer-events-none z-10 flex items-center justify-center bg-black/10 group-hover:bg-transparent transition-all">
                                    <div class="bg-white/20 backdrop-blur-md p-4 rounded-2xl border border-white/30 text-white text-center flex flex-col items-center group-hover:opacity-0 transition-opacity">
                                        <i class="fa-solid fa-arrows-up-down-left-right text-3xl mb-2 animate-pulse"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Cliquer & Faire glisser pour visiter en 3D</span>
                                    </div>
                                </div>

                                {{-- Bouton Replay / Mute --}}
                                <div class="absolute bottom-4 right-4 z-20 flex gap-2">
                                    <button onclick="document.getElementById('video3d').play()" class="w-8 h-8 bg-white/20 hover:bg-white/40 backdrop-blur text-white rounded-full flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-play text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- Badges flottants --}}
                        <div class="absolute top-4 right-4 flex gap-2 z-20">
                            <span class="px-4 py-1.5 bg-white/90 backdrop-blur text-primary font-bold text-sm rounded-full shadow-lg">
                                <i class="fa-solid fa-location-dot text-secondary mr-2"></i> {{ $bien->commune }}
                            </span>
                            @php
                                $statusColors = [
                                    'actif' => 'bg-green-500',
                                    'inactif' => 'bg-gray-400',
                                    'loue' => 'bg-secondary',
                                ];
                            @endphp
                            <span class="px-4 py-1.5 {{ $statusColors[$bien->statut] ?? 'bg-blue-500' }} text-white font-bold text-sm rounded-full shadow-lg">
                                {{ strtoupper($bien->statut) }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Miniatures --}}
                    <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                        {{-- Miniature Vidéo 3D Interactive --}}
                        @if($bien->video_3d)
                            <button @click="activeMedia = { type: 'video', url: '{{ Storage::url($bien->video_3d) }}' }" 
                                    class="relative aspect-square rounded-lg overflow-hidden border-2 transition-all group bg-black"
                                    :class="activeMedia.type === 'video' ? 'border-secondary' : 'border-transparent hover:border-gray-200'">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/40 z-10">
                                    <i class="fa-solid fa-person-walking text-white text-xl"></i>
                                </div>
                                <img src="{{ Storage::url($bien->photo_principale) }}" class="w-full h-full object-cover opacity-50">
                                <div class="absolute bottom-1 right-1 bg-secondary text-white text-[8px] font-bold px-1 rounded uppercase tracking-tighter">VISITE 3D</div>
                            </button>
                        @endif

                        {{-- Miniature Photo Principale --}}
                        <button @click="activeMedia = { type: 'image', url: '{{ Storage::url($bien->photo_principale) }}' }" 
                                class="relative aspect-square rounded-lg overflow-hidden border-2 transition-all"
                                :class="(activeMedia.type === 'image' && activeMedia.url === '{{ Storage::url($bien->photo_principale) }}') ? 'border-secondary' : 'border-transparent hover:border-gray-200'">
                            <img src="{{ Storage::url($bien->photo_principale) }}" class="w-full h-full object-cover">
                        </button>

                        {{-- Miniatures Photos Supplémentaires --}}
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
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center text-center group hover:border-secondary/20 transition-all">
                        <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-secondary/10 transition-all">
                            <i class="fa-solid fa-maximize text-xl text-gray-400 group-hover:text-secondary"></i>
                        </div>
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Superficie</span>
                        <span class="text-lg font-bold text-primary">{{ number_format($bien->superficie, 0) }} m²</span>
                    </div>
                    <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center text-center group hover:border-secondary/20 transition-all">
                        <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-secondary/10 transition-all">
                            <i class="fa-solid fa-door-open text-xl text-gray-400 group-hover:text-secondary"></i>
                        </div>
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Pièces</span>
                        <span class="text-lg font-bold text-primary">{{ $bien->nb_pieces }}</span>
                    </div>
                    <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center text-center group hover:border-secondary/20 transition-all">
                        <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-secondary/10 transition-all">
                            <i class="fa-solid fa-sink text-xl text-gray-400 group-hover:text-secondary"></i>
                        </div>
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Toilettes</span>
                        <span class="text-lg font-bold text-primary">{{ $bien->nb_toilettes }}</span>
                    </div>
                    <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center text-center group hover:border-secondary/20 transition-all">
                        <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-secondary/10 transition-all">
                            <i class="fa-solid fa-warehouse text-xl text-gray-400 group-hover:text-secondary"></i>
                        </div>
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Garage</span>
                        <span class="text-lg font-bold text-primary">{{ $bien->garage ? 'Qui' : 'Non' }}</span>
                    </div>
                </div>

                {{-- Fiche Technique --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-primary mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-secondary rounded-full"></span>
                        Fiche Technique & Historique
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div class="flex justify-between items-center py-3 border-b border-gray-50">
                            <span class="text-gray-500 font-medium italic">Type de bien</span>
                            <span class="font-bold text-primary capitalize">{{ $bien->type_bien }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-50">
                            <span class="text-gray-500 font-medium italic">Configuration</span>
                            <span class="font-bold text-primary capitalize">{{ str_replace('_', ' ', $bien->typologie) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-50">
                            <span class="text-gray-500 font-medium italic">Usage autorisé</span>
                            <span class="font-bold text-primary capitalize">{{ $bien->type_utilisation }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-50">
                            <span class="text-gray-500 font-medium italic">Commune</span>
                            <span class="font-bold text-primary capitalize">{{ $bien->commune }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-50">
                            <span class="text-gray-500 font-medium italic">Lien GPS</span>
                            @if($bien->google_maps_url)
                                <a href="{{ $bien->google_maps_url }}" target="_blank" class="text-secondary font-bold hover:underline flex items-center gap-1">
                                    <i class="fa-solid fa-map-location-dot"></i> Google Maps
                                </a>
                            @else
                                <span class="text-gray-400 italic">Non renseigné</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-50">
                            <span class="text-gray-500 font-medium italic">Statut</span>
                            <span class="px-3 py-1 bg-secondary/10 text-secondary rounded-lg font-bold text-xs uppercase tracking-widest">{{ $bien->statut }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-50">
                            <span class="text-gray-500 font-medium italic">Publié le</span>
                            <span class="font-bold text-primary">{{ $bien->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-50">
                            <span class="text-gray-500 font-medium italic">Mis à jour le</span>
                            <span class="font-bold text-primary">{{ $bien->updated_at->translatedFormat('d F Y à H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-primary mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-secondary rounded-full"></span>
                        Description détaillée
                    </h3>
                    <div class="prose max-w-none text-gray-600 leading-relaxed">
                        {!! nl2br(e($bien->description)) ?: '<span class="italic text-gray-400">Aucune description disponible pour ce bien.</span>' !!}
                    </div>
                </div>

            </div>

            {{-- Colonne de droite : Finances et Infos --}}
            <div class="space-y-8">
                
                {{-- Carte de prix --}}
                <div class="bg-white rounded-3xl shadow-xl shadow-primary/5 border border-primary/5 overflow-hidden">
                    <div class="bg-primary p-6 text-white text-center">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[10px] font-bold opacity-60 uppercase tracking-[0.2em]">Loyer mensuel</span>
                            <i class="fa-solid fa-crown text-secondary/40 text-xs"></i>
                        </div>
                        <div class="flex items-baseline justify-center gap-2">
                            <span class="text-4xl font-black">{{ number_format($bien->loyer_mensuel, 0, ',', ' ') }}</span>
                            <span class="text-sm font-medium opacity-70">FCFA</span>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center text-xs italic font-bold">Av</span>
                                <span class="text-sm font-semibold text-gray-600">Avance</span>
                            </div>
                            <span class="font-bold text-primary">{{ $bien->avance }} mois</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs italic font-bold">Ca</span>
                                <span class="text-sm font-semibold text-gray-600">Caution</span>
                            </div>
                            <span class="font-bold text-primary">{{ $bien->caution }} mois</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center text-xs italic font-bold">Fr</span>
                                <span class="text-sm font-semibold text-gray-600">Frais agence</span>
                            </div>
                            <span class="font-bold text-primary">{{ $bien->frais_agence }} mois</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-green-50 text-green-500 flex items-center justify-center text-xs italic font-bold">Pa</span>
                                <span class="text-sm font-semibold text-gray-600">Paiement le</span>
                            </div>
                            <span class="font-bold text-primary">{{ $bien->date_paiement ?: 'N/A' }} du mois</span>
                        </div>

                        <div class="mt-6 p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total à l'entrée</span>
                                <i class="fa-solid fa-circle-info text-gray-300"></i>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-black text-primary">{{ number_format($bien->montant_total, 0, ',', ' ') }}</span>
                                <span class="text-xs font-bold text-primary/40">FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions : Locate --}}
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ $bien->google_maps_url ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($bien->commune) }}" target="_blank" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-3xl border border-gray-100 shadow-sm hover:border-secondary/20 transition-all group">
                        @if($bien->google_maps_url)
                            <span class="absolute -top-2 px-2 py-0.5 bg-green-500 text-[8px] text-white font-bold rounded-full shadow-sm">Position exacte</span>
                        @endif
                        <i class="fa-solid fa-map-location-dot text-gray-300 group-hover:text-secondary mb-2 text-xl transition-all"></i>
                        <span class="text-[10px] font-extrabold text-gray-400 group-hover:text-secondary uppercase tracking-tighter">Localiser</span>
                    </a>
                </div>

                {{-- Référence --}}
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
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Archiver ce bien ?',
                text: "Le bien ne sera plus visible sur le site public mais restera dans vos archives.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff5e14',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Oui, archiver',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endpush
@endsection
