@extends('comptable.layouts.app')

@section('title', 'Validations de Paiements')
@section('page-title', 'Paiements à Valider')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    {{-- Header --}}
    <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/20">
        <div>
            <h2 class="text-2xl font-black text-primary italic">Demandes en attente</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Vérifiez les justificatifs et validez les encaissements</p>
        </div>
        
        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            {{-- Barre de Recherche --}}
            <form action="{{ route('comptable.payments.pending') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-80 group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Locataire, référence..." 
                           class="w-full bg-white border-2 border-gray-100 focus:border-secondary h-12 pl-12 pr-10 rounded-2xl outline-none transition-all font-bold text-xs shadow-sm group-hover:shadow-md">
                    
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    @if(request('search'))
                        <a href="{{ route('comptable.payments.pending') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="h-12 px-6 bg-primary text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-secondary transition-all shadow-lg shadow-primary/20 hover:shadow-secondary/20 whitespace-nowrap">
                    Rechercher
                </button>
            </form>

            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-10 h-10 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">En attente</p>
                    <p class="text-lg font-black text-primary">{{ $pendingPayments->total() }} demandes</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des paiements --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <th class="px-8 py-6 text-center">Réf & Locataire</th>
                    <th class="px-8 py-6 text-center">Méthode</th>
                    <th class="px-8 py-6 text-center">Période</th>
                    <th class="px-8 py-6 text-center">Montant</th>
                    <th class="px-8 py-6 text-center">Justificatif</th>
                    <th class="px-8 py-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pendingPayments as $payment)
                <tr class="hover:bg-gray-50/30 transition-all group">
                    <td class="px-8 py-6 text-center">
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-primary tracking-tight">{{ $payment->reference }}</span>
                            <span class="text-xs font-bold text-gray-500 uppercase">{{ $payment->user->name }} {{ $payment->user->prenoms }}</span>
                            <span class="text-[10px] text-secondary font-black uppercase tracking-tighter">{{ $payment->user->bien->reference ?? 'Logement' }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-center">
                        @if($payment->payment_method === 'bank')
                            <div class="inline-flex flex-col items-center gap-1">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center border border-blue-100">
                                    <i class="fa-solid fa-building-columns text-sm"></i>
                                </div>
                                <span class="text-[9px] font-black uppercase text-blue-400">Virement</span>
                            </div>
                        @else
                            <div class="inline-flex flex-col items-center gap-1">
                                <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center border border-orange-100">
                                    <img src="{{ asset('assets/images/' . ($payment->mobile_network ?? 'wave') . '.png') }}" class="w-6 h-6 object-contain">
                                </div>
                                <span class="text-[9px] font-black uppercase text-orange-400">{{ $payment->mobile_network ?? 'Wave' }}</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-center">
                        <span class="text-xs font-black text-gray-600 italic uppercase">{{ $payment->periode_couverte }}</span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <span class="text-lg font-black text-primary">{{ number_format($payment->amount, 0, ',', ' ') }}</span>
                        <span class="text-[10px] font-black text-secondary uppercase ml-0.5 italic">CFA</span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        @if($payment->payment_proof)
                            <a href="{{ Storage::url($payment->payment_proof) }}" target="_blank" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-100 hover:bg-primary hover:text-white transition-all shadow-sm">
                                <i class="fa-solid fa-file-image text-xs"></i> Voir Reçu
                            </a>
                        @else
                            <span class="text-[9px] text-gray-300 font-bold uppercase italic">Pas de reçu</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <form action="{{ route('comptable.payments.validate', $payment->id) }}" method="POST" id="form-validate-{{ $payment->id }}">
                                @csrf
                                <button type="button" onclick="confirmAction('validate', {{ $payment->id }}, '{{ $payment->user->name }} {{ $payment->user->prenoms }}', '{{ number_format($payment->amount, 0, ',', ' ') }}')" 
                                        class="w-10 h-10 bg-green-500 text-white rounded-xl hover:bg-green-600 transition-all shadow-lg shadow-green-500/20 flex items-center justify-center">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('comptable.payments.rejeter', $payment->id) }}" method="POST" id="form-cancel-{{ $payment->id }}">
                                @csrf
                                <button type="button" onclick="confirmAction('cancel', {{ $payment->id }}, '{{ $payment->user->name }} {{ $payment->user->prenoms }}', '{{ number_format($payment->amount, 0, ',', ' ') }}')"
                                        class="w-10 h-10 bg-red-50 text-red-400 rounded-xl hover:bg-red-500 hover:text-white transition-all border border-red-100 flex items-center justify-center">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-20 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-gray-200">
                            <i class="fa-solid fa-receipt text-gray-200 text-3xl"></i>
                        </div>
                        <p class="text-sm font-black text-gray-400 italic">Aucun paiement en attente pour le moment.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($pendingPayments->hasPages())
        <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100">
            {{ $pendingPayments->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function confirmAction(type, id, name, amount) {
    const isValidate = type === 'validate';
    
    Swal.fire({
        title: `<span style="font-family:Inter; font-weight:800;">${isValidate ? 'Confirmer l\'encaissement ?' : 'Rejeter le paiement ?'}</span>`,
        html: `
            <div class="text-center p-2">
                <p class="text-sm text-gray-500 mb-4">Voulez-vous ${isValidate ? 'valider' : 'annuler'} le paiement de <span class="font-black text-primary">${amount} CFA</span> pour <span class="font-black text-primary">${name}</span> ?</p>
                <div class="inline-block px-4 py-1.5 ${isValidate ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600'} rounded-full text-[10px] font-black uppercase tracking-widest">
                    ${isValidate ? 'Les fonds ont été reçus' : 'Le paiement sera annulé'}
                </div>
            </div>
        `,
        icon: isValidate ? 'question' : 'warning',
        showCancelButton: true,
        confirmButtonText: isValidate ? 'Oui, Valider' : 'Oui, Rejeter',
        cancelButtonText: 'Annuler',
        confirmButtonColor: isValidate ? '#10b981' : '#ef4444',
        cancelButtonColor: '#6b7280',
        borderRadius: '2rem',
        customClass: {
            popup: 'rounded-[2.5rem] p-8',
            confirmButton: 'rounded-xl px-6 py-3 font-bold uppercase text-[11px] tracking-widest',
            cancelButton: 'rounded-xl px-6 py-3 font-bold uppercase text-[11px] tracking-widest'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-${type}-${id}`).submit();
        }
    });
}
</script>
@endpush
@endsection
