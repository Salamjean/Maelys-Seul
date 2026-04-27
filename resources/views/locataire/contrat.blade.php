@extends('locataire.layouts.app')

@section('title', 'Mon Contrat de Bail')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header -->
    <div class="bg-white rounded-[40px] p-10 border border-gray-100 shadow-xl shadow-gray-200/50">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-1.5 h-12 bg-secondary rounded-full"></div>
                <div>
                    <h1 class="text-3xl font-black text-primary italic uppercase tracking-tighter">Mon <span class="text-secondary">Contrat</span></h1>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mt-1">Informations sur votre bail et votre logement</p>
                </div>
            </div>
            @if($user->contrat_bail)
            <a href="{{ asset('storage/' . $user->contrat_bail) }}" target="_blank" class="flex items-center gap-3 bg-primary text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary/90 transition-all shadow-lg shadow-blue-900/20">
                <i class="fa-solid fa-download"></i>
                Télécharger le bail
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Colonne de Gauche : Images du bien -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[40px] p-8 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-black text-primary italic uppercase">Galerie du <span class="text-secondary">Logement</span></h2>
                    <span class="bg-secondary/10 text-secondary text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest">
                        {{ $bien->reference ?? 'REF-EXTERNE' }}
                    </span>
                </div>

                <!-- Main Image -->
                <div class="relative rounded-3xl overflow-hidden aspect-video mb-6 group">
                    <img src="{{ asset('storage/' . $bien->photo_principale) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Photo principale">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>

                <!-- Supplementary Photos -->
                @if($bien->photos_supplementaires && count($bien->photos_supplementaires) > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($bien->photos_supplementaires as $photo)
                    <div class="relative rounded-2xl overflow-hidden aspect-square cursor-pointer group">
                        <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Détails Techniques -->
            <div class="bg-primary rounded-[40px] p-10 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-bl-[10rem]"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-black italic uppercase mb-8">Caractéristiques <span class="text-secondary">Techniques</span></h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        <div class="space-y-1">
                            <span class="text-white/40 text-[9px] font-black uppercase tracking-widest">Type de bien</span>
                            <p class="font-bold text-sm italic">{{ $bien->type_bien }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-white/40 text-[9px] font-black uppercase tracking-widest">Typologie</span>
                            <p class="font-bold text-sm italic">{{ $bien->typologie }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-white/40 text-[9px] font-black uppercase tracking-widest">Superficie</span>
                            <p class="font-bold text-sm italic">{{ $bien->superficie }} m²</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-white/40 text-[9px] font-black uppercase tracking-widest">Pièces</span>
                            <p class="font-bold text-sm italic">{{ $bien->nb_pieces }} Pièces</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne de Droite : Contrat Card -->
        <div class="space-y-8">
            <div class="bg-white rounded-[40px] p-8 border border-gray-100 shadow-xl sticky top-8">
                <div class="w-20 h-20 bg-secondary/10 rounded-3xl flex items-center justify-center text-secondary mb-8">
                    <i class="fa-solid fa-file-signature text-3xl"></i>
                </div>
                
                <h2 class="text-2xl font-black text-primary italic uppercase mb-2">Votre <span class="text-secondary">Bail</span></h2>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-8">Contrat de bail sécurisé</p>

                <div class="space-y-6 mb-10">
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Date de début</span>
                        <p class="text-primary font-black italic">{{ \Carbon\Carbon::parse($user->contract_start_date)->translatedFormat('d F Y') }}</p>
                    </div>
                    
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Loyer Mensuel</span>
                        <p class="text-secondary font-black italic text-xl">{{ number_format($bien->loyer_mensuel, 0, ',', ' ') }} <span class="text-xs uppercase">CFA</span></p>
                    </div>

                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Commune</span>
                        <p class="text-primary font-black italic">{{ $bien->commune }}</p>
                    </div>
                </div>

                @if($user->contrat_bail)
                <div class="space-y-4">
                    <a href="{{ asset('storage/' . $user->contrat_bail) }}" target="_blank" class="w-full flex items-center justify-center gap-3 bg-secondary text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-[1.02] transition-all shadow-xl shadow-secondary/20">
                        <i class="fa-solid fa-eye"></i>
                        Consulter le bail
                    </a>
                    <a href="{{ asset('storage/' . $user->contrat_bail) }}" download class="w-full flex items-center justify-center gap-3 border-2 border-primary text-primary py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary hover:text-white transition-all">
                        <i class="fa-solid fa-cloud-arrow-down"></i>
                        Télécharger PDF
                    </a>
                </div>
                @else
                <div class="p-6 bg-orange-50 rounded-3xl border border-orange-100 text-center">
                    <i class="fa-solid fa-clock-rotate-left text-orange-400 text-2xl mb-3"></i>
                    <p class="text-orange-800 text-[10px] font-black uppercase tracking-tight">Le contrat est en cours de numérisation</p>
                </div>
                @endif

                <div class="mt-8 pt-8 border-t border-gray-100 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-green-50 text-green-500 flex items-center justify-center">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-primary uppercase">Document Certifié</p>
                        <p class="text-[8px] font-bold text-gray-400 uppercase">Maelys Immobilier SARL</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
