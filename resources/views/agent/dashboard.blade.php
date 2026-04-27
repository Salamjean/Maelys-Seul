@extends('agent.layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Bienvenue sur votre espace personnel, ' . $agent->name)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    {{-- Card Stat --}}
    <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group overflow-hidden relative">
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:rotate-12 transition-transform shadow-sm">
                <i class="fa-solid fa-coins text-2xl"></i>
            </div>
            <h3 class="text-4xl font-black text-primary tracking-tighter italic">{{ $stats['paiements_attente'] }}</h3>
            <p class="text-[11px] font-black text-gray-400 uppercase tracking-[3px] mt-2">Paiements à valider</p>
        </div>
    </div>

    {{-- Card Stat --}}
    <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group overflow-hidden relative">
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-secondary/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary mb-6 group-hover:rotate-12 transition-transform shadow-sm">
                <i class="fa-solid fa-headset text-2xl"></i>
            </div>
            <h3 class="text-4xl font-black text-secondary tracking-tighter italic">{{ $stats['sav_attente'] }}</h3>
            <p class="text-[11px] font-black text-gray-400 uppercase tracking-[3px] mt-2">SAV en attente</p>
        </div>
    </div>

    {{-- Card Stat --}}
    <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group overflow-hidden relative">
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 mb-6 group-hover:rotate-12 transition-transform shadow-sm">
                <i class="fa-solid fa-calendar-check text-2xl"></i>
            </div>
            <h3 class="text-4xl font-black text-green-600 tracking-tighter italic">{{ $stats['visites_jour'] }}</h3>
            <p class="text-[11px] font-black text-gray-400 uppercase tracking-[3px] mt-2">Visites aujourd'hui</p>
        </div>
    </div>
</div>

{{-- Espace Fichiers --}}
<div class="bg-white rounded-[3.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-10 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <div>
            <h2 class="text-2xl font-black text-primary italic lowercase leading-tight"><span class="uppercase">M</span>on espace fichiers</h2>
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-[3px] mt-1">Vos documents personnels et ressources d'agence</p>
        </div>
        <button class="bg-primary text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-secondary transition-all shadow-xl shadow-blue-900/20 flex items-center gap-3">
            <i class="fa-solid fa-cloud-arrow-up"></i> Téléverser un fichier
        </button>
    </div>

    <div class="p-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Dossier Resources --}}
            <div class="p-8 bg-gray-50/50 rounded-[2.5rem] border-2 border-transparent hover:border-primary/20 hover:bg-white transition-all cursor-pointer group text-center">
                <div class="w-20 h-20 bg-white rounded-3xl shadow-sm flex items-center justify-center text-primary mb-6 mx-auto group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-folder-tree text-4xl"></i>
                </div>
                <h4 class="text-sm font-black text-primary uppercase tracking-tighter italic">Ressources Agence</h4>
                <p class="text-[10px] text-gray-400 font-bold mt-2">3 fichiers partagés</p>
            </div>

            {{-- Fichier --}}
            <div class="p-8 bg-gray-50/50 rounded-[2.5rem] border-2 border-transparent hover:border-secondary/20 hover:bg-white transition-all cursor-pointer group text-center">
                <div class="w-20 h-20 bg-white rounded-3xl shadow-sm flex items-center justify-center text-secondary mb-6 mx-auto group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-invoice text-4xl"></i>
                </div>
                <h4 class="text-sm font-black text-primary uppercase tracking-tighter italic">Note_Service.pdf</h4>
                <p class="text-[10px] text-gray-400 font-bold mt-2">1.4 MB • 24 Avr 2026</p>
            </div>

            {{-- Fichier --}}
            <div class="p-8 bg-gray-50/50 rounded-[2.5rem] border-2 border-transparent hover:border-green-400/20 hover:bg-white transition-all cursor-pointer group text-center">
                <div class="w-20 h-20 bg-white rounded-3xl shadow-sm flex items-center justify-center text-green-500 mb-6 mx-auto group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-image text-4xl"></i>
                </div>
                <h4 class="text-sm font-black text-primary uppercase tracking-tighter italic">Capture_Bien.jpg</h4>
                <p class="text-[10px] text-gray-400 font-bold mt-2">3.2 MB • Personnel</p>
            </div>

            {{-- Upload Slot --}}
            <div class="p-8 bg-primary/5 rounded-[2.5rem] border-2 border-dashed border-primary/20 flex flex-col items-center justify-center text-center group hover:bg-primary hover:text-white transition-all cursor-pointer">
                <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center text-primary mb-4 shadow-md group-hover:rotate-180 transition-transform duration-700">
                    <i class="fa-solid fa-plus text-xl"></i>
                </div>
                <p class="text-xs font-black uppercase tracking-widest italic">Nouveau fichier</p>
            </div>
        </div>
    </div>
</div>
@endsection
