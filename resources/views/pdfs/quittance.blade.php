<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1a202c;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        /* Corporate Stripe */
        .top-stripe {
            height: 12px;
            background: #02245b;
            width: 100%;
            border-bottom: 3px solid #ff5e14;
        }

        .container {
            padding: 45px;
            position: relative;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: #f9fafb;
            font-weight: 900;
            z-index: -1;
            text-transform: uppercase;
            letter-spacing: 10px;
        }

        /* Header Area */
        .header-table {
            width: 100%;
            margin-bottom: 40px;
        }

        .logo-box {
            width: 65px;
            height: 65px;
            border-radius: 14px;
        }

        .logo-text {
            font-size: 26px;
            font-weight: 900;
            color: #02245b;
            font-style: italic;
            margin-left: 10px;
        }

        .receipt-status {
            text-align: right;
            vertical-align: middle;
        }

        .status-badge {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            padding: 10px 25px;
            border-radius: 12px;
            font-size: 20px;
            font-weight: 900;
            text-transform: uppercase;
            border: 2px solid #166534;
            transform: rotate(-12deg);
            opacity: 0.8;
            margin-top: 10px;
        }

        /* Document Title */
        .doc-title {
            border-left: 5px solid #ff5e14;
            padding-left: 15px;
            margin-bottom: 35px;
        }

        .doc-title h2 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            color: #02245b;
            letter-spacing: 1px;
        }

        .doc-title p {
            margin: 3px 0 0;
            font-size: 11px;
            color: #9ca3af;
            font-weight: bold;
        }

        /* Info Grid */
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .info-cell {
            width: 50%;
            vertical-align: top;
            padding: 20px;
            background: #fcfcfc;
            border: 1px solid #f3f4f6;
            border-radius: 20px;
        }

        .label {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            color: #ff5e14;
            margin-bottom: 8px;
            display: block;
        }

        .value {
            font-size: 13px;
            font-weight: bold;
            color: #0a1931;
        }

        /* Main Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .data-table th {
            background: #02245b;
            color: #ffffff;
            padding: 14px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }

        .data-table td {
            padding: 20px 14px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
            font-weight: bold;
        }

        /* Summary Card */
        .summary-card {
            float: right;
            width: 280px;
            background: #02245b;
            color: white;
            padding: 25px;
            border-radius: 25px;
            margin-top: 10px;
            box-shadow: 0 10px 20px rgba(2, 36, 91, 0.15);
        }

        .sum-label {
            font-size: 10px;
            text-transform: uppercase;
            opacity: 0.7;
            letter-spacing: 1px;
        }

        .sum-value {
            font-size: 22px;
            font-weight: 900;
            display: block;
            margin-top: 5px;
        }

        /* QR Certification Zone */
        .cert-zone {
            margin-top: 80px;
            clear: both;
            border-top: 1px dashed #e5e7eb;
            padding-top: 40px;
        }

        .qr-box {
            float: left;
            padding: 12px;
            background: #fff;
            border: 1px solid #f3f4f6;
            border-radius: 18px;
        }

        .cert-text {
            float: left;
            margin-left: 25px;
            width: 400px;
            padding-top: 10px;
        }

        .cert-text h4 {
            margin: 0;
            font-size: 12px;
            color: #02245b;
            text-transform: uppercase;
        }

        .cert-text p {
            margin: 5px 0 0;
            font-size: 10px;
            color: #6b7280;
            line-height: 1.5;
        }

        .footer {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #cbd5e1;
            font-weight: bold;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="top-stripe"></div>
    <div class="watermark">Quittance</div>

    <div class="container">
        <!-- HEADER -->
        <table class="header-table">
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td><img src="{{ public_path('assets/images/maelys.jpg') }}" class="logo-box"></td>
                            <td class="logo-text italic">MAELYS - <span style="color:#ff5e14">IMMOBILIER</span></td>
                        </tr>
                    </table>
                </td>
                <td class="receipt-status">
                    <span class="status-badge">Paiement Validé</span>
                </td>
            </tr>
        </table>

        <!-- TITLE -->
        <div class="doc-title">
            <h2>Quittance de Loyer</h2>
            <p>Référence documentaire : {{ $payment->reference }}</p>
        </div>

        <!-- INFO BLOCKS -->
        <table class="info-grid">
            <tr>
                <td class="info-cell" style="border-right: 15px solid white;">
                    <span class="label italic">Bailleur / Agence</span>
                    <div class="value">MAELYS IMMOBILIER Sarl</div>
                    <div style="font-size: 11px; color:#6b7280; font-weight: normal; margin-top:3px;">
                        Abidjan, Côte d'Ivoire<br>
                        RCCM ABJ/23 B 12345
                    </div>
                </td>
                <td class="info-cell">
                    <span class="label italic">Locataire Bénéficiaire</span>
                    <div class="value">{{ $payment->user->name }} {{ $payment->user->prenoms }}</div>
                    <div style="font-size: 11px; color:#6b7280; font-weight: normal; margin-top:3px;">
                        Bien : <strong>{{ $payment->user->bien->reference ?? 'Logement' }}</strong><br>
                        {{ $payment->user->bien->commune ?? 'Zone Abidjan' }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- DATA TABLE -->
        <table class="data-table">
            <thead>
                <tr>
                    <th width="50%">Désignation</th>
                    <th>Période de loyer</th>
                    <th style="text-align: right">Montant Net</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Encaissement Loyer Mensuel<br>
                        <span style="font-size: 10px; color:#9ca3af; font-weight: normal;">Modalité : 
                            @if($payment->payment_method === 'mobile')
                                Mobile Money ({{ ucfirst($payment->mobile_network ?? 'Wave') }})
                            @elseif($payment->payment_method === 'bank')
                                Virement Bancaire
                            @else
                                Espèces / Cash
                            @endif
                            • Ref: {{ $payment->reference }}</span>
                    </td>
                    <td><span style="color:#ff5e14">{{ $payment->periode_couverte }}</span></td>
                    <td style="text-align: right">{{ number_format($payment->amount, 0, ',', ' ') }} CFA</td>
                </tr>
            </tbody>
        </table>

        <!-- SUMMARY CARD -->
        <div class="summary-card">
            <span class="sum-label italic">Total Net Encaissé</span>
            <span class="sum-value">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</span>
            <div style="font-size: 9px; margin-top: 10px; opacity: 0.6; font-weight: bold;">Montant arrêté à la date du
                {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y') : now()->format('d/m/Y') }}</div>
        </div>
        <div class="clear"></div>

        <!-- SECURITY ZONE -->
        <div class="cert-zone">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="150">
                        <div class="qr-box"
                            style="line-height: 0; padding: 5px; background: #fff; border: 1px solid #f3f4f6; border-radius: 15px;">
                            <img src="data:image/svg+xml;base64,{{ $qrCode }}" style="width: 130px; height: 130px;">
                        </div>
                    </td>
                    <td style="padding-left: 30px; vertical-align: middle;">
                        <h4 class="italic"
                            style="margin-bottom: 8px; font-size: 16px; letter-spacing: 1px; color: #02245b;">
                            CERTIFICATION NUMÉRIQUE</h4>
                        <p style="margin: 0; font-size: 11px; color: #4b5563; line-height: 1.6; max-width: 350px;">
                            Ce document est une quittance de loyer officielle générée par <strong>MAELYS-IMO</strong>.
                            L'authenticité et l'intégrité de ce reçu sont garanties par le certificat QR ci-contre.
                            Scan de vérification obligatoire pour les tiers.
                        </p>
                        <div
                            style="margin-top: 12px; font-size: 9px; color: #9ca3af; text-transform: uppercase; font-weight: bold; border-top: 1px solid #f9fafb; padding-top: 8px;">
                            Certificat de validité • Généré le {{ now()->format('d/m/Y à H:i') }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            MAELYS IMMOBILIER - Gestion Locative & Transactions • Abidjan, Côte d'Ivoire • www.immoseul.ci
        </div>
    </div>
</body>

</html>