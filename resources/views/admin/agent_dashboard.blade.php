@extends('admin.layouts.app')

@section('title', 'Tableau de bord Agent')
@section('page-title', 'Espace Collaborateur')
@section('page-subtitle', 'Bienvenue sur votre espace de gestion, ' . $agent->name)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    {{-- Card Paiements --}}
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-5 group-hover:rotate-12 transition-transform">
                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
            </div>
            <h3 class="text-3xl font-black text-gray-800 tracking-tighter">{{ $stats['paiements_attente'] }}</h3>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Paiements à valider</p>
        </div>
    </div>

    {{-- Card SAV --}}
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-secondary/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary mb-5 group-hover:rotate-12 transition-transform">
                <i class="fa-solid fa-headset text-xl"></i>
            </div>
            <h3 class="text-3xl font-black text-gray-800 tracking-tighter">{{ $stats['sav_attente'] }}</h3>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Tickets SAV en attente</p>
        </div>
    </div>

    {{-- Card Visites --}}
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 mb-5 group-hover:rotate-12 transition-transform">
                <i class="fa-solid fa-calendar-check text-xl"></i>
            </div>
            <h3 class="text-3xl font-black text-gray-800 tracking-tighter">{{ $stats['visites_jour'] }}</h3>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Visites prévues aujourd'hui</p>
        </div>
    </div>
</div>

{{-- Section Mes Fichiers --}}
<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-xl font-black text-primary italic lowercase"><span class="uppercase">M</span>es documents & fichiers</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Gérez vos documents administratifs et outils de travail</p>
        </div>
        <button class="bg-gray-50 text-gray-400 p-3 rounded-xl hover:bg-primary hover:text-white transition shadow-sm border border-gray-100">
            <i class="fa-solid fa-upload mr-2"></i> Téléverser
        </button>
    </div>
    
    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Exemple de fichier --}}
            <div class="p-6 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200 flex flex-col items-center text-center group hover:bg-white hover:border-primary/30 transition-all cursor-pointer">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-primary mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-pdf text-3xl"></i>
                </div>
                <h4 class="text-xs font-black text-gray-800 uppercase tracking-tighter">Guide Agent.pdf</h4>
                <p class="text-[9px] text-gray-400 font-bold mt-1">2.4 MB • Manuel d'utilisation</p>
            </div>

            <div class="p-6 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200 flex flex-col items-center text-center group hover:bg-white hover:border-primary/30 transition-all cursor-pointer">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-red-500 mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-contract text-3xl"></i>
                </div>
                <h4 class="text-xs font-black text-gray-800 uppercase tracking-tighter">Contrat_Type.docx</h4>
                <p class="text-[9px] text-gray-400 font-bold mt-1">1.1 MB • Modèle de bail</p>
            </div>

            <div class="p-6 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200 flex flex-col items-center text-center group hover:bg-white hover:border-primary/30 transition-all cursor-pointer">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-blue-500 mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-image text-3xl"></i>
                </div>
                <h4 class="text-xs font-black text-gray-800 uppercase tracking-tighter">Logo_Agence.png</h4>
                <p class="text-[9px] text-gray-400 font-bold mt-1">800 KB • Identité visuelle</p>
            </div>

            {{-- Slot vide pour ajouter --}}
            <div class="p-6 bg-primary/5 rounded-3xl border-2 border-dashed border-primary/20 flex flex-col items-center justify-center text-center group hover:bg-primary hover:text-white transition-all cursor-pointer">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-primary mb-3 shadow-sm group-hover:rotate-90 transition-transform duration-500">
                    <i class="fa-solid fa-plus text-lg"></i>
                </div>
                <p class="text-[10px] font-black uppercase tracking-widest">Ajouter un fichier</p>
            </div>
        </div>
    </div>
</div>

@endsection
