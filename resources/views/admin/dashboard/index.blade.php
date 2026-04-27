@extends('admin.layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')

    {{-- Welcome & Wallet --}}
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px; margin-bottom:28px;">
        {{-- Welcome Card --}}
        <div style="background:linear-gradient(135deg, #02245b, #0a3578); border-radius:24px; padding:32px; display:flex; align-items:center; justify-content:space-between; position:relative; overflow:hidden; border:1px solid rgba(255,255,255,0.1);">
            <div style="position:absolute; top:-20%; right:-10%; width:300px; height:300px; background:rgba(255,255,255,0.03); border-radius:50%; pointer-events:none;"></div>
            <div style="z-index:1;">
                <p style="color:rgba(255,255,255,0.65); font-size:14px; font-weight:600; margin-bottom:8px; text-transform:uppercase; letter-spacing:1px;">Bienvenue,</p>
                <h2 style="font-size:28px; font-weight:900; color:white; margin-bottom:8px; letter-spacing:-1px;">{{ $admin->name }} 👋</h2>
                <p style="color:rgba(255,255,255,0.5); font-size:14px; font-weight:500;">{{ now()->translatedFormat('l d F Y') }}</p>
            </div>
            <div style="background:rgba(255,255,255,0.08); backdrop-filter:blur(10px); border-radius:20px; padding:20px; text-align:center; border:1px solid rgba(255,255,255,0.1); z-index:1;">
                <img src="{{ asset('assets/images/maelys.jpg') }}" alt="Maelys-imo"
                    style="width:64px; height:64px; object-fit:cover; border-radius:16px; box-shadow:0 8px 24px rgba(0,0,0,0.3); display:block; margin:0 auto 10px; border:2px solid rgba(255,94,20,0.3);">
                <p style="color:white; font-size:10px; letter-spacing:2px; text-transform:uppercase; font-weight:800; opacity:0.8;">Administration</p>
            </div>
        </div>

        {{-- Wallet Card (Portefeuille) --}}
        <div style="background:white; border-radius:24px; padding:32px; border:1px solid #eee; display:flex; flex-direction:column; justify-content:center; position:relative; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.04);">
            <div style="position:absolute; top:0; right:0; padding:20px; opacity:0.05;">
                <i class="fa-solid fa-wallet" style="font-size:80px; color:#02245b;"></i>
            </div>
            <p style="font-size:12px; color:#9ca3af; font-weight:800; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:10px;">Portefeuille Global</p>
            <h3 style="font-size:32px; font-weight:950; color:#02245b; margin-bottom:5px; letter-spacing:-1px;">
                {{ number_format($total_encaissements, 0, ',', ' ') }} <span style="font-size:16px; color:#ff5e14; font-weight:800;">FCFA</span>
            </h3>
            <p style="font-size:11px; color:#16a34a; font-weight:700; display:flex; align-items:center; gap:5px;">
                <i class="fa-solid fa-arrow-trend-up"></i> Total des fonds encaissés
            </p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:28px;">
        @php
            $stats_data = [
                ['label' => 'Biens Immobiliers', 'value' => $count_biens, 'icon' => 'fa-house-chimney', 'color' => '#ff5e14', 'sub' => 'Annonces actives'],
                ['label' => 'Locataires Actifs', 'value' => $count_locataires, 'icon' => 'fa-users', 'color' => '#0891b2', 'sub' => 'Clients enregistrés'],
                ['label' => 'Messages SAV', 'value' => $count_messages, 'icon' => 'fa-headset', 'color' => '#7c3aed', 'sub' => 'Demandes reçues'],
            ];
        @endphp

        @foreach ($stats_data as $stat)
            <div style="background:white; border-radius:20px; padding:24px; border:1px solid #f3f4f6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.01); transition: transform 0.3s ease; cursor:pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:18px;">
                    <div style="width:44px; height:44px; border-radius:14px; display:flex; align-items:center; justify-content:center; background:{{ $stat['color'] }}1a;">
                        <i class="fa-solid {{ $stat['icon'] }}" style="color:{{ $stat['color'] }}; font-size:18px;"></i>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-size:11px; color:#9ca3af; font-weight:800; text-transform:uppercase; letter-spacing:1px;">{{ $stat['label'] }}</p>
                    </div>
                </div>
                <div style="display:flex; align-items:baseline; gap:8px;">
                    <p style="font-size:32px; font-weight:900; color:#02245b; letter-spacing:-1px;">{{ $stat['value'] }}</p>
                </div>
                <p style="font-size:11px; color:#6b7280; font-weight:500; margin-top:4px;">{{ $stat['sub'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Recent activity & Quick Links --}}
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px;">
        {{-- Recent Activity --}}
        <div style="background:white; border-radius:24px; padding:32px; border:1px solid #f3f4f6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
                <h3 style="font-size:18px; font-weight:800; color:#02245b; display:flex; align-items:center; gap:10px;">
                    <i class="fa-solid fa-receipt" style="color:#ff5e14;"></i>
                    Derniers Encaissements
                </h3>
                <a href="{{ route('admin.payments.history') }}" style="font-size:11px; font-weight:800; color:#ff5e14; text-transform:uppercase; text-decoration:none; letter-spacing:1px;">Voir tout</a>
            </div>
            
            <div style="display:flex; flex-direction:column; gap:12px;">
                @forelse($recent_payments as $pay)
                    <div style="display:flex; align-items:center; gap:16px; padding:16px; background:#f9fafb; border-radius:18px; border:1px solid #f3f4f6;">
                        <div style="width:44px; height:44px; border-radius:14px; background:white; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(0,0,0,0.03);">
                            <i class="fa-solid fa-money-bill-transfer" style="color:#16a34a; font-size:18px;"></i>
                        </div>
                        <div style="flex:1;">
                            <p style="font-size:14px; font-weight:800; color:#374151;">{{ $pay->user->name }} {{ $pay->user->prenoms }}</p>
                            <p style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">{{ $pay->periode_couverte }} • Ref: {{ $pay->reference }}</p>
                        </div>
                        <div style="text-align:right;">
                            <p style="font-size:16px; font-weight:900; color:#02245b;">+{{ number_format($pay->amount, 0, ',', ' ') }}</p>
                            <p style="font-size:10px; color:#16a34a; font-weight:800;">FCFA</p>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center; padding:40px;">
                        <i class="fa-solid fa-folder-open" style="font-size:40px; color:#eee; margin-bottom:15px;"></i>
                        <p style="color:#9ca3af; font-size:13px; font-weight:600;">Aucun encaissement récent.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions --}}
        <div style="background:white; border-radius:24px; padding:32px; border:1px solid #f3f4f6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02);">
            <h3 style="font-size:18px; font-weight:800; color:#02245b; margin-bottom:24px;">Raccourcis</h3>
            <div style="display:grid; gap:12px;">
                <a href="{{ route('admin.biens.create') }}" style="display:flex; align-items:center; gap:14px; padding:18px; background:#02245b; border-radius:18px; text-decoration:none; transition:0.3s; box-shadow:0 8px 20px rgba(2,36,91,0.15);" onmouseover="this.style.background='#0a3578'" onmouseout="this.style.background='#02245b'">
                    <div style="width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center;">
                        <i class="fa-solid fa-plus" style="color:white; font-size:14px;"></i>
                    </div>
                    <span style="color:white; font-size:13px; font-weight:700;">Publier un bien</span>
                </a>
                <a href="{{ route('admin.payments.pending') }}" style="display:flex; align-items:center; gap:14px; padding:18px; background:#f9fafb; border-radius:18px; text-decoration:none; border:1px solid #eee; transition:0.3s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#f9fafb'">
                    <div style="width:36px; height:36px; border-radius:10px; background:#ff5e141a; display:flex; align-items:center; justify-content:center;">
                        <i class="fa-solid fa-stamp" style="color:#ff5e14; font-size:14px;"></i>
                    </div>
                    <span style="color:#02245b; font-size:13px; font-weight:700;">Valider paiements</span>
                </a>
            </div>
        </div>
    </div>

@endsection
