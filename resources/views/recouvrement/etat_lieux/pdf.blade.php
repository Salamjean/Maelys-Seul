<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>État des Lieux - {{ ucfirst($etatLieu->type) }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #02245b;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #02245b;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            color: #ff5e14;
            margin: 5px 0 0;
            font-weight: bold;
            font-size: 14px;
        }
        .info-section {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .info-section td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        .info-label {
            background-color: #f8fafc;
            font-weight: bold;
            color: #02245b;
            width: 25%;
        }
        .section-title {
            color: #ff5e14;
            font-size: 18px;
            border-bottom: 1px solid #ff5e14;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        table.details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.details th, table.details td {
            border: 1px solid #cbd5e1;
            padding: 12px;
            text-align: left;
            font-size: 13px;
        }
        table.details th {
            background-color: #02245b;
            color: #fff;
            text-transform: uppercase;
            font-size: 12px;
        }
        .etat-badge {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        .remarques {
            background-color: #f8fafc;
            padding: 15px;
            border-left: 4px solid #ff5e14;
            font-size: 13px;
            margin-bottom: 40px;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-container img {
            width: 100px;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('assets/images/maelys.jpg') }}" alt="Maelys-IMO">
        </div>
        <h1>ÉTAT DES LIEUX : {{ strtoupper($etatLieu->type) }}</h1>
        <p>Référence du Document : EL-{{ $etatLieu->id }}-{{ date('Y') }}</p>
    </div>

    <div class="section-title">Informations sur l'Inspection</div>
    <table class="info-section">
        <tr>
            <td class="info-label">Date de l'état :</td>
            <td>{{ $etatLieu->date_etat_lieux ? $etatLieu->date_etat_lieux->format('d/m/Y') : 'N/A' }}</td>
            <td class="info-label">Type d'État :</td>
            <td style="text-transform: uppercase; font-weight: bold; color: #ff5e14;">{{ $etatLieu->type }}</td>
        </tr>
        <tr>
            <td class="info-label">Agent Responsable :</td>
            <td colspan="3">{{ $agent->name }} {{ $agent->prenoms }}</td>
        </tr>
    </table>

    <div class="section-title">Informations sur le Bien & Locataire</div>
    <table class="info-section">
        <tr>
            <td class="info-label">Référence du Bien :</td>
            <td>{{ $etatLieu->bien->reference }} ({{ $etatLieu->bien->commune }})</td>
            <td class="info-label">Type de Bien :</td>
            <td>{{ ucfirst($etatLieu->bien->type_bien) }} - {{ $etatLieu->bien->typologie ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Nom du Locataire :</td>
            <td>{{ $etatLieu->user->name }} {{ $etatLieu->user->prenoms }}</td>
            <td class="info-label">Contact Locataire :</td>
            <td>{{ $etatLieu->user->contact ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">Relevés de Compteurs & Remise de Clés</div>
    <table class="info-section">
        <tr>
            <td class="info-label">Index Eau :</td>
            <td>{{ $etatLieu->compteur_eau ?: 'Non renseigné' }}</td>
            <td class="info-label">Index Électricité :</td>
            <td>{{ $etatLieu->compteur_electricite ?: 'Non renseigné' }}</td>
        </tr>
        <tr>
            <td class="info-label">Nombre de clés :</td>
            <td colspan="3">{{ $etatLieu->nombre_cles !== null ? $etatLieu->nombre_cles . ' clé(s) remise(s)' : 'Non renseigné' }}</td>
        </tr>
    </table>

    <div class="section-title">Détail par Pièce</div>
    
    @foreach($etatLieu->details->groupBy('piece') as $piece => $details)
    <h3 style="color: #02245b; margin-top: 20px; margin-bottom: 10px; font-size: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">{{ $piece }}</h3>
    <table class="details">
        <thead>
            <tr>
                <th width="30%">Élément</th>
                <th width="20%">État</th>
                <th width="50%">Observations</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $detail)
            <tr>
                <td style="font-weight: bold; color: #333;">{{ $detail->element }}</td>
                <td>
                    <span class="etat-badge">
                        @if(strtolower($detail->etat) == 'bon')
                            Bon
                        @elseif(strtolower($detail->etat) == 'moyen')
                            Moyen
                        @elseif(strtolower($detail->etat) == 'mauvais')
                            <span style="color: red;">Mauvais</span>
                        @else
                            {{ ucfirst(str_replace('_', ' ', $detail->etat)) }}
                        @endif
                    </span>
                </td>
                <td style="{{ strtolower($detail->etat) == 'mauvais' ? 'color: red; font-weight: bold;' : '' }}">
                    {{ $detail->observations ?: '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach

    <div class="section-title">Conclusion & Remarques Globales</div>
    <div class="remarques">
        {{ $etatLieu->remarques_globales ?: 'Aucune remarque globale n\'a été formulée pour cet état des lieux.' }}
    </div>

</body>
</html>
