@extends('recouvrement.layouts.app')

@section('title', 'Mes encaissements')
@section('page-title', 'Mes encaissements')

@section('content')
<div class="space-y-8">
    {{-- Summary Cards --}}
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:24px; margin-bottom:28px;">
        {{-- Total Collected Card --}}
        <div style="background:linear-gradient(135deg, #02245b, #0a3578); border-radius:24px; padding:32px; border:1px solid rgba(255,255,255,0.1); position:relative; overflow:hidden; box-shadow:0 10px 30px rgba(2, 36, 91, 0.2);">
            <div style="position:absolute; top:-20%; right:-10%; width:150px; height:150px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
            <div style="position:relative; z-index:1;">
                <div style="width:48px; height:48px; background:rgba(255,255,255,0.1); border-radius:14px; display:flex; align-items:center; justify-content:center; margin-bottom:20px; border:1px solid rgba(255,255,255,0.1);">
                    <i class="fa-solid fa-sack-dollar" style="color:white; font-size:20px;"></i>
                </div>
                <p style="font-size:11px; color:rgba(255,255,255,0.6); font-weight:800; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:8px;">Total de mes encaissements</p>
                <h3 style="font-size:32px; font-weight:950; color:white; letter-spacing:-1px; margin-bottom:4px;">
                    {{ number_format($totalCollected, 0, ',', ' ') }} <span style="font-size:16px; color:#ff5e14; font-weight:800;">FCFA</span>
                </h3>
                <p style="font-size:11px; color:#10b981; font-weight:700; display:flex; align-items:center; gap:5px;">
                    <i class="fa-solid fa-circle-check"></i> Fonds validés et sécurisés
                </p>
            </div>
        </div>

        {{-- Count Card --}}
        <div style="background:white; border-radius:24px; padding:32px; border:1px solid #f3f4f6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02); display:flex; align-items:center; gap:20px;">
            <div style="width:56px; height:56px; border-radius:16px; background:#f0f9ff; color:#0ea5e9; display:flex; align-items:center; justify-content:center; border:1px solid #e0f2fe;">
                <i class="fa-solid fa-file-invoice-dollar" style="font-size:24px;"></i>
            </div>
            <div>
                <p style="font-size:11px; color:#9ca3af; font-weight:800; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Nombre d'opérations</p>
                <h3 style="font-size:24px; font-weight:900; color:#02245b; letter-spacing:-1px;">{{ count($payments) }} Reçus</h3>
                <p style="font-size:11px; color:#64748b; font-weight:500;">Validés par code OTP</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div style="background:white; border-radius:24px; padding:24px 32px; border:1px solid #f3f4f6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02); margin-bottom:28px;">
        <form action="{{ route('recouvrement.my_payments') }}" method="GET" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px; align-items:end;">
            <div>
                <label style="font-size:10px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px; block;">Filtrer par Locataire</label>
                <select name="locataire_id" style="width:100%; height:48px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:0 16px; font-size:13px; font-weight:600; color:#02245b; outline:none; cursor:pointer;" onchange="this.form.submit()">
                    <option value="">Tous les locataires</option>
                    @foreach($locataires as $loc)
                        <option value="{{ $loc->id }}" {{ request('locataire_id') == $loc->id ? 'selected' : '' }}>
                            {{ strtoupper($loc->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="font-size:10px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px; block;">Rechercher un mois (Ex: Janvier)</label>
                <div style="position:relative;">
                    <input type="text" name="periode" value="{{ request('periode') }}" placeholder="Mois ou année..."
                           style="width:100%; height:48px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:0 45px 0 16px; font-size:13px; font-weight:600; color:#02245b; outline:none;">
                    <button type="submit" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#ff5e14; cursor:pointer;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" style="height:48px; flex:1; background:#02245b; color:white; border-radius:12px; font-size:12px; font-weight:800; text-transform:uppercase; border:none; cursor:pointer; transition:all 0.3s ease;">
                    Filtrer
                </button>
                @if(request()->anyFilled(['locataire_id', 'periode']))
                    <a href="{{ route('recouvrement.my_payments') }}" style="height:48px; width:48px; background:#f1f5f9; color:#64748b; border-radius:12px; display:flex; align-items:center; justify-content:center; text-decoration:none; transition:all 0.3s ease;">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- History Table Card --}}
    <div style="background:white; border-radius:24px; border:1px solid #f3f4f6; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02); overflow:hidden;">
        <div style="padding:24px 32px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:16px; font-weight:800; color:#02245b; display:flex; align-items:center; gap:10px;">
                <i class="fa-solid fa-receipt" style="color:#ff5e14;"></i>
                Journal de mes encaissements
            </h3>
        </div>

        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb; border-bottom:1px solid #f3f4f6;">
                    <th style="padding:20px 24px; text-align:left; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Réf / Date</th>
                    <th style="padding:20px 24px; text-align:left; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Locataire</th>
                    <th style="padding:20px 24px; text-align:center; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Période</th>
                    <th style="padding:20px 24px; text-align:center; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Montant</th>
                    <th style="padding:20px 24px; text-align:center; font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px;">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr style="border-bottom:1px solid #f9fafb; transition:all 0.2s;" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:20px 24px;">
                            <p style="font-size:13px; font-weight:800; color:#02245b; margin-bottom:4px; text-transform:uppercase; font-style:italic;">{{ $payment->reference }}</p>
                            <p style="font-size:11px; color:#9ca3af; font-weight:600;">{{ $payment->paid_at->format('d/m/Y H:i') }}</p>
                        </td>
                        <td style="padding:20px 24px;">
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:36px; height:36px; border-radius:10px; background:#f3f4f6; color:#02245b; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:12px;">
                                    {{ substr($payment->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p style="font-size:13px; font-weight:800; color:#374151; margin-bottom:2px; text-transform:uppercase; font-style:italic;">{{ $payment->user->name }}</p>
                                    <p style="font-size:10px; color:#9ca3af; font-weight:600;">{{ $payment->user->bien->reference ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="padding:20px 24px; text-align:center;">
                            <span style="display:inline-block; padding:6px 14px; background:#f0f9ff; color:#0ea5e9; border-radius:10px; font-size:10px; font-weight:800; text-transform:uppercase;">
                                {{ $payment->periode_couverte }}
                            </span>
                        </td>
                        <td style="padding:20px 24px; text-align:center;">
                            <p style="font-size:14px; font-weight:950; color:#02245b; letter-spacing:-0.5px;">{{ number_format($payment->amount, 0, ',', ' ') }} <span style="font-size:10px; color:#64748b; font-weight:700;">FCFA</span></p>
                        </td>
                        <td style="padding:20px 24px; text-align:center;">
                            <div style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:#f0fdf4; color:#16a34a; border-radius:100px; font-size:10px; font-weight:800; text-transform:uppercase;">
                                <i class="fa-solid fa-circle-check"></i>
                                Validé
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:60px 24px; text-align:center;">
                            <div style="width:60px; height:60px; border-radius:50%; background:#f8fafc; color:#cbd5e1; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; border:1px dashed #cbd5e1;">
                                <i class="fa-solid fa-receipt" style="font-size:24px;"></i>
                            </div>
                            <p style="font-size:14px; font-weight:700; color:#64748b;">Aucun encaissement</p>
                            <p style="font-size:12px; color:#9ca3af; font-weight:500;">Vous n'avez pas encore effectué d'encaissement.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
