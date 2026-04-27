@extends('recouvrement.layouts.app')

@section('title', 'Locataires en retard')
@section('page-title', 'Locataires en retard')

@section('content')
<div class="space-y-8">
    {{-- Header Card --}}
    <div style="background:white; border-radius:24px; padding:32px; border:1px solid #f3f4f6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02); display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h2 style="font-size:20px; font-weight:800; color:#02245b; margin-bottom:4px; display:flex; align-items:center; gap:12px;">
                <i class="fa-solid fa-user-clock" style="color:#ff5e14;"></i>
                Suivi des Impayés
            </h2>
            <p style="font-size:13px; color:#6b7280; font-weight:500;">Liste des locataires n'ayant pas régularisé leur situation pour le mois en cours.</p>
        </div>
        <div style="background:#fef2f2; padding:12px 24px; border-radius:16px; border:1px solid #fee2e2;">
            <span style="color:#ef4444; font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:1px;">{{ count($lateTenants) }} Dossier(s) à traiter</span>
        </div>
    </div>

    {{-- Table Card --}}
    <div style="background:white; border-radius:24px; border:1px solid #f3f4f6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02); overflow:hidden;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb; border-bottom:1px solid #f3f4f6;">
                    <th style="padding:20px 24px; text-align:left; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Locataire</th>
                    <th style="padding:20px 24px; text-align:center; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Bien / Loyer</th>
                    <th style="padding:20px 24px; text-align:center; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Dernier Règlement</th>
                    <th style="padding:20px 24px; text-align:center; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Retard</th>
                    <th style="padding:20px 24px; text-align:center; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Action</th>
                </tr>
            </thead>
            <tbody style="divide-y divide-gray-50">
                @forelse($lateTenants as $tenant)
                    <tr style="border-bottom:1px solid #f9fafb; transition:all 0.2s;" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:20px 24px;">
                            <div style="display:flex; align-items:center; gap:14px;">
                                <div style="width:40px; height:40px; border-radius:12px; background:#02245b1a; color:#02245b; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px;">
                                    {{ substr($tenant->name, 0, 1) }}
                                </div>
                                <div>
                                    <p style="font-size:14px; font-weight:800; color:#1f2937; margin-bottom:2px; text-transform:uppercase; font-style:italic;">{{ $tenant->name }} {{ $tenant->prenoms }}</p>
                                    <p style="font-size:11px; color:#9ca3af; font-weight:600;">{{ $tenant->contact }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="padding:20px 24px; text-align:center;">
                            @if($tenant->bien)
                                <p style="font-size:13px; font-weight:800; color:#02245b; margin-bottom:2px;">{{ $tenant->bien->reference }}</p>
                                <p style="font-size:11px; color:#ff5e14; font-weight:700;">{{ number_format($tenant->bien->loyer_mensuel, 0, ',', ' ') }} FCFA</p>
                            @else
                                <span style="font-size:11px; color:#d1d5db; font-style:italic;">N/A</span>
                            @endif
                        </td>
                        <td style="padding:20px 24px; text-align:center;">
                            <p style="font-size:12px; font-weight:700; color:#4b5563;">
                                {{ \Carbon\Carbon::parse($tenant->next_payment_date)->subMonth()->translatedFormat('F Y') }}
                            </p>
                        </td>
                        <td style="padding:20px 24px; text-align:center;">
                            <div style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; background:#fef2f2; color:#ef4444; border-radius:10px; font-size:11px; font-weight:800; text-transform:uppercase;">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                {{ $tenant->months_late }} Mois
                            </div>
                        </td>
                        <td style="padding:20px 24px; text-align:center;">
                            <a href="{{ route('recouvrement.tenants.pay', $tenant->id) }}" style="display:inline-flex; align-items:center; gap:8px; background:#02245b; color:white; padding:10px 20px; border-radius:12px; font-size:11px; font-weight:800; text-transform:uppercase; text-decoration:none; transition:all 0.3s ease; box-shadow:0 4px 10px rgba(2, 36, 91, 0.15);" onmouseover="this.style.background='#ff5e14'; this.style.boxShadow='0 4px 15px rgba(255, 94, 20, 0.2)'" onmouseout="this.style.background='#02245b'; this.style.boxShadow='0 4px 10px rgba(2, 36, 91, 0.15)'">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                Encaisser
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:60px 24px; text-align:center;">
                            <div style="width:60px; height:60px; border-radius:50%; background:#f0fdf4; color:#22c55e; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                                <i class="fa-solid fa-check-double" style="font-size:24px;"></i>
                            </div>
                            <p style="font-size:14px; font-weight:700; color:#374151;">Félicitations !</p>
                            <p style="font-size:12px; color:#9ca3af; font-weight:500;">Tous les locataires sont à jour dans leurs paiements.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
