@extends('admin.layouts.app')

@section('title', 'Versements des Agents')
@section('page-title', 'Gestion des Versements')

@section('content')
<div x-data="{ currentTab: '{{ $currentTab }}' }">
    {{-- Search / Filter Bar (Biens Style) --}}
    <div style="background:white; padding:24px; border-radius:18px; margin-bottom:32px; box-shadow:0 2px 12px rgba(0,0,0,0.04); border:1px solid #f3f4f6;">
        <form action="{{ route('admin.versements.index') }}" method="GET" style="display:grid; grid-template-columns: 1fr 1fr 150px; gap:20px; align-items:end;">
            {{-- On garde le tab actuel dans le formulaire pour ne pas le perdre lors du filtrage --}}
            <input type="hidden" name="tab" :value="currentTab">
            
            <div>
                <label style="display:block; font-size:11px; font-weight:800; color:#374151; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">Agent de recouvrement</label>
                <select name="agent_id" style="width:100%; padding:12px 16px; border:1px solid #e5e7eb; border-radius:10px; font-size:13px; outline:none; background:white; font-weight:600; color:#02245b;">
                    <option value="">Tous les agents</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                            {{ $agent->name }} {{ $agent->prenoms }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display:block; font-size:11px; font-weight:800; color:#374151; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">Mois de l'opération</label>
                <input type="month" name="month" value="{{ request('month') }}" 
                       style="width:100%; padding:11px 16px; border:1px solid #e5e7eb; border-radius:10px; font-size:13px; outline:none; font-weight:600; color:#02245b;">
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" style="flex:2; padding:12px; background:#02245b; color:white; border:none; border-radius:10px; font-size:13px; font-weight:800; text-transform:uppercase; cursor:pointer; transition:all 0.3s;" onmouseover="this.style.background='#ff5e14'">
                    <i class="fa-solid fa-filter"></i> Filtrer
                </button>
                @if(request('agent_id') || request('month'))
                    <a href="{{ route('admin.versements.index', ['tab' => $currentTab]) }}" style="flex:1; padding:12px; background:#fef2f2; color:#ef4444; border:1px solid #fee2e2; border-radius:10px; text-decoration:none; display:flex; align-items:center; justify-content:center; transition:all 0.3s;" onmouseover="this.style.background='#fee2e2'">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Stats Grid --}}
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:24px; margin-bottom:32px;">
        <div style="background:white; padding:24px; border-radius:18px; border:1px solid #f3f4f6; box-shadow:0 1px 4px rgba(0,0,0,0.03); display:flex; align-items:center; gap:20px;">
            <div style="width:54px; height:54px; background:#f0fdf4; color:#16a34a; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:20px;">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <p style="font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Total Encaissé</p>
                <p style="font-size:20px; font-weight:900; color:#02245b; letter-spacing:-0.5px;">{{ number_format($stats['total_collected'], 0, ',', ' ') }} <span style="font-size:10px; opacity:0.5;">CFA</span></p>
            </div>
        </div>

        <div style="background:white; padding:24px; border-radius:18px; border:1px solid #f3f4f6; box-shadow:0 1px 4px rgba(0,0,0,0.03); display:flex; align-items:center; gap:20px;">
            <div style="width:54px; height:54px; background:#eff6ff; color:#3b82f6; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:20px;">
                <i class="fa-solid fa-vault"></i>
            </div>
            <div>
                <p style="font-size:11px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Total Versé</p>
                <p style="font-size:20px; font-weight:900; color:#02245b; letter-spacing:-0.5px;">{{ number_format($stats['total_versed'], 0, ',', ' ') }} <span style="font-size:10px; opacity:0.5;">CFA</span></p>
            </div>
        </div>

        <div style="background:#02245b; padding:24px; border-radius:18px; display:flex; align-items:center; gap:20px; color:white; box-shadow:0 10px 20px rgba(2, 36, 91, 0.15);">
            <div style="width:54px; height:54px; background:rgba(255,255,255,0.1); border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:20px;">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div>
                <p style="font-size:11px; font-weight:800; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Solde Attendu</p>
                <p style="font-size:20px; font-weight:900; color:white; letter-spacing:-0.5px;">{{ number_format($stats['remaining'], 0, ',', ' ') }} <span style="font-size:10px; opacity:0.5;">CFA</span></p>
            </div>
        </div>
    </div>

    {{-- Main Table Card (Biens Style) --}}
    <div style="background:white; border-radius:18px; overflow:hidden; border:1px solid #f3f4f6; box-shadow:0 1px 4px rgba(0,0,0,0.06);">
        
        {{-- View: État des Caisses --}}
        <div x-show="currentTab === 'agents'" x-transition>
            <div style="padding:20px 24px; background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                <h3 style="font-size:15px; font-weight:800; color:#02245b; text-transform:uppercase; letter-spacing:0.5px;">État des Caisses par Agent</h3>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                    <thead>
                        <tr style="background:#fcfcfc; border-bottom:1px solid #f3f4f6;">
                            <th style="padding:16px 24px; text-align:left; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Agent</th>
                            <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Rôle</th>
                            <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Encaissé</th>
                            <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Versé</th>
                            <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Reste</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agents as $agent)
                            <tr style="border-bottom:1px solid #f9fafb; transition:all 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                                <td style="padding:16px 24px;">
                                    <div style="display:flex; align-items:center; gap:15px;">
                                        <div style="width:40px; height:40px; border-radius:10px; background:#02245b10; color:#02245b; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px;">
                                            {{ substr($agent->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p style="font-size:14px; font-weight:800; color:#1f2937; margin-bottom:2px; text-transform:uppercase; font-style:italic;">{{ $agent->name }} {{ $agent->prenoms }}</p>
                                            <p style="font-size:11px; color:#9ca3af; font-weight:600;">{{ $agent->contact }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:16px 24px; text-align:center;">
                                    <span style="display:inline-block; padding:4px 12px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; background:{{ $agent->role == 'recouvrement' ? '#fff7ed' : '#f0f9ff' }}; color:{{ $agent->role == 'recouvrement' ? '#ff5e14' : '#0ea5e9' }};">
                                        {{ $agent->role }}
                                    </span>
                                </td>
                                <td style="padding:16px 24px; text-align:center; font-weight:700; color:#374151;">
                                    {{ number_format($agent->total_collected, 0, ',', ' ') }}
                                </td>
                                <td style="padding:16px 24px; text-align:center; font-weight:700; color:#16a34a;">
                                    {{ number_format($agent->total_versed, 0, ',', ' ') }}
                                </td>
                                <td style="padding:16px 24px; text-align:center;">
                                    <span style="font-weight:900; color:{{ $agent->balance > 0 ? '#ff5e14' : '#02245b' }}; background:{{ $agent->balance > 0 ? '#fef2f2' : '#f0fdf4' }}; padding:6px 14px; border-radius:10px;">
                                        {{ number_format($agent->balance, 0, ',', ' ') }} <small>CFA</small>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- View: Historique Global --}}
        <div x-show="currentTab === 'history'" x-transition>
            <div style="padding:20px 24px; background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                <h3 style="font-size:15px; font-weight:800; color:#02245b; text-transform:uppercase; letter-spacing:0.5px;">Historique des Opérations</h3>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                    <thead>
                        <tr style="background:#fcfcfc; border-bottom:1px solid #f3f4f6;">
                            <th style="padding:16px 24px; text-align:left; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Référence</th>
                            <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Agent</th>
                            <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Montant</th>
                            <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Date</th>
                            <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Comptable</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($versements as $v)
                            <tr style="border-bottom:1px solid #f9fafb; transition:all 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                                <td style="padding:16px 24px;">
                                    <span style="font-family:monospace; font-weight:700; color:#ff5e14; background:#fff7ed; padding:4px 8px; border-radius:6px; font-size:12px;">
                                        {{ $v->reference }}
                                    </span>
                                </td>
                                <td style="padding:16px 24px; text-align:center;">
                                    <p style="font-weight:700; color:#02245b; text-transform:uppercase; font-size:12px;">{{ $v->agent->name }}</p>
                                </td>
                                <td style="padding:16px 24px; text-align:center; font-weight:900; color:#1f2937;">
                                    {{ number_format($v->amount, 0, ',', ' ') }} <small>CFA</small>
                                </td>
                                <td style="padding:16px 24px; text-align:center;">
                                    <p style="font-weight:700; color:#374151; margin-bottom:2px;">{{ $v->created_at->format('d/m/Y') }}</p>
                                    <p style="font-size:10px; color:#9ca3af; font-weight:600;">{{ $v->created_at->format('H:i') }}</p>
                                </td>
                                <td style="padding:16px 24px; text-align:center;">
                                    <span style="display:inline-block; padding:4px 12px; border-radius:20px; font-size:10px; font-weight:800; background:#f3f4f6; color:#4b5563; font-style:italic;">
                                        {{ $v->comptable->name }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding:60px 24px; text-align:center;">
                                    <p style="font-size:14px; font-weight:600; color:#9ca3af;">Aucun versement trouvé pour cette sélection.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($versements->hasPages())
                <div style="padding:16px 24px; border-top:1px solid #f3f4f6; background:#fcfcfc;">
                    {{ $versements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
