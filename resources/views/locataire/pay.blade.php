@extends('locataire.layouts.app')

@section('title', 'Payer mon loyer')

@section('content')
    <div class="mx-auto" style="width:100%">
        <div class="mb-12 bg-white rounded-[40px] p-10 border border-gray-100 shadow-xl shadow-gray-200/50">
            <div class="flex items-center gap-5">
                <div class="w-1.5 h-10 bg-secondary rounded-full"></div>
                <h1 class="text-3xl font-black text-primary italic uppercase tracking-tighter">Payer mon <span
                        class="text-secondary">Loyer</span></h1>
            </div>
            <p class="mt-4 text-gray-400 font-medium text-sm">Veuillez sélectionner votre mode de paiement ci-dessous pour
                l'échéance de <span class="text-primary font-black uppercase italic">{{ $nextMonth }}</span>.</p>
        </div>

        @if($pendingPayment)
            <div class="mb-10 p-8 bg-orange-50 border border-orange-100 rounded-[40px] flex flex-col md:flex-row items-center justify-between gap-6 animate-fadeIn">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-white rounded-2xl text-orange-500 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-hourglass-half text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-orange-900 font-black italic uppercase text-lg">Paiement en attente</p>
                        <p class="text-orange-700/70 text-xs font-bold uppercase tracking-tight">Un règlement pour {{ $pendingPayment->periode_couverte }} est en cours de vérification.</p>
                    </div>
                </div>
                
                @if($pendingPayment->payment_method === 'mobile')
                <a href="{{ route('locataire.pay.retry', $pendingPayment->id) }}" class="bg-secondary text-white px-8 py-4 rounded-2xl font-black italic uppercase text-sm shadow-xl shadow-secondary/20 hover:scale-105 transition-all flex items-center gap-3">
                    <i class="fa-solid fa-credit-card"></i>
                    Finaliser le paiement Wave
                </a>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Colonne de Gauche : Formulaire -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-[#0a1931] border border-white/10 rounded-[40px] p-8 shadow-2xl shadow-black/50">
                    <form action="{{ route('locataire.pay.initiate') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="period_display" value="{{ $nextMonth }}">
                        <input type="hidden" name="months_count" id="months_count" value="1">

                        <!-- Choix de la Méthode -->
                        <div class="mb-12">
                            <label
                                class="text-secondary font-black italic text-xs uppercase mb-6 block tracking-widest">Étape
                                1 : Choisir le mode de paiement</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Mobile -->
                                <div onclick="selectMethod('mobile')" id="card-mobile"
                                    class="method-card relative overflow-hidden bg-white/5 border-2 border-white/5 p-7 rounded-3xl cursor-pointer transition-all hover:bg-white/10 active-method">
                                    <div class="flex items-center gap-5 relative z-10">
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-secondary text-white flex items-center justify-center shadow-lg shadow-secondary/20">
                                            <i class="fa-solid fa-mobile-screen text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-white font-black italic text-xl uppercase leading-tight">Mobile
                                                Money</h3>
                                            <p class="text-white/40 text-[10px] font-bold uppercase tracking-widest mt-1">
                                                Validation Instantanée</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bank -->
                                <div onclick="selectMethod('bank')" id="card-bank"
                                    class="method-card relative overflow-hidden bg-white/5 border-2 border-white/5 p-7 rounded-3xl cursor-pointer transition-all hover:bg-white/10">
                                    <div class="flex items-center gap-5 relative z-10">
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/20">
                                            <i class="fa-solid fa-building-columns text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-white font-black italic text-xl uppercase leading-tight">
                                                Virement Bancaire</h3>
                                            <p class="text-white/40 text-[10px] font-bold uppercase tracking-widest mt-1">
                                                Preuve de paiement</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="payment_method" id="input-method" value="mobile">
                        </div>

                        <!-- Options Mobile Money -->
                        <div id="section-mobile" class="space-y-6 animate-fadeIn">
                            <label
                                class="text-white/30 font-black italic text-xs uppercase mb-4 block text-center">Sélectionnez
                                votre réseau local</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                                @foreach(['orange', 'mtn', 'moov', 'wave'] as $prov)
                                    @php $isAvailable = ($prov === 'wave'); @endphp
                                    <label class="relative {{ $isAvailable ? 'cursor-pointer' : 'cursor-not-allowed' }} group">
                                        <input type="radio" name="provider" value="{{ $prov }}" class="hidden peer" 
                                               {{ $prov === 'wave' ? 'checked' : 'disabled' }}>
                                        
                                        <div class="bg-[#112240] border-2 border-white/5 p-5 rounded-2xl flex flex-col items-center gap-3 transition-all 
                                                    {{ $isAvailable ? 'peer-checked:bg-white/10 peer-checked:border-secondary peer-checked:scale-105 shadow-lg' : 'opacity-40 grayscale pointer-events-none' }}">
                                            
                                            <div class="w-16 h-16 rounded-xl bg-white p-2 flex items-center justify-center overflow-hidden shadow-inner relative">
                                                <img src="{{ asset('assets/images/' . $prov . '.png') }}" class="w-full object-contain">
                                                @if(!$isAvailable)
                                                    <div class="absolute inset-0 bg-white/60 flex items-center justify-center">
                                                        <i class="fa-solid fa-lock text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex flex-col items-center gap-1">
                                                <span class="text-white uppercase font-black italic text-[10px] tracking-tighter {{ $isAvailable ? 'opacity-60 peer-checked:opacity-100' : 'opacity-30' }}">
                                                    {{ strtoupper($prov) }}
                                                </span>
                                                @if(!$isAvailable)
                                                    <span class="text-[8px] bg-red-500/20 text-red-400 px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter">Bientôt</span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Section Virement Bancaire -->
                        <div id="section-bank" class="hidden animate-fadeIn space-y-6">
                            <div class="bg-blue-600/10 border-2 border-blue-600/30 rounded-[30px] p-8">
                                <div class="flex items-center gap-4 mb-6">
                                    <img src="{{ asset('assets/images/maelys.jpg') }}" class="h-6">
                                    <div class="h-4 w-px bg-white/10"></div>
                                    <span class="text-blue-400 font-black italic text-xs uppercase">Compte Officiel
                                        Maelys</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <span class="text-white/30 font-bold uppercase text-[9px] block mb-1">IBAN de
                                            règlement</span>
                                        <p class="text-white font-mono text-base tracking-widest break-all">CI93 0123 4567
                                            8901 2345 6789 01</p>
                                    </div>
                                    <div>
                                        <span
                                            class="text-white/30 font-bold uppercase text-[9px] block mb-1">Bénéficiaire</span>
                                        <p class="text-white font-black italic uppercase text-sm tracking-widest">MAELYS
                                            IMMOBILIER SARL</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="text-white/30 font-black italic text-xs uppercase mb-3 block">Transférez votre
                                    reçu de virement</label>
                                <div class="relative group">
                                    <div
                                        class="w-full bg-[#112240] border-2 border-dashed border-white/10 rounded-[30px] p-10 text-center transition-all group-hover:border-blue-600/50">
                                        <i
                                            class="fa-solid fa-file-invoice-dollar text-4xl text-white/10 group-hover:text-blue-600 transition-colors mb-4"></i>
                                        <p class="text-white/40 text-sm italic font-bold uppercase tracking-tight">Cliquer
                                            pour choisir le fichier</p>
                                        <input type="file" name="payment_proof"
                                            class="absolute inset-0 opacity-0 cursor-pointer">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-secondary text-white font-black italic uppercase py-6 rounded-3xl mt-12 hover:bg-secondary/90 transition-all shadow-2xl shadow-secondary/30 flex items-center justify-center gap-4 text-lg">
                            <i class="fa-solid fa-lock text-sm"></i>
                            Confirmer le Paiement
                        </button>
                    </form>
                </div>
            </div>

            <!-- Colonne de Droite : Résumé -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[40px] p-8 shadow-2xl sticky top-8 border-t-[10px] border-secondary">
                    <h2 class="text-[#0a1931] font-black italic text-2xl uppercase mb-8 pb-4 border-b border-gray-100">
                        Détails <span class="text-secondary">Dûs</span></h2>

                    <div class="space-y-8">
                        <div class="flex flex-col gap-1">
                            <span class="text-gray-400 text-[10px] font-black uppercase italic tracking-widest">Période de
                                loyer</span>
                            <span class="text-[#0a1931] font-black italic uppercase text-lg">{{ $nextMonth }}</span>
                        </div>

                        <div class="flex flex-col gap-1">
                            <span class="text-gray-400 text-[10px] font-black uppercase italic tracking-widest">Logement
                                concerné</span>
                            <span
                                class="text-[#0a1931] font-black italic uppercase text-sm leading-tight text-secondary">{{ $user->bien->reference ?? 'Logement Expert' }}</span>
                        </div>

                        <div class="pt-8 border-t border-gray-100">
                            <span class="text-gray-400 text-[10px] font-black uppercase italic block mb-2">Montant Total
                                Net</span>
                            <div class="flex items-baseline gap-2">
                                <span id="summary_total"
                                    class="text-5xl font-black italic text-[#0a1931] tracking-tighter">{{ number_format($amount, 0, ',', ' ') }}</span>
                                <span class="text-secondary font-black italic text-2xl uppercase">CFA</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 p-5 bg-gray-50 rounded-3xl flex items-center gap-4 border border-gray-100">
                        <div
                            class="w-10 h-10 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-shield-halved text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[9px] text-gray-500 font-black underline uppercase italic">Transaction Sécurisée
                            </p>
                            <p class="text-[8px] text-gray-400 font-bold uppercase italic">Vérification SSL Active</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .method-card.active-method {
            background: #112240;
            border-color: #ff5e14;
            box-shadow: 0 0 30px rgba(255, 94, 20, 0.3);
        }

        .method-card:not(.active-method):hover {
            border-color: rgba(255, 255, 255, 0.2);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.4s ease-out forwards;
        }
    </style>

    <script>
        const unitPrice = parseFloat("{!! $amount !!}") || 0;

        function selectMethod(method) {
            document.querySelectorAll('.method-card').forEach(c => c.classList.remove('active-method'));
            const card = document.getElementById('card-' + method);
            if (card) card.classList.add('active-method');

            const input = document.getElementById('input-method');
            if (input) input.value = method;

            const mobileSection = document.getElementById('section-mobile');
            const bankSection = document.getElementById('section-bank');

            if (method === 'mobile') {
                if (mobileSection) mobileSection.classList.remove('hidden');
                if (bankSection) bankSection.classList.add('hidden');
            } else {
                if (bankSection) bankSection.classList.remove('hidden');
                if (mobileSection) mobileSection.classList.add('hidden');
            }
        }
    </script>
@endsection