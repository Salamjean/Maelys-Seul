@extends('admin.layouts.app')

@section('title', 'Visites demandées')
@section('page-title', 'Demandes de visite en attente')

@section('content')
<div style="background:white; border-radius:18px; overflow:hidden; border:1px solid #f3f4f6; box-shadow:0 1px 4px rgba(0,0,0,0.06);">
    <div style="padding:20px 24px; background:#f9fafb; border-bottom:1px solid #e5e7eb; display:flex; justify-content:between; align-items:center;">
        <div>
            <h2 style="font-size:15px; font-weight:800; color:#02245b; text-transform:uppercase; letter-spacing:0.5px;">Nouvelles demandes</h2>
            <p style="font-size:11px; color:#9ca3af; font-weight:600; margin-top:2px;">Gérez les demandes de visite entrantes</p>
        </div>
        <span style="display:inline-block; padding:6px 14px; background:#02245b10; color:#02245b; font-size:11px; font-weight:900; border-radius:10px;">
            {{ $visites->total() }} DEMANDE(S)
        </span>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:#fcfcfc; border-bottom:1px solid #f3f4f6;">
                    <th style="padding:16px 24px; text-align:left; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Visiteur</th>
                    <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Bien Concerné</th>
                    <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Date & Heure</th>
                    <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Statut</th>
                    <th style="padding:16px 24px; text-align:center; font-weight:700; color:#6b7280; text-transform:uppercase; font-size:11px; letter-spacing:1px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visites as $v)
                    <tr style="border-bottom:1px solid #f9fafb; transition:all 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                        <td style="padding:16px 24px;">
                            <div style="display:flex; align-items:center; gap:15px;">
                                <div style="width:40px; height:40px; border-radius:10px; background:#02245b10; color:#02245b; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px;">
                                    {{ substr($v->nom, 0, 1) }}
                                </div>
                                <div>
                                    <p style="font-size:14px; font-weight:800; color:#1f2937; margin-bottom:2px; text-transform:uppercase; font-style:italic;">{{ $v->nom }} {{ $v->prenom }}</p>
                                    <p style="font-size:11px; color:#9ca3af; font-weight:600;">{{ $v->telephone }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="padding:16px 24px; text-align:center;">
                            <a href="{{ route('admin.biens.show', $v->bien->id) }}" style="text-decoration:none;">
                                <p style="font-size:13px; font-weight:900; color:#ff5e14; margin-bottom:2px; font-family:monospace;">{{ $v->bien->reference }}</p>
                                <p style="font-size:10px; color:#9ca3af; font-weight:800; text-transform:uppercase;">{{ $v->bien->commune }}</p>
                            </a>
                        </td>
                        <td style="padding:16px 24px; text-align:center;">
                            <p style="font-weight:700; color:#374151; margin-bottom:2px;">{{ \Carbon\Carbon::parse($v->date_visite)->translatedFormat('d/m/Y') }}</p>
                            <p style="font-size:11px; color:#9ca3af; font-weight:600;">{{ $v->heure_visite }}</p>
                        </td>
                        <td style="padding:16px 24px; text-align:center;">
                            @if($v->statut == 'en_attente')
                                <span style="display:inline-block; padding:4px 12px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; background:#fff7ed; color:#ff5e14;">En attente</span>
                            @else
                                <span style="display:inline-block; padding:4px 12px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; background:#f0fdf4; color:#16a34a;">Planifiée</span>
                            @endif
                        </td>
                        <td style="padding:16px 24px; text-align:center;">
                            <div style="display:flex; justify-content:center; gap:8px;">
                                <button type="button" 
                                    onclick="confirmVisite({{ $v->id }}, '{{ $v->date_visite }}', '{{ $v->heure_visite }}', '{{ $v->statut }}')"
                                    style="width:34px; height:34px; border:none; border-radius:10px; background:{{ $v->statut == 'en_attente' ? '#ff5e14' : '#02245b' }}; color:white; cursor:pointer; transition:all 0.3s; display:flex; align-items:center; justify-content:center;"
                                    title="{{ $v->statut == 'en_attente' ? 'Confirmer le RDV' : 'Modifier le RDV' }}">
                                    <i class="fa-solid {{ $v->statut == 'en_attente' ? 'fa-calendar-check' : 'fa-calendar-day' }}"></i>
                                </button>

                                @if($v->statut == 'confirmee')
                                    <form action="{{ route('admin.visites.terminer', $v->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="width:34px; height:34px; border:none; border-radius:10px; background:#16a34a; color:white; cursor:pointer; transition:all 0.3s; display:flex; align-items:center; justify-content:center;" title="Marquer comme effectuée">
                                            <i class="fa-solid fa-check-double"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.visites.annuler', $v->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="width:34px; height:34px; border:none; border-radius:10px; background:#ef4444; color:white; cursor:pointer; transition:all 0.3s; display:flex; align-items:center; justify-content:center;" title="Annuler">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>

                                <form id="form-confirmer-{{ $v->id }}" action="{{ route('admin.visites.confirmer', $v->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="date_visite" id="input-date-{{ $v->id }}">
                                    <input type="hidden" name="heure_visite" id="input-heure-{{ $v->id }}">
                                    <input type="hidden" name="motif" id="input-motif-{{ $v->id }}">
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:60px 24px; text-align:center;">
                            <div style="width:60px; height:60px; border-radius:50%; background:#f9fafb; color:#d1d5db; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                                <i class="fa-solid fa-calendar-xmark" style="font-size:24px;"></i>
                            </div>
                            <p style="font-size:14px; font-weight:700; color:#374151;">Aucune demande de visite</p>
                            <p style="font-size:12px; color:#9ca3af; font-weight:500;">Les nouvelles demandes apparaîtront ici.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($visites->hasPages())
        <div style="padding:16px 24px; border-top:1px solid #f3f4f6; background:#fcfcfc;">
            {{ $visites->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function confirmVisite(id, date, heure, statut) {
        const isPlanifiee = statut === 'confirmee';
        
        Swal.fire({
            title: isPlanifiee ? 'Modifier le rendez-vous ?' : 'Confirmer la visite ?',
            text: `Le RDV est ${isPlanifiee ? 'actuellement' : 'prévu'} pour le ${date} à ${heure}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: isPlanifiee ? 'Garder tel quel' : 'Oui, confirmer tel quel',
            cancelButtonText: isPlanifiee ? 'Changer la date/heure' : 'Non, modifier le RDV',
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#ff5e14',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                if(!isPlanifiee) {
                    document.getElementById('form-confirmer-' + id).submit();
                }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Formulaire de modification
                Swal.fire({
                    title: 'Modifier le rendez-vous',
                    html: `
                        <div style="text-align:left; display:flex; flex-direction:column; gap:16px;">
                            <div>
                                <label style="display:block; font-size:11px; font-weight:800; color:#374151; margin-bottom:8px; text-transform:uppercase;">Nouvelle date</label>
                                <input type="date" id="swal-date" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:8px; font-size:13px;" value="${date}">
                            </div>
                            <div>
                                <label style="display:block; font-size:11px; font-weight:800; color:#374151; margin-bottom:8px; text-transform:uppercase;">Nouvelle heure</label>
                                <input type="time" id="swal-heure" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:8px; font-size:13px;" value="${heure}">
                            </div>
                            <div>
                                <label style="display:block; font-size:11px; font-weight:800; color:#374151; margin-bottom:8px; text-transform:uppercase;">Motif ${isPlanifiee ? 'du changement' : 'du report'}</label>
                                <textarea id="swal-motif" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:8px; font-size:13px; min-height:80px;" placeholder="Ex: Agent indisponible, demande du client..."></textarea>
                            </div>
                        </div>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Mettre à jour & Envoyer SMS',
                    cancelButtonText: 'Annuler',
                    confirmButtonColor: '#02245b',
                    preConfirm: () => {
                        const newDate = document.getElementById('swal-date').value;
                        const newHeure = document.getElementById('swal-heure').value;
                        const motif = document.getElementById('swal-motif').value;

                        if (!newDate || !newHeure || !motif) {
                            Swal.showValidationMessage('Veuillez remplir tous les champs.');
                            return false;
                        }
                        return { newDate, newHeure, motif };
                    }
                }).then((modifyResult) => {
                    if (modifyResult.isConfirmed) {
                        document.getElementById('input-date-' + id).value = modifyResult.value.newDate;
                        document.getElementById('input-heure-' + id).value = modifyResult.value.newHeure;
                        document.getElementById('input-motif-' + id).value = modifyResult.value.motif;
                        document.getElementById('form-confirmer-' + id).submit();
                    }
                });
            }
        });
    }
</script>
@endpush
