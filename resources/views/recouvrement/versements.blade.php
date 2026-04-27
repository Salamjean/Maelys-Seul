@extends('recouvrement.layouts.app')

@section('title', 'Mes Versements')
@section('page-title', 'Historique des versements')

@section('content')
<div class="space-y-8">
    {{-- Stats Cards --}}
    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:24px;">
        {{-- Total Collecté --}}
        <div style="background:white; border-radius:24px; padding:24px; border:1px solid #f3f4f6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02); display:flex; align-items:center; gap:20px;">
            <div style="width:56px; height:56px; border-radius:16px; background:#02245b0a; color:#02245b; display:flex; align-items:center; justify-content:center; font-size:20px;">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <p style="font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Collecté (Terrain)</p>
                <p style="font-size:18px; font-weight:900; color:#02245b; italic">{{ number_format($totalCollected, 0, ',', ' ') }} <span style="font-size:10px; font-weight:700;">FCFA</span></p>
            </div>
        </div>

        {{-- Total Versé --}}
        <div style="background:white; border-radius:24px; padding:24px; border:1px solid #f3f4f6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02); display:flex; align-items:center; gap:20px;">
            <div style="width:56px; height:56px; border-radius:16px; background:#22c55e0a; color:#22c55e; display:flex; align-items:center; justify-content:center; font-size:20px;">
                <i class="fa-solid fa-check-double"></i>
            </div>
            <div>
                <p style="font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Versé (Comptable)</p>
                <p style="font-size:18px; font-weight:900; color:#22c55e; italic">{{ number_format($totalVersed, 0, ',', ' ') }} <span style="font-size:10px; font-weight:700;">FCFA</span></p>
            </div>
        </div>

        {{-- Reste à Verser --}}
        <div style="background:{{ $remainingToVerse > 0 ? '#ff5e14' : '#02245b' }}; border-radius:24px; padding:24px; border:none; box-shadow:0 10px 25px {{ $remainingToVerse > 0 ? 'rgba(255, 94, 20, 0.2)' : 'rgba(2, 36, 91, 0.2)' }}; display:flex; align-items:center; gap:20px; color:white;">
            <div style="width:56px; height:56px; border-radius:16px; background:rgba(255,255,255,0.15); color:white; display:flex; align-items:center; justify-content:center; font-size:20px;">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div>
                <p style="font-size:11px; font-weight:800; color:rgba(255,255,255,0.6); text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Reste à verser</p>
                <p style="font-size:18px; font-weight:900; color:white; italic">{{ number_format($remainingToVerse, 0, ',', ' ') }} <span style="font-size:10px; font-weight:700;">FCFA</span></p>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div style="background:white; border-radius:24px; border:1px solid #f3f4f6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02); overflow:hidden;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb; border-bottom:1px solid #f3f4f6;">
                    <th style="padding:20px 24px; text-align:left; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Référence</th>
                    <th style="padding:20px 24px; text-align:center; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Date & Heure</th>
                    <th style="padding:20px 24px; text-align:center; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Montant</th>
                    <th style="padding:20px 24px; text-align:center; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Comptable</th>
                    <th style="padding:20px 24px; text-align:left; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Notes</th>
                </tr>
            </thead>
            <tbody style="divide-y divide-gray-50">
                @forelse($versements as $v)
                    <tr style="border-bottom:1px solid #f9fafb; transition:all 0.2s;" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:20px 24px;">
                            <div style="display:inline-flex; align-items:center; gap:8px; padding:6px 12px; background:#f3f4f6; color:#02245b; border-radius:10px; font-size:11px; font-weight:900; text-transform:uppercase;">
                                <i class="fa-solid fa-hashtag"></i>
                                {{ $v->reference }}
                            </div>
                        </td>
                        <td style="padding:20px 24px; text-align:center;">
                            <p style="font-size:14px; font-weight:800; color:#1f2937; margin-bottom:2px;">{{ $v->created_at->format('d/m/Y') }}</p>
                            <p style="font-size:11px; color:#9ca3af; font-weight:600;">{{ $v->created_at->format('H:i') }}</p>
                        </td>
                        <td style="padding:20px 24px; text-align:center;">
                            <span style="font-size:14px; font-weight:800; color:#ff5e14;">
                                {{ number_format($v->amount, 0, ',', ' ') }} FCFA
                            </span>
                        </td>
                        <td style="padding:20px 24px; text-align:center;">
                            <div style="display:flex; flex-direction:column; align-items:center;">
                                <p style="font-size:13px; font-weight:700; color:#374151;">{{ $v->comptable->name }}</p>
                                <p style="font-size:10px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Caisse Centrale</p>
                            </div>
                        </td>
                        <td style="padding:20px 24px;">
                            <p style="font-size:12px; color:#6b7280; font-style:italic; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $v->notes }}">
                                {{ $v->notes ?? '-' }}
                            </p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:60px 24px; text-align:center;">
                            <div style="width:60px; height:60px; border-radius:50%; background:#f9fafb; color:#d1d5db; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                                <i class="fa-solid fa-receipt" style="font-size:24px;"></i>
                            </div>
                            <p style="font-size:14px; font-weight:700; color:#374151;">Aucun versement</p>
                            <p style="font-size:12px; color:#9ca3af; font-weight:500;">Vos versements validés par le comptable s'afficheront ici.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
