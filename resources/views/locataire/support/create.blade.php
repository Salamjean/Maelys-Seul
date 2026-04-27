@extends('locataire.layouts.app')

@section('title', 'Nouvelle Demande SAV')
@section('page-title', 'Signaler un problème')
@section('page-subtitle', 'Remplissez le formulaire ci-dessous pour contacter l\'agence')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-10 border-b border-gray-50 bg-gray-50/20">
            <h2 class="text-2xl font-black text-primary italic lowercase leading-none"><span class="uppercase">F</span>ormulaire de contact</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[2px] mt-2">Nous reviendrons vers vous dans les plus brefs délais</p>
        </div>

        <form action="{{ route('locataire.support.store') }}" method="POST" class="p-10 space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Sujet de la demande</label>
                    <input type="text" name="subject" required 
                           placeholder="Ex: Problème de plomberie, Climatisation..." 
                           class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary/20 transition-all outline-none">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Catégorie</label>
                    <select name="category" required 
                            class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary/20 transition-all outline-none appearance-none">
                        <option value="reparation">Réparation / Travaux</option>
                        <option value="reclamation">Réclamation</option>
                        <option value="question">Question administrative</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Niveau d'urgence</label>
                <div class="flex gap-4">
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="priority" value="normal" checked class="hidden peer">
                        <div class="p-4 rounded-2xl border-2 border-gray-100 text-center peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                            <span class="text-xs font-black text-gray-400 group-hover:text-gray-600 peer-checked:text-primary uppercase tracking-widest">Normal</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="priority" value="urgent" class="hidden peer">
                        <div class="p-4 rounded-2xl border-2 border-gray-100 text-center peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all">
                            <span class="text-xs font-black text-gray-400 group-hover:text-gray-600 peer-checked:text-orange-600 uppercase tracking-widest">Urgent</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="priority" value="critique" class="hidden peer">
                        <div class="p-4 rounded-2xl border-2 border-gray-100 text-center peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                            <span class="text-xs font-black text-gray-400 group-hover:text-gray-600 peer-checked:text-red-600 uppercase tracking-widest">Critique</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Votre message détaillé</label>
                <textarea name="message" required rows="6" 
                          placeholder="Décrivez précisément votre problème ici..." 
                          class="w-full bg-gray-50 border-none rounded-[2rem] px-8 py-6 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary/20 transition-all outline-none resize-none"></textarea>
            </div>

            <div class="pt-6 flex gap-4">
                <a href="{{ route('locataire.support.index') }}" class="flex-1 bg-gray-100 text-gray-500 py-5 rounded-[2rem] font-black uppercase text-xs hover:bg-gray-200 transition-all text-center">
                    ANNULER
                </a>
                <button type="submit" class="flex-[2] bg-primary text-white py-5 rounded-[2rem] font-black italic uppercase text-sm hover:bg-primary-dark transition-all shadow-xl shadow-primary/20 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> ENVOYER LA DEMANDE
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
