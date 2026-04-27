@extends('locataire.layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-12">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-primary italic uppercase tracking-tighter">Mon <span class="text-secondary">Profil</span></h1>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[2px] mt-1">Gérez vos informations personnelles</p>
        </div>
        <div class="w-16 h-16 bg-primary/5 rounded-[2rem] flex items-center justify-center text-primary text-2xl shadow-inner border border-primary/10">
            <i class="fa-solid fa-user-gear"></i>
        </div>
    </div>

    <form action="{{ route('locataire.profile.update') }}" method="POST" class="space-y-8">
        @csrf
        
        @if(session('success'))
            <div class="bg-green-50 text-green-600 p-6 rounded-[2rem] text-sm font-black border border-green-100 flex items-center gap-4 animate-fade-in shadow-sm">
                <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-green-500/20">
                    <i class="fa-solid fa-check"></i>
                </div>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Informations de base --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-6">
                <h3 class="text-sm font-black text-primary uppercase tracking-widest border-b border-gray-50 pb-4">Identité & Contact</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Nom</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-primary">
                        @error('name') <p class="text-red-500 text-[10px] font-black mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Prénoms</label>
                        <input type="text" name="prenoms" value="{{ old('prenoms', $user->prenoms) }}" required
                               class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-primary">
                        @error('prenoms') <p class="text-red-500 text-[10px] font-black mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Adresse Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-primary">
                        @error('email') <p class="text-red-500 text-[10px] font-black mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Numéro de Contact</label>
                        <input type="text" name="contact" value="{{ old('contact', $user->contact) }}" required
                               class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-primary">
                        @error('contact') <p class="text-red-500 text-[10px] font-black mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Sécurité --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-6">
                <h3 class="text-sm font-black text-primary uppercase tracking-widest border-b border-gray-50 pb-4">Sécurité</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Nouveau mot de passe (optionnel)</label>
                        <input type="password" name="new_password" placeholder="Laisser vide pour ne pas changer"
                               class="w-full bg-gray-50 border-2 border-gray-50 focus:border-secondary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm">
                        @error('new_password') <p class="text-red-500 text-[10px] font-black mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="new_password_confirmation" placeholder="Confirmer le nouveau mot de passe"
                               class="w-full bg-gray-50 border-2 border-gray-50 focus:border-secondary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm">
                    </div>

                    <div class="pt-6 border-t border-gray-50 mt-6">
                        <div class="bg-primary/5 p-6 rounded-[2rem] border-2 border-dashed border-primary/10">
                            <label class="text-[10px] font-black uppercase text-primary mb-3 block tracking-widest text-center">Validation requise</label>
                            <input type="password" name="current_password" required placeholder="Saisir votre mot de passe actuel"
                                   class="w-full bg-white border-2 border-primary/20 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-black text-sm text-center shadow-sm placeholder:font-bold placeholder:text-primary/30">
                            <p class="text-[9px] text-primary/40 font-bold text-center mt-3 uppercase tracking-tighter italic">Indispensable pour enregistrer les modifications</p>
                            @error('current_password') <p class="text-red-500 text-[10px] font-black mt-2 text-center">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="h-16 px-12 bg-primary text-white rounded-[2rem] font-black uppercase text-xs tracking-[2px] hover:bg-secondary hover:shadow-xl hover:shadow-secondary/20 transition-all flex items-center gap-4 group">
                <span>Enregistrer les modifications</span>
                <i class="fa-solid fa-floppy-disk text-sm group-hover:scale-110 transition-transform"></i>
            </button>
        </div>
    </form>

    {{-- Info Card --}}
    <div class="bg-gradient-to-br from-primary to-blue-900 p-8 rounded-[2.5rem] text-white relative overflow-hidden shadow-2xl shadow-blue-900/20">
        <div class="relative z-10 flex items-center gap-8">
            <div class="hidden md:flex w-20 h-20 bg-white/10 rounded-3xl items-center justify-center text-4xl">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h4 class="text-xl font-black italic tracking-tighter mb-2 uppercase">Sécurité de vos données</h4>
                <p class="text-sm text-white/70 font-medium leading-relaxed max-w-xl">
                    Chez Maelys Immobilier, nous prenons la sécurité au sérieux. C'est pourquoi nous exigeons votre mot de passe actuel pour toute modification de profil. Assurez-vous d'utiliser un mot de passe robuste.
                </p>
            </div>
        </div>
        <i class="fa-solid fa-user-lock absolute -right-8 -bottom-8 text-white/5 text-9xl"></i>
    </div>
</div>
@endsection
