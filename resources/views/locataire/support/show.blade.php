@extends('locataire.layouts.app')

@section('title', 'Détails Demande SAV')
@section('page-title', 'Détails de la demande')
@section('page-subtitle', 'Consultez le statut et les échanges de votre requête')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('locataire.support.index') }}" class="text-primary font-black text-xs uppercase flex items-center gap-2 hover:translate-x-[-5px] transition-transform">
            <i class="fa-solid fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-10 border-b border-gray-50 bg-gray-50/20 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black text-primary italic lowercase leading-none"><span class="uppercase">{{ substr($request->subject, 0, 1) }}</span>{{ substr($request->subject, 1) }}</h2>
                <div class="flex items-center gap-3 mt-3">
                    <span class="px-3 py-1 bg-primary/5 text-primary rounded-full text-[9px] font-black uppercase tracking-widest border border-primary/10">
                        {{ $request->category }}
                    </span>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        Réf: SAV-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                @php
                    $statusColors = [
                        'pending' => 'bg-gray-50 text-gray-400 border-gray-100',
                        'default' => 'bg-green-50 text-green-600 border-green-100',
                    ];
                    $statusLabel = [
                        'pending' => 'En attente',
                        'default' => 'Répondu',
                    ];
                    
                    $currentStatus = $request->status === 'pending' ? 'pending' : 'default';
                @endphp
                <span class="px-4 py-2 {{ $statusColors[$currentStatus] }} rounded-full text-[10px] font-black uppercase tracking-widest border block">
                    {{ $statusLabel[$currentStatus] }}
                </span>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-2 italic">Envoyé le {{ $request->created_at->locale('fr')->translatedFormat('d F Y à H:i') }}</p>
            </div>
        </div>

        <div class="p-10 space-y-8">
            <div class="bg-gray-50/50 rounded-[2rem] p-8 border border-gray-50">
                <p class="text-[10px] font-black text-secondary uppercase tracking-widest mb-4">Votre message :</p>
                <div class="text-gray-700 font-medium leading-relaxed italic">
                    "{!! nl2br(e($request->message)) !!}"
                </div>
            </div>

            @if($request->admin_response)
                <div class="bg-primary/5 rounded-[2rem] p-8 border border-primary/10 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5">
                        <i class="fa-solid fa-reply text-6xl text-primary"></i>
                    </div>
                    <div class="relative">
                        <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-user-tie"></i> Réponse de l'agence :
                        </p>
                        <div class="text-primary font-black leading-relaxed italic text-lg">
                            "{!! nl2br(e($request->admin_response)) !!}"
                        </div>
                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-4 italic">Répondu le {{ \Carbon\Carbon::parse($request->responded_at)->locale('fr')->translatedFormat('d F Y à H:i') }}</p>
                    </div>
                </div>
            @elseif($request->status === 'pending')
                <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-500 text-white rounded-xl flex items-center justify-center shrink-0 shadow-lg shadow-blue-500/20">
                        <i class="fa-solid fa-info-circle text-xl"></i>
                    </div>
                    <p class="text-sm font-bold text-blue-700">Votre demande a été bien reçue par nos services. Un technicien ou un conseiller prendra contact avec vous prochainement.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
