@extends('agent.layouts.app')

@section('title', 'Visites demandées')
@section('page-title', 'Demandes de visite en attente')

@section('content')
<div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <div>
            <h2 class="text-2xl font-black text-primary italic uppercase tracking-tighter">Nouvelles <span class="text-secondary">demandes</span></h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Gérez les demandes de visite entrantes</p>
        </div>
        <span class="px-6 py-2.5 bg-secondary text-white text-[10px] font-black rounded-xl uppercase tracking-widest shadow-lg shadow-orange-500/20 italic">
            {{ $visites->total() }} Demande(s)
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                    <th class="px-8 py-6 text-center">Visiteur</th>
                    <th class="px-8 py-6 text-center">Bien Concerné</th>
                    <th class="px-8 py-6 text-center">Date & Heure</th>
                    <th class="px-8 py-6 text-center">Statut</th>
                    <th class="px-8 py-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($visites as $v)
                    <tr class="hover:bg-blue-50/30 transition-all group">
                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black shrink-0 border border-primary/5">
                                    {{ substr($v->nom, 0, 1) }}
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-black text-gray-800 uppercase tracking-tighter italic">{{ $v->nom }} {{ $v->prenom }}</p>
                                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ $v->telephone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <a href="{{ route('agent.biens.show', $v->bien->id) }}" class="group inline-block">
                                <p class="text-sm font-black text-primary group-hover:text-secondary transition uppercase tracking-tighter italic">{{ $v->bien->reference }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase italic tracking-widest mt-0.5">{{ $v->bien->commune }}</p>
                            </a>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex flex-col gap-1">
                                <span class="text-[10px] font-black text-gray-700 bg-gray-100 px-3 py-1.5 rounded-xl uppercase tracking-tighter">
                                    <i class="fa-solid fa-calendar text-[10px] mr-1.5 text-secondary"></i>
                                    {{ \Carbon\Carbon::parse($v->date_visite)->translatedFormat('d F Y') }}
                                </span>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">
                                    <i class="fa-solid fa-clock text-[10px] mr-1.5"></i>
                                    {{ $v->heure_visite }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($v->statut == 'en_attente')
                                <span class="px-3 py-1 bg-orange-100 text-orange-600 text-[9px] font-black uppercase rounded-full tracking-widest">En attente</span>
                            @else
                                <span class="px-3 py-1 bg-green-100 text-green-600 text-[9px] font-black uppercase rounded-full tracking-widest italic">Planifiée</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center gap-3">
                                {{-- Bouton Confirmer / Reporter --}}
                                <button type="button" 
                                    onclick="confirmVisite({{ $v->id }}, '{{ $v->date_visite }}', '{{ $v->heure_visite }}', '{{ $v->statut }}')"
                                    class="w-10 h-10 {{ $v->statut == 'en_attente' ? 'bg-orange-500 shadow-orange-500/20' : 'bg-secondary shadow-orange-500/20' }} text-white rounded-xl hover:scale-110 transition-all shadow-lg flex items-center justify-center" 
                                    title="{{ $v->statut == 'en_attente' ? 'Confirmer le RDV' : 'Modifier le RDV' }}">
                                    <i class="fa-solid {{ $v->statut == 'en_attente' ? 'fa-calendar-check' : 'fa-calendar-day' }}"></i>
                                </button>

                                {{-- Bouton Marquer comme effectuée (Seulement si planifiée) --}}
                                @if($v->statut == 'confirmee')
                                    <form action="{{ route('agent.visites.terminer', $v) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 bg-green-600 text-white rounded-xl hover:scale-110 transition-all shadow-lg shadow-green-600/20 flex items-center justify-center" title="Marquer comme effectuée">
                                            <i class="fa-solid fa-check-double"></i>
                                        </button>
                                    </form>
                                @endif

                                <form id="form-confirmer-{{ $v->id }}" action="{{ route('agent.visites.confirmer', $v) }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="date_visite" id="input-date-{{ $v->id }}">
                                    <input type="hidden" name="heure_visite" id="input-heure-{{ $v->id }}">
                                    <input type="hidden" name="motif" id="input-motif-{{ $v->id }}">
                                </form>

                                <form action="{{ route('agent.visites.annuler', $v) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-10 h-10 bg-red-50 text-red-400 border border-red-100 rounded-xl hover:bg-red-500 hover:text-white transition-all flex items-center justify-center" title="Annuler">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-gray-200">
                                <i class="fa-solid fa-calendar-xmark text-gray-200 text-4xl"></i>
                            </div>
                            <p class="text-sm font-black text-gray-400 uppercase tracking-widest italic">Aucune demande de visite pour le moment.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($visites->hasPages())
        <div class="p-8 border-t border-gray-50 bg-gray-50/30">
            {{ $visites->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function confirmVisite(id, date, heure, statut) {
        const isPlanifiee = statut === 'confirmee';
        
        Swal.fire({
            title: `<span style="font-family:Outfit; font-weight:800;">${isPlanifiee ? 'Modifier le rendez-vous ?' : 'Confirmer la visite ?'}</span>`,
            text: `Le RDV est ${isPlanifiee ? 'actuellement' : 'prévu'} pour le ${date} à ${heure}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: isPlanifiee ? 'Garder tel quel' : 'Oui, confirmer tel quel',
            cancelButtonText: isPlanifiee ? 'Changer la date/heure' : 'Non, modifier le RDV',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ff5e14',
            reverseButtons: true,
            borderRadius: '2rem',
            customClass: {
                popup: 'rounded-[2.5rem] p-8',
                confirmButton: 'rounded-xl px-6 py-3 font-bold uppercase text-[11px] tracking-widest',
                cancelButton: 'rounded-xl px-6 py-3 font-bold uppercase text-[11px] tracking-widest'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if(!isPlanifiee) {
                    document.getElementById('form-confirmer-' + id).submit();
                }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Formulaire de modification
                Swal.fire({
                    title: '<span style="font-family:Outfit; font-weight:800;">Modifier le rendez-vous</span>',
                    html: `
                        <div class="text-left space-y-4 p-2">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nouvelle date</label>
                                <input type="date" id="swal-date" class="w-full h-12 bg-gray-50 border-2 border-gray-100 focus:border-secondary rounded-2xl px-4 outline-none font-bold text-sm transition-all" value="${date}">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nouvelle heure</label>
                                <input type="time" id="swal-heure" class="w-full h-12 bg-gray-50 border-2 border-gray-100 focus:border-secondary rounded-2xl px-4 outline-none font-bold text-sm transition-all" value="${heure}">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Motif ${isPlanifiee ? 'du changement' : 'du report'}</label>
                                <textarea id="swal-motif" class="w-full p-4 bg-gray-50 border-2 border-gray-100 focus:border-secondary rounded-2xl outline-none font-bold text-sm transition-all" placeholder="Ex: Agent indisponible, demande du client..."></textarea>
                            </div>
                        </div>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Mettre à jour & Envoyer SMS',
                    cancelButtonText: 'Annuler',
                    confirmButtonColor: '#ff5e14',
                    borderRadius: '2rem',
                    customClass: {
                        popup: 'rounded-[2.5rem] p-8',
                        confirmButton: 'rounded-xl px-6 py-3 font-bold uppercase text-[11px] tracking-widest',
                        cancelButton: 'rounded-xl px-6 py-3 font-bold uppercase text-[11px] tracking-widest'
                    },
                    preConfirm: () => {
                        const newDate = document.getElementById('swal-date').value;
                        const newHeure = document.getElementById('swal-heure').value;
                        const motif = document.getElementById('swal-motif').value;

                        if (!newDate || !newHeure || !motif) {
                            Swal.showValidationMessage('Veuillez remplir tous les champs, y compris le motif.');
                            return false;
                        }
                        return { newDate, newHeure, motif };
                    }
                }).then((modifyResult) => {
                    if (modifyResult.isConfirmed) {
                        document.getElementById('input-date-' + id).value = modifyResult.value.newDate;
                        document.getElementById('input-heure-' + id).value = modifyResult.value.newHeure;
                        document.getElementById('input-motif-' + id).value = modifyResult.value.motif;
                        document.getElementById('form-confirmer-' + id).submit();
                    }
                });
            }
        });
    }
</script>
@endsection
