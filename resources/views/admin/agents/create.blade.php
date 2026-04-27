@extends('admin.layouts.app')

@section('title', 'Ajouter un Agent')
@section('page-title', 'Nouvel Agent')
@section('page-subtitle', 'Créez un accès pour un nouveau membre de l\'équipe')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10">
            <form action="{{ route('admin.agents.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Nom --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1 block mb-3">Nom Complet</label>
                        <div class="relative">
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-gray-700 shadow-inner"
                                   placeholder="Nom et Prénoms">
                            <i class="fa-solid fa-user absolute right-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        </div>
                        @error('name') <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1 block mb-3">Adresse Email</label>
                        <div class="relative">
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-gray-700 shadow-inner"
                                   placeholder="agent@maelysimo.com">
                            <i class="fa-solid fa-envelope absolute right-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        </div>
                        @error('email') <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 py-4">
                        <div class="h-px bg-gray-100 w-full"></div>
                        <h4 class="text-[10px] font-black text-secondary uppercase tracking-[2px] mt-6 mb-2 italic">L'agent recevra un mail pour définir son mot de passe</h4>
                    </div>

                    <div class="md:col-span-2 py-4">
                        <div class="h-px bg-gray-100 w-full"></div>
                        <h4 class="text-[10px] font-black text-secondary uppercase tracking-[2px] mt-6 mb-2">Informations Personnelles & Urgence</h4>
                    </div>

                    {{-- Résidence --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1 block mb-3">Lieu de résidence</label>
                        <div class="relative">
                            <input type="text" name="residence" value="{{ old('residence') }}"
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-gray-700 shadow-inner"
                                   placeholder="Quartier, Ville">
                            <i class="fa-solid fa-location-dot absolute right-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        </div>
                    </div>

                    {{-- Nom Urgence --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1 block mb-3">Personne à contacter (Urgence)</label>
                        <div class="relative">
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-gray-700 shadow-inner"
                                   placeholder="Nom du contact">
                            <i class="fa-solid fa-user-shield absolute right-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        </div>
                    </div>

                    {{-- Contact Urgence --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1 block mb-3">Contact Urgence</label>
                        <div class="relative">
                            <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}"
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-gray-700 shadow-inner"
                                   placeholder="+225 XX XX XX XX XX">
                            <i class="fa-solid fa-phone absolute right-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        </div>
                    </div>

                    {{-- Lien Parenté --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1 block mb-3">Lien de parenté</label>
                        <div class="relative">
                            <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation') }}"
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-gray-700 shadow-inner"
                                   placeholder="Ex: Frère, Épouse, etc.">
                            <i class="fa-solid fa-users absolute right-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-50 flex items-center justify-between">
                    <a href="{{ route('admin.agents.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-primary transition-colors">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Annuler & Retour
                    </a>
                    <button type="submit" class="px-12 h-14 bg-primary text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-primary-dark transition shadow-xl shadow-blue-900/10 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-save"></i> ENREGISTRER L'AGENT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
