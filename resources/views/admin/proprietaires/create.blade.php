@extends('admin.layouts.app')

@section('title', 'Ajouter un propriétaire')
@section('page-title', 'Nouveau Partenaire')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10 border-b border-gray-50 bg-gray-50/20">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-secondary text-white rounded-2xl flex items-center justify-center shadow-lg shadow-secondary/20">
                    <i class="fa-solid fa-user-plus text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-primary italic uppercase tracking-tighter">Fiche Propriétaire</h2>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Enregistrez un nouveau bailleur partenaire</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.proprietaires.store') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Nom --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1">Nom du propriétaire <span class="text-secondary">*</span></label>
                    <div class="relative">
                        <input type="text" name="nom" required value="{{ old('nom') }}"
                               class="w-full bg-gray-50 border-2 border-transparent focus:border-secondary h-14 pl-12 pr-4 rounded-2xl outline-none transition-all font-bold text-sm text-primary shadow-inner">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    </div>
                </div>

                {{-- Prénoms --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1">Prénoms du propriétaire <span class="text-secondary">*</span></label>
                    <div class="relative">
                        <input type="text" name="prenoms" required value="{{ old('prenoms') }}"
                               class="w-full bg-gray-50 border-2 border-transparent focus:border-secondary h-14 pl-12 pr-4 rounded-2xl outline-none transition-all font-bold text-sm text-primary shadow-inner">
                        <i class="fa-solid fa-user-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    </div>
                </div>

                {{-- Contact --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1">Numéro de contact <span class="text-secondary">*</span></label>
                    <div class="relative">
                        <input type="text" name="contact" required value="{{ old('contact') }}" placeholder="Ex: 07 00 00 00 00"
                               class="w-full bg-gray-50 border-2 border-transparent focus:border-secondary h-14 pl-12 pr-4 rounded-2xl outline-none transition-all font-bold text-sm text-primary shadow-inner">
                        <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    </div>
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1">Email <span class="text-gray-300 italic text-[8px]">(Optionnel)</span></label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Ex: propriétaire@exemple.com"
                               class="w-full bg-gray-50 border-2 border-transparent focus:border-secondary h-14 pl-12 pr-4 rounded-2xl outline-none transition-all font-bold text-sm text-primary shadow-inner">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    </div>
                </div>

                {{-- Lieu de résidence --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1">Lieu de résidence <span class="text-gray-300 italic text-[8px]">(Optionnel)</span></label>
                    <div class="relative">
                        <input type="text" name="lieu_residence" value="{{ old('lieu_residence') }}" placeholder="Ex: Cocody Angré"
                               class="w-full bg-gray-50 border-2 border-transparent focus:border-secondary h-14 pl-12 pr-4 rounded-2xl outline-none transition-all font-bold text-sm text-primary shadow-inner">
                        <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    </div>
                </div>

                {{-- Profession --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1">Profession <span class="text-gray-300 italic text-[8px]">(Optionnel)</span></label>
                    <div class="relative">
                        <input type="text" name="profession" value="{{ old('profession') }}" placeholder="Ex: Entrepreneur"
                               class="w-full bg-gray-50 border-2 border-transparent focus:border-secondary h-14 pl-12 pr-4 rounded-2xl outline-none transition-all font-bold text-sm text-primary shadow-inner">
                        <i class="fa-solid fa-briefcase absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    </div>
                </div>
            </div>

            {{-- Pièce d'identité --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1">Pièce d'identité (RECTO) <span class="text-secondary">*</span></label>
                    <div class="relative group">
                        <div class="w-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] p-8 text-center transition-all group-hover:border-secondary group-hover:bg-secondary/5">
                            <i class="fa-solid fa-address-card text-4xl text-gray-200 group-hover:text-secondary transition-colors mb-3"></i>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-tight">Cliquer pour le RECTO</p>
                            <input type="file" name="piece_identite_recto" required
                                   class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] ml-1">Pièce d'identité (VERSO) <span class="text-secondary">*</span></label>
                    <div class="relative group">
                        <div class="w-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] p-8 text-center transition-all group-hover:border-primary group-hover:bg-primary/5">
                            <i class="fa-solid fa-id-card text-4xl text-gray-200 group-hover:text-primary transition-colors mb-3"></i>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-tight">Cliquer pour le VERSO</p>
                            <input type="file" name="piece_identite_verso" required
                                   class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-4 pt-6">
                <a href="{{ route('admin.proprietaires.index') }}" class="px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-gray-200 transition">
                    Annuler
                </a>
                <button type="submit" class="px-10 py-4 bg-primary text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-secondary transition shadow-xl shadow-primary/20 hover:shadow-secondary/20 flex items-center gap-3">
                    <i class="fa-solid fa-check"></i> Enregistrer le propriétaire
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
