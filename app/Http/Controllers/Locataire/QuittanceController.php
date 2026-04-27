<?php

namespace App\Http\Controllers\Locataire;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QuittanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');

        $query = Payment::where('user_id', $user->id)
            ->where('status', 'completed');

        if ($search) {
            // Traduction inverse pour la recherche (si l'utilisateur tape "Avril", on cherche aussi "April")
            $searchEn = str_replace(
                ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                ucfirst(strtolower($search))
            );

            $query->where(function($q) use ($search, $searchEn) {
                $q->where('reference', 'LIKE', "%{$search}%")
                  ->orWhere('periode_couverte', 'LIKE', "%{$search}%")
                  ->orWhere('periode_couverte', 'LIKE', "%{$searchEn}%");
            });
        }

        $quittances = $query->latest('paid_at')->paginate(6);

        return view('locataire.quittances', compact('quittances', 'search'));
    }

    public function download(Payment $payment)
    {
        // Sécurité : on vérifie que la quittance appartient bien au locataire connecté
        if ($payment->user_id !== Auth::id() || $payment->status !== 'completed') {
            abort(403);
        }

        // On récupère le contenu du fichier QR code déjà enregistré dans la base (chemin stocké)
        $qrPath = $payment->qr_code;
        if (!empty($qrPath) && \Illuminate\Support\Facades\Storage::disk('public')->exists($qrPath)) {
            $qrContent = \Illuminate\Support\Facades\Storage::disk('public')->get($qrPath);
            $qrCode = base64_encode($qrContent);
        } else {
            // Repli au cas où le fichier n'existe pas (génération à la volée avec les données textuelles)
            $qrData = "MAELYS-IMO - Quittance de Loyer\n" .
                      "Référence: {$payment->reference}\n" .
                      "Locataire: {$payment->user->name} {$payment->user->prenoms}\n" .
                      "Montant: " . number_format($payment->amount, 0, ',', ' ') . " FCFA\n" .
                      "Période: {$payment->periode_couverte}\n" .
                      "Date: " . ($payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i'));
            
            $qrCode = base64_encode(QrCode::size(200)->margin(0)->generate($qrData));
        }

        $pdf = Pdf::loadView('pdfs.quittance', compact('payment', 'qrCode'));

        return $pdf->download("Quittance_{$payment->reference}.pdf");
    }
}
