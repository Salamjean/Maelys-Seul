@extends('recouvrement.layouts.app')

@section('title', 'Effectuer un paiement')
@section('page-title', 'Paiement de loyer')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('recouvrement.tenants.late') }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-primary transition mb-8">
        <i class="fa-solid fa-arrow-left"></i> Retour à la liste
    </a>

    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="p-10 border-b border-gray-50 bg-gray-50/30 text-center">
            <div class="w-20 h-20 bg-primary/10 rounded-3xl flex items-center justify-center text-primary mx-auto mb-6 border border-primary/5 shadow-sm">
                <i class="fa-solid fa-money-bill-transfer text-3xl"></i>
            </div>
            <h2 class="text-2xl font-black text-primary italic uppercase tracking-tighter">Validation <span class="text-secondary">Paiement</span></h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Enregistrement manuel du loyer</p>
        </div>

        <div class="p-10 space-y-10">
            {{-- Recapitulation --}}
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Locataire</p>
                    <p class="text-sm font-black text-primary uppercase italic tracking-tighter">{{ $locataire->name }} {{ $locataire->prenoms }}</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Période concernée</p>
                    <p class="text-sm font-black text-secondary uppercase italic tracking-tighter">{{ $periode }}</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Bien immobilier</p>
                    <p class="text-sm font-black text-primary uppercase italic tracking-tighter">{{ $locataire->bien->reference ?? 'N/A' }}</p>
                </div>
                <div class="bg-primary p-6 rounded-3xl shadow-lg shadow-blue-900/20">
                    <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-2">Montant à percevoir</p>
                    <p class="text-xl font-black text-white italic tracking-tighter">{{ number_format($locataire->bien->loyer_mensuel ?? 0, 0, ',', ' ') }} <span class="text-[10px] opacity-70">FCFA</span></p>
                </div>
            </div>

            <div class="bg-blue-50/50 p-6 rounded-3xl border border-blue-100 flex items-start gap-4">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <p class="text-xs font-bold text-gray-600 leading-relaxed italic">
                    En cliquant sur le bouton ci-dessous, vous confirmez avoir reçu le montant du loyer en espèces pour le mois de <strong class="text-primary">{{ $periode }}</strong>. Cette action est irréversible et générera un reçu définitif.
                </p>
            </div>

            <form action="{{ route('recouvrement.tenants.initiate_payment', $locataire->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full h-16 bg-primary text-white rounded-2xl font-black uppercase text-xs tracking-[2px] hover:bg-secondary transition-all shadow-2xl shadow-blue-900/20 hover:shadow-orange-500/20 flex items-center justify-center gap-4 group">
                    <i class="fa-solid fa-paper-plane text-lg group-hover:scale-125 transition-transform"></i>
                    INITIER LE PAIEMENT & ENVOYER LE CODE
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
