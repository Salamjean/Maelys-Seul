<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent - @yield('title', 'Dashboard') - MAELYS-IMO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; background: #f8fafc; }
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
            transition: all 0.2s;
        }
        [x-cloak] { display: none !important; }
    </style>
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
    @yield('styles')
</head>
<body class="flex bg-[#f8fafc]">

    @include('agent.layouts.sidebar')

    <main class="flex-1 min-h-screen relative ml-[280px]">
        @include('agent.layouts.navbar')

        <div class="p-8 mt-20">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl flex items-center justify-between shadow-sm animate-pulse">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check"></i>
                        <span class="text-sm font-bold uppercase tracking-widest">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            <div class="mb-10">
                <h1 class="text-3xl font-black text-primary tracking-tighter italic">@yield('page-title', 'Dashboard')</h1>
                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-[3px] mt-1 italic">@yield('page-subtitle', 'Espace collaborateur sécurisé')</p>
            </div>

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Init Axios
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        // Document Viewer Logic
        function showDocuments(name, docs) {
            let html = '<div class="space-y-3 text-left mt-4">';
            docs.forEach(d => {
                const url = `/storage/${d.path}`;
                html += `
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-primary/20 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm group-hover:bg-primary group-hover:text-white transition-all">
                                <i class="fa-solid ${d.icon}"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1">${d.label}</p>
                                <p class="text-[11px] font-bold text-gray-700">Fichier joint</p>
                            </div>
                        </div>
                        <a href="${url}" target="_blank" class="p-2 bg-white text-primary rounded-lg shadow-sm hover:bg-primary hover:text-white transition-all">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </a>
                    </div>
                `;
            });
            html += '</div>';

            Swal.fire({
                title: `<span class="font-black italic text-primary">Dossier : ${name}</span>`,
                html: html,
                showConfirmButton: false,
                showCloseButton: true,
                borderRadius: '2rem',
                customClass: { popup: 'rounded-[2.5rem]' }
            });
        }
        
        async function quickPaymentShortcut() {
            Swal.fire({
                title: 'Chargement des locataires...',
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await axios.get("{{ route('agent.api.locataires') }}");
                const locataires = response.data;

                if (locataires.length === 0) {
                    return Swal.fire('Info', 'Aucun locataire actif trouvé.', 'info');
                }

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
                        const cleanUrl = "{{ route('agent.payments.confirm_direct') }}";
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

        async function initiateCashPayment(locataireId, locataireName) {
            let currentPeriod = "Chargement...";
            try {
                const infoRes = await axios.get(`/admin/agent/locataires/${locataireId}/next-payment-info?months=1`);
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
                            const res = await axios.get(`/admin/agent/locataires/${locataireId}/next-payment-info?months=${val}`);
                            preview.innerText = res.data.periode;
                        } catch (err) { preview.innerText = "Erreur"; }
                    });
                }
            });

            if (!months) return;

            Swal.fire({ title: 'Génération du code...', didOpen: () => { Swal.showLoading(); } });

            try {
                const response = await axios.post(`/admin/agent/locataires/${locataireId}/payments/initiate`, { months: months });
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
                        const res = await axios.post(`/admin/agent/payments/${paymentId}/confirm`, { code: enteredCode });
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
    @yield('scripts')
    @stack('scripts')
</body>
</html>
