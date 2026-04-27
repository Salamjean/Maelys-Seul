@extends('home.layouts.app')

@section('title', 'Maelys-imo — Trouvez votre logement à louer')

@section('content')

    {{-- Hero Carousel --}}
    @include('home.layouts.carousel')

    {{-- ===== CATEGORIES ===== --}}
    <section class="py-8 bg-gray-50">
        <div class="w-full" style="padding-left:5%;padding-right:5%;">
            <div class="text-center mb-6">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#ff5e14;">Parcourir par
                    catégorie</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto">
                @foreach ([
                    ['icon' => 'fa-building', 'label' => 'Appartements', 'slug' => 'appartement', 'count' => '412'],
                    ['icon' => 'fa-house', 'label' => 'Maisons', 'slug' => 'maison', 'count' => '245'],
                    ['icon' => 'fa-briefcase', 'label' => 'Bureaux', 'slug' => 'bureau', 'count' => '128']
                ] as $cat)
                    <a href="{{ route('home', ['type_bien' => $cat['slug']]) }}"
                        class="group bg-white rounded-2xl p-6 text-center shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 {{ request('type_bien') == $cat['slug'] ? 'border-secondary bg-orange-50/30' : 'hover:border-orange-300' }}">
                        <div class="w-14 h-14 rounded-xl mx-auto mb-3 flex items-center justify-center transition-all duration-300 {{ request('type_bien') == $cat['slug'] ? 'bg-secondary' : 'bg-primary/10' }}"
                             style="{{ request('type_bien') == $cat['slug'] ? '' : 'background-color: rgba(2,36,91,0.08);' }}">
                            <i class="fa-solid {{ $cat['icon'] }} text-xl {{ request('type_bien') == $cat['slug'] ? 'text-white' : 'text-primary' }}"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 text-sm group-hover:text-primary transition">
                            {{ $cat['label'] }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $cat['count'] }} annonces</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== FEATURED PROPERTIES ===== --}}
    <section class="py-20 bg-white">
        <div class="w-full" style="padding-left:5%;padding-right:5%;">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-widest" style="color:#ff5e14;">Biens à la
                        une</span>
                    <h2 class="text-3xl sm:text-4xl font-bold mt-2" style="color:#02245b;">Annonces récentes</h2>
                </div>
                <a href="{{ route('biens.all') }}" class="flex items-center gap-2 text-sm font-semibold hover:opacity-80 transition"
                    style="color:#ff5e14;">
                    Voir toutes les annonces <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            {{-- Search Box --}}
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 mb-10 shadow-sm">
                <form action="{{ route('home') }}" method="GET">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-center">
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="commune" value="{{ request('commune') }}" placeholder="Ville, commune..."
                                class="search-input w-full pl-9 pr-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700 bg-white transition focus:ring-2 focus:ring-secondary/20">
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-building absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <select name="type_bien"
                                class="search-input w-full pl-9 pr-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700 bg-white appearance-none transition focus:ring-2 focus:ring-secondary/20">
                                <option value="">Type de bien</option>
                                <option value="appartement" {{ request('type_bien') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                                <option value="maison" {{ request('type_bien') == 'maison' ? 'selected' : '' }}>Maison / Villa</option>
                                <option value="bureau" {{ request('type_bien') == 'bureau' ? 'selected' : '' }}>Bureau</option>
                                <option value="local_commercial" {{ request('type_bien') == 'local_commercial' ? 'selected' : '' }}>Local commercial</option>
                            </select>
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-money-bill-wave absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="number" name="loyer_max" value="{{ request('loyer_max') }}" min="0" placeholder="Budget max (FCFA)"
                                class="search-input w-full pl-9 pr-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700 bg-white transition focus:ring-2 focus:ring-secondary/20">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" style="background-color:#ff5e14;"
                                class="flex-1 py-3 px-4 text-white font-semibold rounded-xl hover:opacity-90 transition-all flex items-center justify-center gap-2 shadow-lg active:scale-95">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Rechercher
                            </button>
                            @if(request()->anyFilled(['commune', 'type_bien', 'loyer_max']))
                                <a href="{{ route('home') }}" title="Vider les filtres"
                                    class="w-12 h-12 bg-gray-200 text-gray-600 rounded-xl flex items-center justify-center hover:bg-gray-300 transition-all active:scale-95">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-7">
                @foreach ($biens as $bien)
                    <div class="property-card bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100 group">
                        {{-- Image --}}
                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ Storage::url($bien->photo_principale) }}" alt="{{ $bien->reference }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            {{-- Tag --}}
                            <span class="absolute top-4 left-4 text-[10px] font-bold text-white px-3 py-1 rounded-full shadow bg-secondary uppercase tracking-widest">
                                @if($bien->type_utilisation === 'habitation') Habitation @else Pro @endif
                            </span>
                        </div>

                        {{-- Content --}}
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h3 class="font-bold text-gray-800 text-base leading-tight group-hover:text-secondary transition uppercase">
                                    {{ $bien->typologie }} - {{ $bien->type_bien }}
                                </h3>
                            </div>
                            <p class="text-sm text-gray-500 flex items-center gap-1.5 mb-4">
                                <i class="fa-solid fa-location-dot text-secondary text-xs"></i>
                                {{ $bien->commune }}
                            </p>

                            {{-- Specs --}}
                            <div class="flex items-center gap-4 text-xs text-gray-500 mb-4 pb-4 border-b border-gray-100">
                                <span class="flex items-center gap-1">
                                    <i class="fa-solid fa-bed text-primary"></i>
                                    {{ $bien->nb_pieces }} pièces
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-solid fa-expand text-primary"></i>
                                    {{ (int)$bien->superficie }} m²
                                </span>
                                <span class="flex items-center gap-1" title="Toilettes">
                                    <i class="fa-solid fa-toilet text-primary"></i>
                                    {{ $bien->nb_toilettes }}
                                </span>
                            </div>

                            {{-- Price & CTA --}}
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xl font-extrabold text-primary">{{ number_format($bien->loyer_mensuel, 0, ',', ' ') }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold ml-1 uppercase">FCFA</span>
                                </div>
                                {{-- Pour l'instant on renvoie vers une route publique show si elle existe, sinon on bloque --}}
                             <div class="flex items-center gap-2">
                                <a href="{{ route('biens.show', $bien->id) }}"
                                   class="flex-1 text-center text-[10px] font-black px-3 py-2.5 rounded-lg text-white transition hover:opacity-90 bg-secondary uppercase">
                                    Découvrir
                                </a>
                                <a href="{{ route('visite.create', $bien->id) }}" title="Demander une visite"
                                   class="flex items-center justify-center min-w-[40px] h-10 rounded-lg border-2 border-secondary text-secondary transition hover:bg-secondary hover:text-white">
                                    <i class="fa-solid fa-calendar-days text-sm"></i>
                                </a>
                             </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Load more --}}
            <div class="text-center mt-12">
                <a href="{{ route('biens.all') }}" style="background-color:#02245b;"
                    class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl font-semibold text-white transition hover:opacity-90 shadow-lg">
                    Découvrir toutes les annonces
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ===== HOW IT WORKS ===== --}}
    <section class="py-20" style="background-color: #02245b;">
        <div class="w-full" style="padding-left:5%;padding-right:5%;">
            <div class="text-center mb-14">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#ff5e14;">Simple & rapide</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-white mt-2">Comment ça fonctionne ?</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                {{-- connector line --}}
                <div class="hidden md:block absolute top-12 left-1/3 right-1/3 h-0.5 opacity-20"
                    style="background-color:#ff5e14;"></div>

                @foreach ([['step' => '01', 'icon' => 'fa-magnifying-glass', 'title' => 'Recherchez', 'desc' => 'Parcourez des milliers d\'annonces de location filtrées par ville, type de bien et budget.'], ['step' => '02', 'icon' => 'fa-calendar-check', 'title' => 'Contactez', 'desc' => 'Prenez directement contact avec le propriétaire ou l\'agence en un seul clic.'], ['step' => '03', 'icon' => 'fa-handshake', 'title' => 'Emménagez', 'desc' => 'Finalisez votre contrat de location en toute sécurité avec notre accompagnement.']] as $step)
                    <div class="text-center relative">
                        <div class="w-24 h-24 rounded-2xl mx-auto mb-6 flex items-center justify-center relative"
                            style="background: linear-gradient(135deg, rgba(255,94,20,0.15), rgba(255,94,20,0.05)); border: 2px solid rgba(255,94,20,0.3);">
                            <i class="fa-solid {{ $step['icon'] }} text-3xl" style="color:#ff5e14;"></i>
                            <span
                                class="absolute -top-3 -right-3 w-7 h-7 rounded-full text-xs font-bold text-white flex items-center justify-center"
                                style="background-color:#ff5e14;">{{ $step['step'] }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">{{ $step['title'] }}</h3>
                        <p class="text-blue-200 text-sm leading-relaxed max-w-xs mx-auto">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== WHY CHOOSE US ===== --}}
    <section class="py-20 bg-gray-50">
        <div class="w-full" style="padding-left:5%;padding-right:5%;">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-widest" style="color:#ff5e14;">Pourquoi nous
                        choisir</span>
                    <h2 class="text-3xl sm:text-4xl font-bold mt-2 mb-6" style="color:#02245b;">
                        La plateforme immobilière<br>de confiance
                    </h2>
                    <p class="text-gray-500 mb-10 leading-relaxed">
                        Maelys-imo vous offre une expérience unique pour publier et trouver des biens à louer. Des
                        annonces vérifiées, un accompagnement personnalisé et des outils modernes pour faciliter votre
                        recherche de logement.
                    </p>
                    <div class="space-y-5">
                        @foreach ([['icon' => 'fa-shield-halved', 'title' => 'Annonces vérifiées', 'desc' => 'Chaque annonce est vérifiée par notre équipe pour garantir son authenticité.'], ['icon' => 'fa-bolt', 'title' => 'Mise en ligne rapide', 'desc' => 'Publiez votre bien à louer en moins de 5 minutes et touchez des milliers de locataires.'], ['icon' => 'fa-headset', 'title' => 'Support dédié', 'desc' => 'Notre équipe est disponible 7j/7 pour vous accompagner dans votre recherche.'], ['icon' => 'fa-chart-line', 'title' => 'Visibilité maximale', 'desc' => 'Vos annonces sont optimisées pour être vues par le plus grand nombre de locataires.']] as $feat)
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                                    style="background-color: rgba(255,94,20,0.1);">
                                    <i class="fa-solid {{ $feat['icon'] }}" style="color:#ff5e14;"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-0.5">{{ $feat['title'] }}</h4>
                                    <p class="text-sm text-gray-500">{{ $feat['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Visual card --}}
                <div class="relative">
                    <div class="rounded-3xl overflow-hidden shadow-2xl h-96"
                        style="background: linear-gradient(135deg, #02245b, #03306e);">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fa-solid fa-city text-white opacity-10 text-9xl"></i>
                        </div>
                        <div class="absolute inset-0 p-8 flex flex-col justify-end">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 text-white">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                        style="background-color:#ff5e14;">
                                        <i class="fa-solid fa-star text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold">Qualité garantie</p>
                                        <p class="text-xs text-blue-200">Toutes nos annonces sont fiables</p>
                                    </div>
                                </div>
                                <div class="flex gap-4 text-center">
                                    <div class="flex-1">
                                        <p class="text-2xl font-extrabold" style="color:#ff5e14;">98%</p>
                                        <p class="text-xs text-blue-200">Satisfaction</p>
                                    </div>
                                    <div class="w-px bg-white/20"></div>
                                    <div class="flex-1">
                                        <p class="text-2xl font-extrabold" style="color:#ff5e14;">4.9/5</p>
                                        <p class="text-xs text-blue-200">Note moyenne</p>
                                    </div>
                                    <div class="w-px bg-white/20"></div>
                                    <div class="flex-1">
                                        <p class="text-2xl font-extrabold" style="color:#ff5e14;">5ans</p>
                                        <p class="text-xs text-blue-200">D'expérience</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- floating badge --}}
                    <div
                        class="absolute -top-5 -right-5 bg-white rounded-2xl p-4 shadow-xl hidden lg:flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                            style="background-color:#02245b;">
                            <i class="fa-solid fa-bell text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Nouvelle offre</p>
                            <p class="text-sm font-bold text-gray-800">+15 annonces aujourd'hui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== PUBLISH CTA ===== --}}
    <section class="py-20 bg-white">
        <div class="w-full" style="padding-left:5%;padding-right:5%;">
            <div class="rounded-3xl overflow-hidden relative"
                style="background: linear-gradient(135deg, #02245b, #021d4a);">
                <div class="absolute top-0 right-0 w-72 h-72 rounded-full opacity-10"
                    style="background: radial-gradient(circle, #ff5e14, transparent); transform: translate(30%, -30%);">
                </div>
                <div class="absolute bottom-0 left-0 w-56 h-56 rounded-full opacity-10"
                    style="background: radial-gradient(circle, #ff5e14, transparent); transform: translate(-20%, 30%);">
                </div>

                <div class="relative z-10 p-10 sm:p-16 text-center">
                    <div class="w-16 h-16 rounded-2xl mx-auto mb-6 flex items-center justify-center"
                        style="background-color:#ff5e14;">
                        <i class="fa-solid fa-plus text-white text-2xl"></i>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">
                        Publiez votre bien à louer gratuitement
                    </h2>
                    <p class="text-blue-200 text-lg max-w-xl mx-auto mb-8">
                        Rejoignez des milliers de propriétaires qui font confiance à Maelys-imo pour louer leurs biens
                        rapidement et trouver des locataires sérieux.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="#" style="background-color:#ff5e14;"
                            class="px-8 py-4 text-white font-bold rounded-xl hover:opacity-90 transition shadow-lg text-base flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus"></i>
                            Publier une annonce
                        </a>
                        <a href="#"
                            class="px-8 py-4 text-white font-bold rounded-xl transition text-base flex items-center justify-center gap-2"
                            style="border: 2px solid rgba(255,255,255,0.3);"
                            onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'"
                            onmouseout="this.style.backgroundColor=''">
                            <i class="fa-solid fa-circle-info"></i>
                            En savoir plus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== TESTIMONIALS ===== --}}
    <section class="py-20 bg-gray-50">
        <div class="w-full" style="padding-left:5%;padding-right:5%;">
            <div class="text-center mb-12">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#ff5e14;">Témoignages</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2" style="color:#02245b;">Ce que disent nos clients</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
                @foreach ([['name' => 'Karim Benali', 'role' => 'Acheteur', 'comment' => 'J\'ai trouvé mon appartement en moins d\'une semaine ! La plateforme est très simple à utiliser et les annonces sont toutes vérifiées.', 'stars' => 5], ['name' => 'Nadia Hamid', 'role' => 'Propriétaire', 'comment' => 'J\'ai vendu mon bien en 3 semaines. L\'équipe Maelys-imo m\'a accompagnée tout au long du processus. Je recommande vivement !', 'stars' => 5], ['name' => 'Youcef Mansouri', 'role' => 'Locataire', 'comment' => 'Excellent service ! J\'ai trouvé un studio meublé à Oran très rapidement. Les photos et descriptions sont fidèles à la réalité.', 'stars' => 5]] as $review)
                    <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex gap-1 mb-4">
                            @for ($s = 0; $s < $review['stars']; $s++)
                                <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            @endfor
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed mb-5">"{{ $review['comment'] }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                style="background-color:#02245b;">
                                {{ substr($review['name'], 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $review['name'] }}</p>
                                <p class="text-xs text-gray-400">{{ $review['role'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
