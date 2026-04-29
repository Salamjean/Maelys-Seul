@extends('recouvrement.layouts.app')

@section('title', 'États des Lieux')
@section('page-title', 'Mes États des Lieux')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/20">
        <div>
            <h2 class="text-2xl font-black text-primary italic">États des Lieux Assignés</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Gérez vos missions de contrôle</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                    <th class="px-8 py-5">Locataire</th>
                    <th class="px-8 py-5">Bien</th>
                    <th class="px-8 py-5 text-center">Type</th>
                    <th class="px-8 py-5 text-center">Statut</th>
                    <th class="px-8 py-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($etatsLieux as $el)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-8 py-6">
                            <p class="text-sm font-black text-gray-800">{{ $el->user->name }} {{ $el->user->prenoms }}</p>
                            <p class="text-[11px] text-gray-400 font-bold">{{ $el->user->contact }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-sm font-black text-primary">{{ $el->bien->reference }}</p>
                            <p class="text-[11px] text-gray-400 font-bold italic">{{ $el->bien->commune }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($el->type == 'entree')
                                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase">Entrée</span>
                            @else
                                <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-black uppercase">Sortie</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($el->statut == 'en_attente')
                                <span class="px-3 py-1 bg-orange-50 text-orange-600 rounded-full text-[10px] font-black uppercase">En attente OTP</span>
                            @elseif($el->statut == 'otp_verifie')
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase">En cours de saisie</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] font-black uppercase">Terminé</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($el->statut == 'en_attente')
                                <button onclick="triggerOtp('{{ route('recouvrement.etat_lieux.generate_otp', $el->id) }}', '{{ route('recouvrement.etat_lieux.verify_otp', $el->id) }}', '{{ addslashes($el->user->name) }}')" class="px-4 py-2 bg-secondary text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md hover:bg-orange-600 transition">
                                    Démarrer
                                </button>
                            @elseif($el->statut == 'otp_verifie')
                                <a href="{{ route('recouvrement.etat_lieux.form', $el->id) }}" class="px-4 py-2 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md hover:bg-blue-900 transition">
                                    Continuer
                                </a>
                            @else
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Le {{ $el->date_etat_lieux->format('d/m/Y') }}</span>
                                    <a href="{{ route('recouvrement.etat_lieux.pdf', $el->id) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-file-pdf"></i> PDF
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-gray-400 font-bold italic text-sm">
                            Aucun état des lieux assigné.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($etatsLieux->hasPages())
        <div class="p-8 border-t border-gray-50 bg-gray-50/30">
            {{ $etatsLieux->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    async function triggerOtp(generateUrl, verifyUrl, userName) {
        // 1. Envoyer OTP
        Swal.fire({ title: 'Génération OTP...', didOpen: () => Swal.showLoading() });
        try {
            await axios.post(generateUrl);
        } catch (e) {
            let errorMsg = 'Impossible d\'envoyer le SMS OTP.';
            if (e.response && e.response.data && e.response.data.error) {
                errorMsg = e.response.data.error;
            }
            return Swal.fire('Erreur', errorMsg, 'error');
        }

        // 2. Demander OTP
        const { value: code } = await Swal.fire({
            title: '<span style="font-family:Outfit; font-weight:800;">Code de Sécurité</span>',
            text: `Un code a été envoyé à ${userName}. Veuillez le saisir :`,
            input: 'text',
            inputPlaceholder: 'Code à 4 chiffres',
            showCancelButton: true,
            confirmButtonText: 'Valider',
            confirmButtonColor: '#ff5e14',
            borderRadius: '2rem',
            customClass: { popup: 'rounded-[2.5rem]' },
            preConfirm: async (enteredCode) => {
                try {
                    const res = await axios.post(verifyUrl, { code: enteredCode });
                    return res.data;
                } catch (e) {
                    Swal.showValidationMessage('Code incorrect');
                }
            }
        });

        if (code && code.success) {
            window.location.href = code.redirect_url;
        }
    }
</script>
@endpush
