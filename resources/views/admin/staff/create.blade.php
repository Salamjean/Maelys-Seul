@extends('admin.layouts.app')

@section('title', 'Inviter un Collaborateur')
@section('page-title', 'Nouveau Collaborateur')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ step: 1 }">
    <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-primary transition mb-8">
        <i class="fa-solid fa-arrow-left"></i> Retour à la liste
    </a>

    {{-- Stepper Progress Bar --}}
    <div class="mb-10 px-10">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 bg-gray-100 w-full z-0"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 bg-secondary z-0 transition-all duration-500" :style="step === 1 ? 'width: 0%' : 'width: 100%'"></div>
            
            {{-- Step 1 Indicator --}}
            <div class="relative z-10 flex flex-col items-center gap-3">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-sm transition-all duration-500 shadow-lg"
                     :class="step >= 1 ? 'bg-secondary text-white shadow-orange-500/30' : 'bg-white text-gray-300 border-2 border-gray-100'">
                    1
                </div>
                <span class="text-[9px] font-black uppercase tracking-widest" :class="step >= 1 ? 'text-primary' : 'text-gray-300'">Compte & Rôle</span>
            </div>

            {{-- Step 2 Indicator --}}
            <div class="relative z-10 flex flex-col items-center gap-3">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-sm transition-all duration-500 shadow-lg"
                     :class="step === 2 ? 'bg-secondary text-white shadow-orange-500/30' : 'bg-white text-gray-300 border-2 border-gray-100'">
                    2
                </div>
                <span class="text-[9px] font-black uppercase tracking-widest" :class="step === 2 ? 'text-primary' : 'text-gray-300'">Infos & Urgence</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="p-10 border-b border-gray-50 bg-gray-50/30">
            <h2 class="text-2xl font-black text-primary italic uppercase tracking-tighter">Fiche <span class="text-secondary">Invitation</span></h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1" x-text="step === 1 ? 'Étape 1 : Identité et accès système' : 'Étape 2 : Coordonnées personnelles et contact d\'urgence'"></p>
        </div>

        <form action="{{ route('admin.staff.store') }}" method="POST" class="p-10">
            @csrf

            {{-- Step 1: Account & Role --}}
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-10" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-black text-primary uppercase tracking-[2px] ml-1 block mb-3">Nom Complet</label>
                        <div class="relative group">
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex: Jean Dupont"
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-secondary focus:bg-white rounded-2xl h-14 pl-12 pr-6 outline-none transition-all font-bold text-sm text-primary shadow-inner">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                                <i class="fa-solid fa-user text-sm"></i>
                            </div>
                        </div>
                        @error('name') <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-primary uppercase tracking-[2px] ml-1 block mb-3">Adresse Email Professionnelle</label>
                        <div class="relative group">
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="Ex: staff@maelysimo.com"
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-secondary focus:bg-white rounded-2xl h-14 pl-12 pr-6 outline-none transition-all font-bold text-sm text-primary shadow-inner">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </div>
                        </div>
                        @error('email') <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[2px] ml-1 block mb-4">Rôle Attribué</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="role" value="comptable" class="peer hidden" required {{ old('role', 'comptable') === 'comptable' ? 'checked' : '' }}>
                                <div class="p-8 border-2 border-gray-50 rounded-2xl bg-gray-50 peer-checked:border-secondary peer-checked:bg-orange-50/50 transition-all text-center">
                                    <i class="fa-solid fa-calculator text-3xl text-gray-400 peer-checked:text-secondary mb-3 transition-colors"></i>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-secondary transition-colors">Comptabilité</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="role" value="recouvrement" class="peer hidden" required {{ old('role') === 'recouvrement' ? 'checked' : '' }}>
                                <div class="p-8 border-2 border-gray-50 rounded-2xl bg-gray-50 peer-checked:border-secondary peer-checked:bg-orange-50/50 transition-all text-center">
                                    <i class="fa-solid fa-hand-holding-dollar text-3xl text-gray-400 peer-checked:text-secondary mb-3 transition-colors"></i>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-secondary transition-colors">Recouvrement</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-50 flex justify-end">
                    <button type="button" @click="step = 2" class="h-14 px-10 bg-primary text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-secondary transition-all shadow-xl shadow-blue-900/20 flex items-center gap-3 group">
                        Suivant <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </div>

            {{-- Step 2: Personal & Emergency --}}
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-10" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-8" style="display:none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[2px] ml-1 block mb-3">Lieu de résidence</label>
                        <div class="relative group">
                            <input type="text" name="residence" value="{{ old('residence') }}" placeholder="Ex: Cocody, Abidjan"
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-secondary focus:bg-white rounded-2xl h-14 pl-12 pr-6 outline-none transition-all font-bold text-sm text-primary shadow-inner">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                                <i class="fa-solid fa-location-dot text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-primary uppercase tracking-[2px] ml-1 block mb-3">Contact d'urgence (Nom)</label>
                        <div class="relative group">
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Nom complet"
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-secondary focus:bg-white rounded-2xl h-14 pl-12 pr-6 outline-none transition-all font-bold text-sm text-primary shadow-inner">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                                <i class="fa-solid fa-user-shield text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-primary uppercase tracking-[2px] ml-1 block mb-3">Lien de parenté</label>
                        <div class="relative group">
                            <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation') }}" placeholder="Ex: Frère, Épouse..."
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-secondary focus:bg-white rounded-2xl h-14 pl-12 pr-6 outline-none transition-all font-bold text-sm text-primary shadow-inner">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                                <i class="fa-solid fa-users text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[2px] ml-1 block mb-3">Téléphone Urgence</label>
                        <div class="relative group">
                            <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="+225 XX XX XX XX XX"
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-secondary focus:bg-white rounded-2xl h-14 pl-12 pr-6 outline-none transition-all font-bold text-sm text-primary shadow-inner">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                                <i class="fa-solid fa-phone text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-50 flex items-center justify-between">
                    <button type="button" @click="step = 1" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-primary transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Précédent
                    </button>
                    <button type="submit" class="h-16 px-12 bg-primary text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-secondary transition-all shadow-2xl shadow-blue-900/20 hover:shadow-orange-500/20 flex items-center gap-4">
                        <i class="fa-solid fa-paper-plane"></i> ENVOYER L'INVITATION
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- AlpineJS est probablement déjà inclus dans le layout app, sinon décommentez ci-dessous --}}
{{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
@endpush
