<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') — ImmoSeul</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#02245b',
                        secondary: '#ff5e14',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-link {
            transition: all 0.2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: white !important;
        }

        .sidebar-link.active div {
            background-color: #ff5e14 !important;
            box-shadow: 0 4px 12px rgba(255, 94, 20, 0.3);
        }

        .sidebar-link.active div i {
            color: white !important;
        }

        .sidebar-sublink:hover,
        .sidebar-sublink.active {
            color: #ff5e14 !important;
            padding-left: 8px !important;
        }

        .sidebar-sublink {
            border-left: none !important;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>

</head>

<body class="bg-gray-100 min-h-screen flex">

    @include('admin.layouts.sidebar')

    {{-- Main --}}
    <div style="margin-left:280px; flex:1; min-height:100vh; display:flex; flex-direction:column;">

        @include('admin.layouts.navbar')

        {{-- Content --}}
        <main style="flex:1; padding: 40px 3%; transition: all 0.3s ease;">
            @yield('content')
        </main>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Succès !',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ff5e14',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl',
                        confirmButton: 'rounded-xl px-8 py-3 font-bold'
                    }
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Erreur',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'Réessayer',
                    confirmButtonColor: '#ef4444'
                });
            });
        </script>
    @endif

    @stack('scripts')

    {{-- Global Payment Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Init Axios
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        
        async function quickPaymentShortcut() {
            Swal.fire({
                title: 'Chargement des locataires...',
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await axios.get("{{ route('admin.api.locataires') }}");
                const locataires = response.data;

                if (locataires.length === 0) {
                    return Swal.fire('Info', 'Aucun locataire actif trouvé.', 'info');
                }

                // Step 1: Select Tenant
                const { value: locataireId } = await Swal.fire({
                    title: '<span style="font-family:Outfit; font-weight:800;">Encaissement Rapide</span>',
                    text: 'Sélectionnez le locataire pour le paiement :',
                    input: 'select',
                    inputOptions: locataires.reduce((acc, l) => {
                        acc[l.id] = (l.name + ' ' + (l.prenoms || '')).toUpperCase();
                        return acc;
                    }, {}),
                    inputPlaceholder: '-- Choisir un locataire --',
                    showCancelButton: true,
                    confirmButtonText: 'Suivant',
                    confirmButtonColor: '#10b981',
                    borderRadius: '2rem',
                    customClass: { popup: 'rounded-[2.5rem]' }
                });

                if (locataireId) {
                    const selected = locataires.find(l => l.id == locataireId);
                    initiateCashPayment(locataireId, selected.name + ' ' + (selected.prenoms || ''));
                }
            } catch (error) {
                Swal.fire('Erreur', 'Impossible de charger la liste des locataires.', 'error');
            }
        }

        async function resumePaymentValidation() {
            const { value: code } = await Swal.fire({
                title: '<span style="font-family:Outfit; font-weight:800;">Validation Rapide</span>',
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
                        const cleanUrl = "{{ route('admin.payments.confirm_direct') }}";
                        const response = await axios.post(cleanUrl, { code: enteredCode });
                        return response.data;
                    } catch (e) {
                        Swal.showValidationMessage(`Erreur: ${e.response.data.error || 'Code invalide'}`);
                    }
                }
            });

            if (code) {
                await Swal.fire({
                    title: '<span style="font-family:Outfit; font-weight:800;">Paiement Validé !</span>',
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


        function showDocuments(name, docs) {
            let docsHtml = docs.map(doc => `
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-primary/20 transition-all group mb-3">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm group-hover:bg-primary group-hover:text-white transition-all">
                            <i class="fa-solid ${doc.icon}"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1">${doc.label}</p>
                            <p class="text-xs font-black text-primary truncate max-w-[200px]">${doc.path.split('/').pop()}</p>
                        </div>
                    </div>
                    <a href="/storage/${doc.path}" target="_blank" 
                       class="px-4 py-2 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-secondary transition-all flex items-center gap-2">
                        <i class="fa-solid fa-eye"></i> Voir
                    </a>
                </div>
            `).join('');

            Swal.fire({
                title: `<span style="font-family:Outfit; font-weight:800;">Documents de ${name}</span>`,
                html: `
                    <div class="mt-6 max-h-[400px] overflow-y-auto px-2 custom-scrollbar">
                        ${docsHtml || '<p class="text-gray-400 italic py-8 text-sm">Aucun document disponible.</p>'}
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'Fermer',
                confirmButtonColor: '#02245b',
                borderRadius: '2.5rem',
                width: '500px',
                customClass: {
                    popup: 'rounded-[3rem]',
                    confirmButton: 'rounded-2xl px-8 py-3 uppercase text-[10px] font-black tracking-widest'
                }
            });
        }

        async function initiateCashPayment(locataireId, locataireName) {

            // 1. On récupère d'abord l'info du prochain mois
            let currentPeriod = "Chargement...";
            try {
                const infoRes = await axios.get(`/admin/locataires/${locataireId}/next-payment-info?months=1`);
                currentPeriod = infoRes.data.periode;
            } catch (e) { currentPeriod = "Inconnue"; }

            const { value: months } = await Swal.fire({
                title: '<span style="font-family:Outfit; font-weight:800;">Paiement Espèces</span>',
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
                            const res = await axios.get(`/admin/locataires/${locataireId}/next-payment-info?months=${val}`);
                            preview.innerText = res.data.periode;
                        } catch (err) { preview.innerText = "Erreur"; }
                    });
                }
            });

            if (!months) return;

            Swal.fire({ title: 'Génération du code...', didOpen: () => { Swal.showLoading(); } });

            try {
                const response = await axios.post(`/admin/locataires/${locataireId}/payments/initiate`, { months: months });
                const paymentId = response.data.payment_id;
                showCodeValidationPopup(paymentId, locataireName);
            } catch (err) {
                Swal.fire('Erreur', err.response.data.error || 'Une erreur est survenue', 'error');
            }
        }

        async function showCodeValidationPopup(paymentId, locataireName) {
            const { value: code } = await Swal.fire({
                title: '<span style="font-family:Outfit; font-weight:800;">Code de Sécurité</span>',
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
                        const res = await axios.post(`/admin/payments/${paymentId}/confirm`, { code: enteredCode });
                        return res.data;
                    } catch (e) {
                        Swal.showValidationMessage(`Erreur: ${e.response.data.error || 'Code invalide'}`);
                    }
                }
            });

            if (code) {
                await Swal.fire({
                    title: '<span style="font-family:Outfit; font-weight:800;">Paiement Validé !</span>',
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
    @stack('scripts')
</body>


</html>
