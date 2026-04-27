@extends('agent.layouts.app')

@section('title', 'Détails du message')
@section('page-title', 'Lecture du Message')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <a href="{{ route('agent.support.index') }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-primary transition">
        <i class="fa-solid fa-arrow-left"></i> Retour à la liste
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Message Panel --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary text-white rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20">
                            <i class="fa-solid fa-envelope-open-text text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-primary italic uppercase tracking-tighter">{{ $supportRequest->subject }}</h2>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Reçu le {{ $supportRequest->created_at ? \Carbon\Carbon::parse($supportRequest->created_at)->locale('fr')->translatedFormat('d F Y à H:i') : 'Date inconnue' }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-10">
                    <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100">
                        <p class="text-gray-600 font-black leading-relaxed italic text-lg">
                            "{{ $supportRequest->message }}"
                        </p>
                    </div>

                    @if($supportRequest->admin_response)
                        <div class="mt-10 pt-10 border-t border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-secondary/10 text-secondary rounded-lg flex items-center justify-center font-black">
                                    <i class="fa-solid fa-reply"></i>
                                </div>
                                <span class="text-xs font-black text-secondary uppercase tracking-widest">Votre réponse (le {{ $supportRequest->responded_at ? \Carbon\Carbon::parse($supportRequest->responded_at)->locale('fr')->translatedFormat('d F Y') : 'Date inconnue' }})</span>
                            </div>
                            <div class="bg-secondary/5 rounded-3xl p-8 border border-secondary/10">
                                <p class="text-secondary font-black italic">
                                    {{ $supportRequest->admin_response }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if(!$supportRequest->admin_response)
                {{-- Formulaire de réponse --}}
                <div class="bg-[#02245b] rounded-[2.5rem] shadow-2xl shadow-blue-900/20 p-10 text-white">
                    <h3 class="text-xl font-black italic uppercase tracking-tighter mb-8 flex items-center gap-3">
                        <i class="fa-solid fa-paper-plane text-secondary"></i>
                        Envoyer une réponse
                    </h3>

                    <form action="{{ route('agent.support.respond', $supportRequest->id) }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="text-[10px] font-black text-white/30 uppercase tracking-[2px] ml-1 block mb-3">Votre message au locataire</label>
                            <textarea name="admin_response" required rows="5"
                                      placeholder="Expliquez la démarche ou donnez une réponse..."
                                      class="w-full bg-white/5 border-2 border-white/5 focus:border-secondary rounded-2xl p-6 outline-none transition-all font-black text-sm text-white shadow-inner"></textarea>
                        </div>

                        <div class="flex items-end justify-end">
                            <button type="submit" class="px-12 h-14 bg-secondary text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-white hover:text-primary transition shadow-xl shadow-secondary/20 flex items-center justify-center gap-3">
                                <i class="fa-solid fa-check-circle"></i> Confirmer & Clôturer
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-green-600 rounded-[2.5rem] p-10 text-white flex items-center gap-6 shadow-xl shadow-green-500/20">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black italic uppercase tracking-tighter uppercase">Dossier Répondu & Clôturé</h3>
                        <p class="text-xs font-bold opacity-80 mt-1 uppercase tracking-widest">Aucune modification supplémentaire n'est possible.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar Info --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-xl shadow-gray-200/50">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 pb-4 border-b border-gray-50">Informations Locataire</h3>
                
                <div class="space-y-6">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-secondary uppercase tracking-tighter">Identité</span>
                        <span class="text-sm font-black text-primary uppercase italic">{{ $supportRequest->user->name }} {{ $supportRequest->user->prenoms }}</span>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-secondary uppercase tracking-tighter">Logement Occupé</span>
                        <span class="text-sm font-black text-primary italic uppercase">{{ $supportRequest->user->bien->reference ?? 'Logement Expert' }}</span>
                        <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ $supportRequest->user->bien->commune ?? 'Côte d\'Ivoire' }}</span>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-secondary uppercase tracking-tighter">Catégorie</span>
                        <span class="text-[10px] font-black text-gray-600 bg-gray-50 px-3 py-1 rounded-lg w-fit mt-1 uppercase tracking-widest italic">{{ $supportRequest->category }}</span>
                    </div>

                    <div class="pt-4 border-t border-gray-50">
                        <a href="{{ route('agent.locataires.index', ['search' => $supportRequest->user->contact]) }}" class="text-[10px] font-black text-primary uppercase hover:text-secondary transition flex items-center gap-2 tracking-widest">
                            <i class="fa-solid fa-user-circle text-xs"></i> Voir le profil complet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
