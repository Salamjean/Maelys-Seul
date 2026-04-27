@extends('comptable.layouts.app')

@section('title', 'Versements des Agents')
@section('page-title', 'Gestion des Versements')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    {{-- Formulaire de Versement --}}
    <div class="lg:col-span-4">
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden sticky top-28">
            {{-- Header Card --}}
            <div class="p-8 bg-gradient-to-br from-primary to-blue-900 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-xl font-black italic tracking-tight">Nouveau Versement</h2>
                    <p class="text-[10px] text-white/60 font-black uppercase tracking-[2px] mt-1">Remise de fonds agent</p>
                </div>
                {{-- Decorative circles --}}
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/5 rounded-full"></div>
                <div class="absolute -right-5 -bottom-5 w-20 h-20 bg-secondary/20 rounded-full blur-2xl"></div>
            </div>

            <form action="{{ route('comptable.versements.store') }}" method="POST" class="p-8" x-data="{ 
                agentId: '',
                agentName: '',
                totalCollected: 0,
                totalDeposited: 0,
                remaining: 0,
                loading: false,
                updateAgent(e) {
                    this.agentId = e.target.value;
                    this.agentName = e.target.options[e.target.selectedIndex].text;
                    this.fetchStats();
                },
                async fetchStats() {
                    if(!this.agentId) return;
                    this.loading = true;
                    try {
                        const res = await axios.get(`/admin/comptable/api/agent-stats/${this.agentId}`);
                        this.totalCollected = res.data.total_collected;
                        this.totalDeposited = res.data.total_deposited;
                        this.remaining = res.data.remaining;
                    } catch(e) { console.error(e); }
                    this.loading = false;
                }
            }">
                @csrf
                <div class="space-y-6">
                    {{-- Sélection de l'Agent --}}
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Agent de Recouvrement</label>
                        <div class="relative group">
                            <select name="agent_id" @change="updateAgent($event)" required
                                    class="w-full bg-gray-50 border-2 border-gray-100 focus:border-secondary h-16 px-6 rounded-2xl outline-none transition-all font-black text-sm appearance-none group-hover:bg-white text-primary">
                                <option value="">Choisir un agent...</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }} {{ $agent->prenoms }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-chevron-down text-[10px]"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Zone d'affichage du solde (Ultra Prominent) --}}
                    <div x-show="agentId" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="pt-2">
                        <div class="relative p-6 rounded-[2.5rem] bg-gray-50 border-2 border-dashed border-gray-200 flex flex-col items-center text-center overflow-hidden">
                            <div class="absolute inset-0 bg-white/40 backdrop-blur-sm" x-show="loading">
                                <div class="flex items-center justify-center h-full">
                                    <i class="fa-solid fa-circle-notch fa-spin text-secondary"></i>
                                </div>
                            </div>

                            {{-- Nom de l'agent sélectionné --}}
                            <div class="mb-4 px-4 py-1.5 bg-primary text-white rounded-full text-[10px] font-black uppercase tracking-[2px] shadow-lg shadow-primary/20" x-text="agentName"></div>
                            
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Solde à verser</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-primary tracking-tighter" x-text="new Intl.NumberFormat('fr-FR').format(remaining)">0</span>
                                <span class="text-xs font-black text-secondary italic">CFA</span>
                            </div>
                            
                            <div class="mt-4 flex gap-4 w-full">
                                <div class="flex-1 p-2 bg-white rounded-xl border border-gray-100 shadow-sm">
                                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">Encaissé</p>
                                    <p class="text-[10px] font-black text-gray-700" x-text="new Intl.NumberFormat('fr-FR').format(totalCollected)"></p>
                                </div>
                                <div class="flex-1 p-2 bg-white rounded-xl border border-gray-100 shadow-sm">
                                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">Déjà Versé</p>
                                    <p class="text-[10px] font-black text-secondary" x-text="new Intl.NumberFormat('fr-FR').format(totalDeposited)"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Champ Montant --}}
                    <div x-show="agentId" x-transition>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Montant à encaisser</label>
                        <div class="relative">
                            <input type="number" name="amount" :max="remaining" required
                                   class="w-full bg-white border-2 border-gray-100 focus:border-green-500 h-14 px-5 rounded-2xl outline-none transition-all font-black text-primary text-lg shadow-sm"
                                   placeholder="0">
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-300 uppercase italic">
                                FCFA
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div x-show="agentId" x-transition>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Notes</label>
                        <textarea name="notes" rows="2"
                                  class="w-full bg-white border-2 border-gray-100 focus:border-primary p-4 rounded-2xl outline-none transition-all font-bold text-xs"
                                  placeholder="Observations facultatives..."></textarea>
                    </div>

                    <button type="submit" x-show="agentId" x-transition
                            class="w-full h-14 bg-secondary text-white rounded-2xl font-black uppercase text-xs tracking-[2px] hover:bg-primary transition-all shadow-lg shadow-secondary/20 hover:shadow-primary/20 flex items-center justify-center gap-3 group">
                        <span>Valider le versement</span>
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    
                    <div x-show="!agentId" class="py-10 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-user-check text-gray-200 text-2xl"></i>
                        </div>
                        <p class="text-xs text-gray-400 font-bold italic px-4">Veuillez sélectionner un agent pour afficher son solde</p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Historique des Versements --}}
    <div class="lg:col-span-8">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/10">
                <div>
                    <h2 class="text-xl font-black text-primary italic">Historique des Versements</h2>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Suivi des remises de fonds par agent</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary/5 flex items-center justify-center text-primary">
                    <i class="fa-solid fa-vault text-xl"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                            <th class="px-8 py-6">Référence</th>
                            <th class="px-8 py-6">Agent</th>
                            <th class="px-8 py-6 text-center">Montant Versé</th>
                            <th class="px-8 py-6 text-center">Date & Heure</th>
                            <th class="px-8 py-6">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($versements as $v)
                        <tr class="hover:bg-gray-50/30 transition-all group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    <span class="text-xs font-black text-primary tracking-tighter">{{ $v->reference }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary font-black text-xs shadow-sm">
                                        {{ substr($v->agent->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-black text-primary uppercase tracking-tight">{{ $v->agent->name }} {{ $v->agent->prenoms }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-50 text-green-600 rounded-xl">
                                    <span class="text-sm font-black">{{ number_format($v->amount, 0, ',', ' ') }}</span>
                                    <span class="text-[8px] font-black uppercase opacity-60">CFA</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col text-[11px] font-bold text-gray-500 uppercase italic">
                                    <span class="text-primary/60">{{ $v->created_at->locale('fr')->translatedFormat('d F Y') }}</span>
                                    <span class="text-[10px] text-gray-300 font-medium not-italic">{{ $v->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-[10px] font-bold text-gray-400 italic line-clamp-1 max-w-[150px]">{{ $v->notes ?? '-' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-gray-100">
                                    <i class="fa-solid fa-box-open text-gray-200 text-3xl"></i>
                                </div>
                                <p class="text-sm font-black text-gray-400 italic">Aucun versement enregistré pour le moment.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($versements->hasPages())
                <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/20">
                    {{ $versements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
