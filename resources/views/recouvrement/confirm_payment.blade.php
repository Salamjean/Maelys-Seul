@extends('recouvrement.layouts.app')

@section('title', 'Confirmer le paiement')
@section('page-title', 'Validation du code')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="p-10 border-b border-gray-50 bg-gray-50/30 text-center">
            <div class="w-20 h-20 bg-secondary/10 rounded-3xl flex items-center justify-center text-secondary mx-auto mb-6 border border-secondary/5 shadow-sm animate-pulse">
                <i class="fa-solid fa-shield-halved text-3xl"></i>
            </div>
            <h2 class="text-2xl font-black text-primary italic uppercase tracking-tighter">Code de <span class="text-secondary">Vérification</span></h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Saisissez le code reçu par le locataire</p>
        </div>

        <div class="p-10 space-y-8">
            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Montant à valider</p>
                    <p class="text-lg font-black text-primary italic">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Période</p>
                    <p class="text-sm font-black text-secondary uppercase italic">{{ $payment->periode_couverte }}</p>
                </div>
            </div>

            <form action="{{ route('recouvrement.tenants.verify_payment', $payment->id) }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-primary uppercase tracking-[2px] ml-1 block mb-4 text-center">Code d'activation (4 chiffres)</label>
                    <input type="text" name="code" maxlength="4" required autofocus
                           class="w-full bg-gray-50 border-2 border-gray-100 focus:border-secondary focus:bg-white rounded-2xl h-20 text-center text-3xl font-black text-primary tracking-[15px] outline-none transition-all shadow-inner placeholder:text-gray-200"
                           placeholder="0000">
                    @error('code') <p class="text-red-500 text-[10px] font-bold mt-2 text-center uppercase">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full h-16 bg-primary text-white rounded-2xl font-black uppercase text-xs tracking-[2px] hover:bg-secondary transition-all shadow-2xl shadow-blue-900/20 hover:shadow-orange-500/20 flex items-center justify-center gap-4 group">
                    <i class="fa-solid fa-circle-check text-lg group-hover:scale-125 transition-transform"></i>
                    VALIDER LE PAIEMENT
                </button>
            </form>

            <p class="text-[10px] text-gray-400 font-bold italic text-center leading-relaxed">
                * Le code a été envoyé à l'adresse : <span class="text-primary">{{ $payment->user->email }}</span>
            </p>
        </div>
    </div>
</div>
@endsection
