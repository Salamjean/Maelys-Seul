@extends('home.layouts.app')

@section('title', 'Prendre rendez-vous — ' . $bien->reference)

@section('content')
<section class="py-12 lg:py-20 min-h-screen bg-gray-50 flex items-center justify-center p-4">
    
    {{-- Main Luxury Card --}}
    <div class="w-full max-w-6xl bg-white rounded-[2.5rem] shadow-2xl shadow-primary/10 overflow-hidden flex flex-col lg:flex-row border border-gray-100">
        
        {{-- Left Side: Property Showcase --}}
        <div class="lg:w-[45%] relative min-h-[350px] lg:min-h-0">
            <img src="{{ Storage::url($bien->photo_principale) }}" alt="{{ $bien->reference }}" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/30 to-transparent"></div>
            
            {{-- Content Overlay --}}
            <div class="absolute inset-0 p-8 lg:p-12 flex flex-col justify-between">
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 group text-white/80 hover:text-white transition">
                        <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur flex items-center justify-center group-hover:bg-secondary transition">
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest">Retour</span>
                    </a>
                </div>

                <div class="text-white">
                    <span class="inline-block px-3 py-1 bg-secondary text-[9px] font-black rounded-md uppercase tracking-wider mb-4 shadow-lg">Visite Privée</span>
                    <h1 class="text-3xl lg:text-5xl font-black leading-tight uppercase mb-2">{{ $bien->type_bien }}</h1>
                    <p class="text-blue-100 flex items-center gap-2 text-sm font-semibold italic">
                        <i class="fa-solid fa-location-dot text-secondary"></i>
                        {{ $bien->commune }} — Ref: {{ $bien->reference }}
                    </p>
                    
                    <div class="grid grid-cols-2 gap-4 mt-8 pt-8 border-t border-white/20">
                        <div class="bg-white/10 backdrop-blur p-3 rounded-xl">
                            <p class="text-[8px] font-bold text-blue-200 uppercase tracking-widest mb-1">Espace</p>
                            <p class="text-lg font-bold">{{ (int)$bien->superficie }} m²</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur p-3 rounded-xl">
                            <p class="text-[8px] font-bold text-blue-200 uppercase tracking-widest mb-1">Loyer</p>
                            <p class="text-lg font-bold">{{ number_format($bien->loyer_mensuel, 0, ',', ' ') }} <small class="text-[10px]">FCFA</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side: High-End Form --}}
        <div class="lg:w-[55%] p-8 lg:p-14">
            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl lg:text-4xl font-black text-primary mb-2">Demande de <span class="text-secondary italic">Visite</span></h2>
                <p class="text-gray-400 text-sm font-medium">Planifiez votre rendez-vous en quelques secondes.</p>
                @if ($errors->any())
                    <div class="mt-6 p-4 bg-red-50 border-2 border-red-100 rounded-2xl flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-500 mt-1"></i>
                        <p class="text-xs text-red-600 font-bold leading-relaxed">
                            {{ $errors->first() }} (Merci de vérifier tous les champs)
                        </p>
                    </div>
                @endif
            </div>

            <form action="{{ route('visite.store', $bien->id) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">votre nom</label>
                        <div class="relative group">
                            <input type="text" name="nom" value="{{ old('nom') }}" required placeholder="Ex: Jean Dupont"
                                class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all text-sm font-bold text-primary">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">téléphone</label>
                        <div class="relative group">
                            <input type="tel" name="telephone" value="{{ old('telephone') }}" required placeholder="Ex: +225 ..."
                                class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all text-sm font-bold text-primary">
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="votre@mail.com"
                        class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all text-sm font-bold text-primary">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Date souhaitée</label>
                        <input type="date" name="date_visite" value="{{ old('date_visite') }}" required min="{{ date('Y-m-d') }}"
                            class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all text-sm font-bold text-primary cursor-pointer">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Heure (Max 18h)</label>
                        <input type="time" name="heure_visite" value="{{ old('heure_visite') }}" required min="08:00" max="18:00"
                            class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all text-sm font-bold text-primary cursor-pointer">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Message (optionnel)</label>
                    <textarea name="message" rows="3" placeholder="Avez-vous des questions ?"
                        class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all text-sm font-bold text-primary resize-none">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="w-full py-5 bg-secondary text-white font-black rounded-2xl shadow-xl shadow-secondary/30 hover:opacity-95 transition-all flex items-center justify-center gap-3 active:scale-95">
                    <i class="fa-solid fa-paper-plane"></i>
                    ENVOYER MA DEMANDE
                </button>
                
                <p class="text-[9px] text-gray-400 text-center font-medium italic">
                    <i class="fa-solid fa-shield-halved mr-1 text-green-500"></i> Vos données sont sécurisées et ne seront pas partagées.
                </p>
            </form>
        </div>
    </div>
</section>
@endsection
