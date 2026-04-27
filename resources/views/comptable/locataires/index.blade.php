@extends('comptable.layouts.app')

@section('title', 'Liste des locataires')
@section('page-title', 'Gestion des Locataires')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/20">
        <div>
            <h2 class="text-2xl font-black text-primary italic">Locataires Actifs</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Consultez la liste des locataires et initiez les paiements</p>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            {{-- Barre de Recherche --}}
            <form action="{{ route('comptable.locataires.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-80 group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, contact, référence bien..." 
                           class="w-full bg-white border-2 border-gray-100 focus:border-secondary h-12 pl-12 pr-10 rounded-2xl outline-none transition-all font-bold text-xs shadow-sm group-hover:shadow-md">
                    
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    @if(request('search'))
                        <a href="{{ route('comptable.locataires.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="h-12 px-6 bg-primary text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-secondary transition-all shadow-lg shadow-primary/20 hover:shadow-secondary/20 whitespace-nowrap">
                    Rechercher
                </button>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                    <th class="px-8 py-5 text-center">Locataire</th>
                    <th class="px-8 py-5 text-center">Contact & Job</th>
                    <th class="px-8 py-5 text-center">Bien loué</th>
                    <th class="px-8 py-5 text-center">Documents</th>
                    <th class="px-8 py-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($locataires as $l)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center">
                                <div class="flex items-center gap-4 text-left">
                                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black shadow-sm">
                                        {{ substr($l->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-800">{{ $l->name }} {{ $l->prenoms }}</p>
                                        <p class="text-[11px] text-gray-400 font-bold">{{ $l->email ?? 'Sans email' }}</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <p class="text-sm font-black text-gray-700">{{ $l->contact }}</p>
                            <p class="text-[11px] text-secondary font-black uppercase tracking-wider mt-1">{{ $l->profession }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($l->bien)
                                <p class="text-sm font-black text-primary">{{ $l->bien->reference }}</p>
                                <p class="text-[11px] text-gray-400 font-bold italic">{{ $l->bien->commune }}</p>
                            @else
                                <span class="text-xs text-gray-300 italic">Non assigné</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center">
                                @php
                                    $docs = [
                                        ['label' => 'Pièce d\'identité', 'path' => $l->piece_identite, 'icon' => 'fa-id-card'],
                                        ['label' => 'Contrat de bail', 'path' => $l->contrat_bail, 'icon' => 'fa-file-contract'],
                                        ['label' => 'Attestation de travail', 'path' => $l->attestation_travail, 'icon' => 'fa-briefcase'],
                                        ['label' => 'Bulletin de salaire', 'path' => $l->bulletin_salaire, 'icon' => 'fa-file-invoice-dollar'],
                                        ['label' => 'Document Extra 1', 'path' => $l->doc_extra_1, 'icon' => 'fa-file-medical'],
                                        ['label' => 'Document Extra 2', 'path' => $l->doc_extra_2, 'icon' => 'fa-file-medical'],
                                        ['label' => 'Document Extra 3', 'path' => $l->doc_extra_3, 'icon' => 'fa-file-medical'],
                                    ];
                                    $availableDocs = array_filter($docs, fn($d) => !empty($d['path']));
                                @endphp
                                
                                <button onclick="showDocuments('{{ $l->name }} {{ $l->prenoms }}', {{ json_encode($availableDocs) }})" 
                                        class="px-4 py-2 bg-gray-50 text-primary border border-gray-100 rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-primary hover:text-white transition flex items-center gap-2">
                                    <i class="fa-solid fa-folder-open"></i>
                                    Docs ({{ count($availableDocs) }})
                                </button>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex justify-center gap-2">
                                <button type="button" 
                                        onclick="initiateCashPayment({{ $l->id }}, '{{ $l->name }}')"
                                        class="p-2 bg-green-50/50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition shadow-sm" 
                                        title="Initier Paiement">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </button>
                                <button type="button" 
                                        onclick="resumePaymentValidation()"
                                        class="p-2 bg-orange-50/50 text-orange-500 rounded-xl hover:bg-orange-500 hover:text-white transition shadow-sm" 
                                        title="Saisir Code OTP">
                                    <i class="fa-solid fa-key"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-users-slash text-gray-200 text-2xl"></i>
                            </div>
                            <p class="text-sm font-black text-gray-400 italic">Aucun locataire enregistré pour le moment.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($locataires->hasPages())
        <div class="p-8 border-t border-gray-50 bg-gray-50/30 font-bold">
            {{ $locataires->links() }}
        </div>
    @endif
</div>

{{-- Modal pour les documents --}}
<div id="documentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-primary/40 backdrop-blur-sm" onclick="closeModal()"></div>

        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-8 pt-8 pb-4 bg-white">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-black text-primary" id="modalTitle">Documents du locataire</h3>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Liste des pièces fournies</p>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-secondary transition-colors">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>

                <div id="documentsList" class="space-y-3 mb-6"></div>
            </div>
            <div class="px-8 py-6 bg-gray-50 flex justify-end">
                <button onclick="closeModal()" class="px-6 py-3 bg-white border-2 border-gray-100 text-primary rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 transition shadow-sm">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showDocuments(name, docs) {
    document.getElementById('modalTitle').innerText = "Documents de " + name;
    const list = document.getElementById('documentsList');
    list.innerHTML = '';

    if (docs.length === 0) {
        list.innerHTML = '<p class="text-sm text-gray-400 italic text-center py-4">Aucun document disponible.</p>';
    } else {
        docs.forEach(doc => {
            const div = document.createElement('div');
            div.className = "flex items-center justify-between p-4 bg-white border-2 border-gray-50 rounded-2xl hover:border-secondary/20 transition-all group";
            div.innerHTML = `
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-secondary/10 group-hover:text-secondary transition-colors">
                        <i class="fa-solid ${doc.icon}"></i>
                    </div>
                    <span class="text-xs font-black text-gray-700">${doc.label}</span>
                </div>
                <a href="/storage/${doc.path}" target="_blank" class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center hover:bg-secondary transition-colors shadow-sm shadow-primary/20">
                    <i class="fa-solid fa-eye"></i>
                </a>
            `;
            list.appendChild(div);
        });
    }

    document.getElementById('documentModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('documentModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

async function resumePaymentValidation() {
    const { value: code } = await Swal.fire({
        title: '<span style="font-family:Inter; font-weight:800;">Validation Rapide</span>',
        text: 'Saisissez directement le code OTP reçu par le locataire :',
        input: 'text',
        inputPlaceholder: 'Code à 4 chiffres',
        showCancelButton: true,
        confirmButtonText: 'Valider',
        confirmButtonColor: '#ff5e14',
        showLoaderOnConfirm: true,
        borderRadius: '2rem',
        customClass: { popup: 'rounded-[2.5rem]' },
        preConfirm: async (enteredCode) => {
            try {
                const response = await axios.post("{{ route('comptable.payments.confirm_direct') }}", { code: enteredCode });
                return response.data;
            } catch (e) {
                Swal.showValidationMessage(`Erreur: ${e.response.data.error || 'Code invalide'}`);
            }
        }
    });

    if (code) {
        await Swal.fire({
            title: '<span style="font-family:Inter; font-weight:800;">Paiement Validé !</span>',
            html: `
                <div class="py-4 text-center">
                    <p class="text-sm text-gray-600 mb-4 font-medium italic">Référence : <span class="font-black text-primary">${code.reference}</span></p>
                    <div class="flex justify-center mb-6">
                        <img src="${code.qr_url}" alt="QR Receipt" class="border-[6px] border-gray-50 rounded-3xl shadow-sm max-w-[200px]">
                    </div>
                    <div class="inline-block px-4 py-1.5 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                        Reçu enregistré avec succès
                    </div>
                </div>
            `,
            icon: 'success',
            confirmButtonText: 'Fermer',
            confirmButtonColor: '#02245b',
            borderRadius: '2.5rem',
            customClass: { popup: 'rounded-[3rem]' }
        });
    }
}

async function initiateCashPayment(locataireId, locataireName) {
    let currentPeriod = "Chargement...";
    try {
        const infoRes = await axios.get(`/admin/comptable/locataires/${locataireId}/next-payment-info?months=1`);
        currentPeriod = infoRes.data.periode;
    } catch (e) { currentPeriod = "Inconnue"; }

    const { value: months } = await Swal.fire({
        title: '<span style="font-family:Inter; font-weight:800;">Paiement Espèces</span>',
        html: `
            <div class="text-left mb-6">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Locataire : ${locataireName}</p>
                <div class="p-4 bg-primary/5 rounded-2xl border border-primary/10 flex items-center gap-4">
                    <div class="w-10 h-10 bg-primary text-white rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-primary/60 font-bold uppercase tracking-tighter">Période concernée</p>
                        <p id="swal-period-preview" class="text-sm font-black text-primary italic">${currentPeriod}</p>
                    </div>
                </div>
            </div>
            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Nombre de mois à encaisser :</label>
        `,
        input: 'number',
        inputAttributes: { min: 1, step: 1 },
        inputValue: 1,
        showCancelButton: true,
        confirmButtonText: 'Générer le Code',
        confirmButtonColor: '#10b981',
        borderRadius: '2.5rem',
        customClass: { popup: 'rounded-[3rem]' },
        didOpen: () => {
            const input = Swal.getInput();
            const preview = document.getElementById('swal-period-preview');
            input.addEventListener('input', async (e) => {
                const val = e.target.value || 1;
                preview.innerText = "...";
                try {
                    const res = await axios.get(`/admin/comptable/locataires/${locataireId}/next-payment-info?months=${val}`);
                    preview.innerText = res.data.periode;
                } catch (err) { preview.innerText = "Erreur"; }
            });
        }
    });

    if (!months) return;

    Swal.fire({ title: 'Génération du code...', didOpen: () => { Swal.showLoading(); } });

    try {
        const response = await axios.post(`/admin/comptable/locataires/${locataireId}/payments/initiate`, { months: months });
        const paymentId = response.data.payment_id;
        showCodeValidationPopup(paymentId, locataireName);
    } catch (err) {
        Swal.fire('Erreur', err.response.data.error || 'Une erreur est survenue', 'error');
    }
}

async function showCodeValidationPopup(paymentId, locataireName) {
    const { value: code } = await Swal.fire({
        title: '<span style="font-family:Inter; font-weight:800;">Code de Sécurité</span>',
        text: `Saisissez le code reçu par ${locataireName} :`,
        input: 'text',
        inputPlaceholder: 'Code à 4 chiffres',
        showCancelButton: true,
        confirmButtonText: 'Valider le Paiement',
        confirmButtonColor: '#ff5e14',
        showLoaderOnConfirm: true,
        borderRadius: '2rem',
        customClass: { popup: 'rounded-[2.5rem]' },
        preConfirm: async (enteredCode) => {
            try {
                const res = await axios.post(`/admin/comptable/payments/${paymentId}/confirm`, { code: enteredCode });
                return res.data;
            } catch (e) {
                Swal.showValidationMessage(`Erreur: ${e.response.data.error || 'Code invalide'}`);
            }
        }
    });

    if (code) {
        await Swal.fire({
            title: '<span style="font-family:Inter; font-weight:800;">Paiement Validé !</span>',
            html: `
                <div class="py-4 text-center">
                    <p class="text-sm text-gray-600 mb-4 font-medium italic">Référence : <span class="font-black text-primary">${code.reference}</span></p>
                    <div class="flex justify-center mb-6">
                        <img src="${code.qr_url}" alt="QR Receipt" class="border-[6px] border-gray-50 rounded-3xl shadow-sm max-w-[200px]">
                    </div>
                    <div class="inline-block px-4 py-1.5 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                        Reçu enregistré avec succès
                    </div>
                </div>
            `,
            icon: 'success',
            confirmButtonText: 'Fermer',
            confirmButtonColor: '#02245b',
            borderRadius: '2.5rem',
            customClass: { popup: 'rounded-[3rem]' }
        });
    }
}
</script>
@endsection
