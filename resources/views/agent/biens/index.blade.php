@extends('agent.layouts.app')

@section('title', 'Liste des biens')
@section('page-title', 'Liste des biens')

@section('content')

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Search Bar --}}
    <div style="background:white; padding:20px; border-radius:14px; margin-bottom:24px; box-shadow:0 1px 4px rgba(0,0,0,0.06);">
        <form action="{{ route('agent.biens.index') }}" method="GET" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:15px; align-items:end;">
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#374151; margin-bottom:6px; text-transform:uppercase;">Référence</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ex: A123BC" 
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:13px; outline:none; transition:border-color 0.2s;"
                       onfocus="this.style.borderColor='#ff5e14'">
            </div>

            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#374151; margin-bottom:6px; text-transform:uppercase;">Type de bien</label>
                <select name="type" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:13px; outline:none; background:white;">
                    <option value="">Tous les types</option>
                    @foreach (['appartement' => 'Appartement', 'maison' => 'Maison', 'bureau' => 'Bureau'] as $val => $lbl)
                        <option value="{{ $val }}" {{ request('type') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#374151; margin-bottom:6px; text-transform:uppercase;">Commune</label>
                <input type="text" name="commune" value="{{ request('commune') }}" placeholder="Toute commune" 
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:13px; outline:none;">
            </div>

            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#374151; margin-bottom:6px; text-transform:uppercase;">Statut</label>
                <select name="statut" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:13px; outline:none; background:white;">
                    <option value="">Tous les statuts</option>
                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    <option value="loue" {{ request('statut') == 'loue' ? 'selected' : '' }}>Loué</option>
                </select>
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" style="flex:1; padding:10px; background:#02245b; color:white; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; transition:background 0.2s;" onmouseover="this.style.background='#011a43'">
                    <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                </button>
                <a href="{{ route('agent.biens.index') }}" style="padding:10px; background:#f3f4f6; color:#374151; border-radius:8px; text-decoration:none; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-rotate-right"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div style="background:white; border-radius:14px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.06);">
        @if ($biens->isEmpty())
            <div style="padding:60px; text-align:center;">
                <i class="fa-solid fa-house-chimney"
                    style="font-size:48px; color:#e5e7eb; margin-bottom:16px; display:block;"></i>
                <p style="font-size:15px; font-weight:600; color:#9ca3af;">Aucun bien enregistré pour l'instant.</p>
                <a href="{{ route('agent.biens.create') }}"
                    style="display:inline-flex; align-items:center; gap:8px; margin-top:16px; padding:10px 22px; background:#ff5e14; color:white; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none;">
                    <i class="fa-solid fa-plus"></i> Ajouter un bien
                </a>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                    <thead>
                        <tr style="background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                            <th style="padding:12px 16px; text-align:center; font-weight:600; color:#374151;">Photo</th>
                            <th style="padding:12px 16px; text-align:center; font-weight:600; color:#374151;">Référence</th>
                            <th style="padding:12px 16px; text-align:center; font-weight:600; color:#374151;">Type</th>
                            <th style="padding:12px 16px; text-align:center; font-weight:600; color:#374151;">Commune</th>
                            <th style="padding:12px 16px; text-align:center; font-weight:600; color:#374151;">Superficie</th>
                            <th style="padding:12px 16px; text-align:center; font-weight:600; color:#374151;">Loyer</th>
                            <th style="padding:12px 16px; text-align:center; font-weight:600; color:#374151;">Statut</th>
                            <th style="padding:12px 16px; text-align:center; font-weight:600; color:#374151;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($biens as $bien)
                            <tr style="border-bottom:1px solid #f3f4f6; transition:background 0.15s;"
                                onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                                <td style="padding:12px 16px; text-align:center; display:flex; justify-content:center;">
                                    <img src="{{ Storage::url($bien->photo_principale) }}" alt=""
                                        style="width:52px; height:44px; object-fit:cover; border-radius:8px; border:1px solid #e5e7eb;">
                                </td>
                                <td style="padding:12px 16px; text-align:center; color:#02245b; font-weight:700;">
                                    <span
                                        style="font-family: monospace; font-weight: 700; color: #ff5e14; background: #fff7ed; padding: 4px 8px; border-radius: 6px;">
                                        {{ $bien->reference }}
                                    </span>
                                </td>
                                <td style="padding:12px 16px; text-align:center; color:#374151; font-weight:500;">
                                    <span
                                        style="display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; text-transform:capitalize; background:rgba(2,36,91,0.08); color:#02245b;">
                                        {{ $bien->type_bien }}
                                    </span>
                                </td>
                                <td style="padding:12px 16px; text-align:center; color:#374151; font-weight:500;">{{ $bien->commune }}</td>
                                <td style="padding:12px 16px; text-align:center; color:#6b7280;">{{ number_format($bien->superficie, 0, ',', ' ') }} m²</td>
                                <td style="padding:12px 16px; text-align:center; font-weight:700; color:#02245b;">
                                    {{ number_format($bien->loyer_mensuel, 0, ',', ' ') }} <small>FCFA</small>
                                </td>
                                <td style="padding:12px 16px; text-align:center;">
                                    @php
                                        $statusStyles = [
                                            'actif' => 'background:#f0fdf4; color:#16a34a;',
                                            'inactif' => 'background:#f9fafb; color:#9ca3af;',
                                            'loue' => 'background:#fff7ed; color:#ff5e14;',
                                        ];
                                    @endphp
                                    <span
                                        style="display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; {{ $statusStyles[$bien->statut] ?? '' }}">
                                        {{ ucfirst($bien->statut) }}
                                    </span>
                                </td>
                                <td style="padding:12px 16px; text-align:center;">
                                    <div style="display:flex; justify-content:center; gap:8px;">
                                        @if($bien->statut !== 'loue')
                                            <a href="{{ route('agent.locataires.create', ['bien_id' => $bien->id]) }}" title="Ajouter un locataire"
                                                style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:8px; background:#e0f2fe; color:#0ea5e9; text-decoration:none; transition:all 0.2s;"
                                                onmouseover="this.style.background='#bae6fd'"><i class="fa-solid fa-user-plus"
                                                    style="font-size:14px;"></i></a>
                                        @endif
                                        <a href="{{ route('agent.biens.show', $bien->id) }}" title="Voir"
                                            style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:8px; background:#f3f4f6; color:#4b5563; text-decoration:none; transition:all 0.2s;"
                                            onmouseover="this.style.background='#e5e7eb'"><i class="fa-solid fa-eye"
                                                style="font-size:14px;"></i></a>
                                        <a href="{{ route('agent.biens.edit', $bien->id) }}" title="Modifier"
                                            style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:8px; background:#fff7ed; color:#ff5e14; text-decoration:none; transition:all 0.2s;"
                                            onmouseover="this.style.background='#ffedd5'"><i
                                                class="fa-solid fa-pen-to-square" style="font-size:14px;"></i></a>
                                        <form action="{{ route('agent.biens.destroy', $bien->id) }}" method="POST" id="delete-form-{{ $bien->id }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" title="Supprimer" 
                                                onclick="confirmDelete({{ $bien->id }})"
                                                style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:8px; background:#fef2f2; color:#ef4444; border:none; cursor:pointer; transition:all 0.2s;"
                                                onmouseover="this.style.background='#fee2e2'"><i class="fa-solid fa-trash"
                                                    style="font-size:14px;"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($biens->hasPages())
                <div style="padding:16px 20px; border-top:1px solid #f3f4f6;">
                    {{ $biens->links() }}
                </div>
            @endif
        @endif
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Archiver ce bien ?',
                text: "Le bien ne sera plus visible sur le site public mais restera dans vos archives.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff5e14',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Oui, archiver',
                cancelButtonText: 'Annuler',
                borderRadius: '15px'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>

@endsection
