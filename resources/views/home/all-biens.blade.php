@extends('home.layouts.app')

@section('title', 'Toutes nos annonces — Maelys-imo')

@section('content')
<section class="py-16 bg-white">
    <div class="w-full" style="padding-left:5%;padding-right:5%;">
        
        {{-- Header --}}
        <div class="mb-12">
            <span class="text-sm font-semibold uppercase tracking-widest text-secondary">Notre Catalogue</span>
            <h1 class="text-4xl lg:text-5xl font-black text-primary mt-2">Dénichez votre futur <span class="text-secondary italic">Chez-vous</span></h1>
            <p class="text-gray-400 mt-4 text-lg">Parcourez l'ensemble de nos annonces vérifiées et trouvez la perle rare.</p>
        </div>

        {{-- Search Box --}}
        <div class="bg-gray-50 border border-gray-200 rounded-[2rem] p-8 mb-16 shadow-sm">
            <form action="{{ route('biens.all') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Localisation</label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="text" name="commune" value="{{ request('commune') }}" placeholder="Ville, commune..."
                                class="w-full pl-12 pr-4 py-4 bg-white border-2 border-transparent rounded-2xl focus:border-secondary outline-none transition-all text-sm font-bold shadow-sm">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Type de bien</label>
                        <div class="relative">
                            <i class="fa-solid fa-building absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <select name="type_bien" class="w-full pl-12 pr-4 py-4 bg-white border-2 border-transparent rounded-2xl focus:border-secondary outline-none transition-all text-sm font-bold shadow-sm appearance-none">
                                <option value="">Tous les types</option>
                                <option value="appartement" {{ request('type_bien') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                                <option value="maison" {{ request('type_bien') == 'maison' ? 'selected' : '' }}>Maison / Villa</option>
                                <option value="bureau" {{ request('type_bien') == 'bureau' ? 'selected' : '' }}>Bureau</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Budget Maximum</label>
                        <div class="relative">
                            <i class="fa-solid fa-money-bill-wave absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="number" name="loyer_max" value="{{ request('loyer_max') }}" placeholder="Budget (FCFA)"
                                class="w-full pl-12 pr-4 py-4 bg-white border-2 border-transparent rounded-2xl focus:border-secondary outline-none transition-all text-sm font-bold shadow-sm">
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-secondary text-white py-4 px-6 rounded-2xl font-black text-sm uppercase tracking-widest hover:opacity-90 shadow-xl shadow-secondary/20 transition-all active:scale-95">
                            Filtrer
                        </button>
                        @if(request()->anyFilled(['commune', 'type_bien', 'loyer_max']))
                            <a href="{{ route('biens.all') }}" class="w-14 h-14 bg-white border-2 border-gray-100 text-gray-400 rounded-2xl flex items-center justify-center hover:text-secondary hover:border-secondary transition-all">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Properties Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
            @forelse ($biens as $bien)
                <div class="property-card bg-white rounded-[2rem] overflow-hidden border border-gray-100 group">
                    {{-- Image --}}
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ Storage::url($bien->photo_principale) }}" alt="{{ $bien->reference }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <span class="absolute top-6 left-6 px-4 py-1.5 bg-secondary text-white text-[10px] font-black rounded-full uppercase tracking-widest">
                            {{ $bien->type_bien }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg group-hover:text-secondary transition uppercase truncate max-w-[200px]">{{ $bien->reference }}</h3>
                                <p class="text-xs text-gray-400 flex items-center gap-1 mt-1 font-semibold italic">
                                    <i class="fa-solid fa-location-dot text-secondary"></i>
                                    {{ $bien->commune }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4 mb-6 py-4 border-y border-gray-50">
                            <div class="text-center flex-1">
                                <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest mb-1">Pièces</p>
                                <p class="text-xs font-black text-primary">{{ $bien->nb_pieces }}</p>
                            </div>
                            <div class="w-px bg-gray-100"></div>
                            <div class="text-center flex-1">
                                <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest mb-1">Surface</p>
                                <p class="text-xs font-black text-primary">{{ (int)$bien->superficie }} m²</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest mb-0.5">Loyer mensuel</p>
                                <p class="text-xl font-black text-primary">{{ number_format($bien->loyer_mensuel, 0, ',', ' ') }} <span class="text-[10px] text-gray-400">FCFA</span></p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('biens.show', $bien->id) }}" class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center hover:bg-secondary transition-all">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('visite.create', $bien->id) }}" class="w-10 h-10 rounded-xl border-2 border-secondary text-secondary flex items-center justify-center hover:bg-secondary hover:text-white transition-all">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-search text-4xl text-gray-200"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700">Aucun bien trouvé</h3>
                    <p class="text-gray-400 mt-2">Désolé, nous n'avons aucun bien correspondant à vos critères actuels.</p>
                    <a href="{{ route('biens.all') }}" class="inline-block mt-6 text-secondary font-bold hover:underline">Voir tout le catalogue</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-20">
            {{ $biens->links() }}
        </div>

    </div>
</section>
@endsection
