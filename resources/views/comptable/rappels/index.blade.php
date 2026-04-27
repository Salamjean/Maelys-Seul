@extends('comptable.layouts.app')

@section('title', 'Rappels de Paiement')
@section('page-title', 'Rappels & Retards')

@section('content')

    {{-- Stats Header (Similar to search bar height/margin) --}}
    <div style="background:white; padding:20px; border-radius:14px; margin-bottom:24px; box-shadow:0 1px 4px rgba(0,0,0,0.06); display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h2 style="font-size:18px; font-weight:800; color:#02245b; margin:0;">Relances de Loyer</h2>
            <p style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; margin-top:4px; letter-spacing:0.5px;">Liste des locataires en attente de paiement</p>
        </div>

        <div style="display:flex; gap:12px; align-items:center;">
            <div style="background:#fff7ed; color:#ff5e14; padding:8px 16px; border-radius:10px; font-size:12px; font-weight:800; border:1px solid #ffedd5; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                {{ count($lateTenants) }} RETARDS DÉTECTÉS
            </div>
            
            <button onclick="resumePaymentValidation()" style="padding:10px 20px; background:#02245b; color:white; border:none; border-radius:10px; font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:8px; transition:all 0.2s;" onmouseover="this.style.background='#011a43'">
                <i class="fa-solid fa-key"></i> VALIDER PAR CODE
            </button>
        </div>
    </div>

    {{-- Table Container --}}
    <div style="background:white; border-radius:14px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.06);">
        @if(count($lateTenants) == 0)
            <div style="padding:80px 20px; text-align:center;">
                <div style="width:80px; height:80px; background:#f0fdf4; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
                    <i class="fa-solid fa-check-double" style="font-size:32px; color:#16a34a;"></i>
                </div>
                <h3 style="font-size:18px; font-weight:800; color:#02245b; margin-bottom:8px;">Tout est à jour !</h3>
                <p style="font-size:14px; color:#6b7280;">Aucun retard de paiement n'a été détecté pour le moment.</p>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                    <thead>
                        <tr style="background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                            <th style="padding:16px; text-align:left; font-weight:700; color:#374151; text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Locataire</th>
                            <th style="padding:16px; text-align:center; font-weight:700; color:#374151; text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Bien & Commune</th>
                            <th style="padding:16px; text-align:center; font-weight:700; color:#374151; text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Date Échéance</th>
                            <th style="padding:16px; text-align:center; font-weight:700; color:#374151; text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Mois Dû</th>
                            <th style="padding:16px; text-align:center; font-weight:700; color:#374151; text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Statut Retard</th>
                            <th style="padding:16px; text-align:center; font-weight:700; color:#374151; text-transform:uppercase; font-size:11px; letter-spacing:0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($lateTenants as $l)
                            <tr style="border-bottom:1px solid #f3f4f6; transition:background 0.15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                                <td style="padding:16px;">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div style="width:40px; height:40px; background:rgba(2,36,91,0.08); color:#02245b; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:16px;">
                                            {{ substr($l->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p style="margin:0; font-weight:700; color:#374151; font-size:14px;">{{ $l->name }} {{ $l->prenoms }}</p>
                                            <p style="margin:2px 0 0; font-size:11px; color:#9ca3af; font-weight:600;">{{ $l->contact ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:16px; text-align:center;">
                                    @if($l->bien)
                                        <span style="display:inline-block; font-family:monospace; font-weight:700; color:#ff5e14; background:#fff7ed; padding:4px 8px; border-radius:6px; font-size:12px;">
                                            {{ $l->bien->reference }}
                                        </span>
                                        <p style="margin:4px 0 0; font-size:11px; color:#6b7280; font-weight:600;">{{ $l->bien->commune }}</p>
                                    @endif
                                </td>
                                <td style="padding:16px; text-align:center;">
                                    <div style="display:inline-flex; align-items:center; gap:6px; background:#f0f9ff; color:#0369a1; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; border:1px solid #e0f2fe;">
                                        <i class="fa-solid fa-calendar-day" style="font-size:10px;"></i>
                                        Le {{ $l->bien->date_paiement ?? 5 }} du mois
                                    </div>
                                </td>
                                <td style="padding:16px; text-align:center;">
                                    <span style="font-weight:700; color:#4b5563; font-style:italic;">{{ $l->next_period }}</span>
                                </td>
                                <td style="padding:16px; text-align:center;">
                                    <span style="display:inline-block; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:800; background:#fef2f2; color:#ef4444; border:1px solid #fee2e2;">
                                        {{ $l->months_late }} MOIS DE RETARD
                                    </span>
                                </td>
                                <td style="padding:16px; text-align:center;">
                                    <div style="display:flex; justify-content:center; gap:10px;">
                                        <button onclick="initiateCashPayment({{ $l->id }}, '{{ $l->name }} {{ $l->prenoms }}')" 
                                                title="Encaisser le loyer"
                                                style="width:36px; height:36px; display:flex; align-items:center; justify-content:center; border-radius:10px; background:#ecfdf5; color:#10b981; border:none; cursor:pointer; transition:all 0.2s;"
                                                onmouseover="this.style.background='#d1fae5'">
                                            <i class="fa-solid fa-money-bill-wave" style="font-size:14px;"></i>
                                        </button>

                                        <form action="{{ route('comptable.rappels.send', $l->id) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="button" onclick="confirmReminder(event, this)" 
                                                    title="Envoyer une relance (SMS/Email)"
                                                    style="width:36px; height:36px; display:flex; align-items:center; justify-content:center; border-radius:10px; background:#fff7ed; color:#ff5e14; border:none; cursor:pointer; transition:all 0.2s;"
                                                    onmouseover="this.style.background='#ffedd5'">
                                                <i class="fa-solid fa-paper-plane" style="font-size:14px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@push('scripts')
    <script>
        function confirmReminder(event, button) {
            event.preventDefault();
            const form = button.closest('form');
            
            Swal.fire({
                title: '<span style="font-family:Outfit; font-weight:800;">Envoyer un rappel ?</span>',
                text: "Un Email et un SMS seront envoyés au locataire pour lui notifier son retard de paiement.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, envoyer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#ff5e14',
                cancelButtonColor: '#9ca3af',
                borderRadius: '2rem',
                customClass: {
                    popup: 'rounded-[2.5rem]',
                    confirmButton: 'rounded-xl px-6 py-3 uppercase text-[10px] font-black tracking-widest',
                    cancelButton: 'rounded-xl px-6 py-3 uppercase text-[10px] font-black tracking-widest'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Envoi en cours...',
                        didOpen: () => { Swal.showLoading(); }
                    });
                    form.submit();
                }
            });
        }

        function resumePaymentValidation() {
            Swal.fire({
                title: 'Saisir le Code OTP',
                input: 'text',
                inputPlaceholder: 'Entrez le code à 4 chiffres...',
                showCancelButton: true,
                confirmButtonText: 'Valider',
                cancelButtonText: 'Fermer',
                confirmButtonColor: '#02245b',
                preConfirm: (code) => {
                    if (!code) {
                        Swal.showValidationMessage('Veuillez entrer le code');
                        return;
                    }
                    return axios.post("{{ route('comptable.payments.confirm_direct') }}", { code: code })
                        .then(response => {
                            if (response.data.success) {
                                return response.data;
                            }
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Erreur: ${error.response.data.error || 'Code invalide'}`);
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Paiement Validé !',
                        text: `Référence: ${result.value.reference}`,
                        confirmButtonColor: '#02245b'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        }

        function initiateCashPayment(locataireId, locataireName) {
            Swal.fire({
                title: `<span style="font-family:Outfit; font-weight:800;">Encaisser Loyer - ${locataireName}</span>`,
                html: `
                    <div style="text-align:left; padding:10px;">
                        <label style="display:block; font-size:12px; font-weight:700; color:#9ca3af; text-transform:uppercase; margin-bottom:8px;">Nombre de mois à payer</label>
                        <select id="swal-months" class="swal2-input" style="width:100%; margin:0; border-radius:12px; font-size:14px; font-weight:700;">
                            <option value="1">1 Mois</option>
                            <option value="2">2 Mois</option>
                            <option value="3">3 Mois</option>
                            <option value="6">6 Mois</option>
                            <option value="12">12 Mois (1 An)</option>
                        </select>
                        <div id="period-preview" style="margin-top:15px; padding:12px; background:#f8fafc; border-radius:12px; border:1px dashed #e2e8f0; text-align:center;">
                            <span style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase;">Période couverte :</span><br>
                            <span id="period-text" style="font-size:14px; color:#02245b; font-weight:800; font-style:italic;">Chargement...</span>
                        </div>
                    </div>
                `,
                didOpen: () => {
                    const monthsSelect = document.getElementById('swal-months');
                    const updatePeriod = () => {
                        axios.get(`/admin/comptable/locataires/${locataireId}/next-payment-info?months=${monthsSelect.value}`)
                            .then(res => {
                                document.getElementById('period-text').innerText = res.data.periode;
                            });
                    };
                    monthsSelect.addEventListener('change', updatePeriod);
                    updatePeriod();
                },
                showCancelButton: true,
                confirmButtonText: 'Générer le Code OTP',
                confirmButtonColor: '#02245b',
                cancelButtonText: 'Annuler',
                preConfirm: () => {
                    return { months: document.getElementById('swal-months').value }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Génération du code...', didOpen: () => { Swal.showLoading(); } });
                    axios.post(`/admin/comptable/locataires/${locataireId}/initiate-cash`, { months: result.value.months })
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Code envoyé !',
                                text: res.data.message,
                                confirmButtonColor: '#02245b'
                            }).then(() => {
                                resumePaymentValidation();
                            });
                        })
                        .catch(err => {
                            Swal.fire('Erreur', err.response.data.error || 'Une erreur est survenue', 'error');
                        });
                }
            });
        }
    </script>
@endpush

@endsection
