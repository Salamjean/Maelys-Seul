<?php

namespace App\Http\Controllers\Locataire\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\YellikaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    protected $yellika;

    public function __construct(YellikaService $yellika)
    {
        $this->yellika = $yellika;
    }

    public function showForgotForm()
    {
        return view('locataire.auth.forgot_password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'login' => 'required',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('contact', $request->login)
            ->first();

        if (!$user) {
            return back()->with('error', "Aucun compte trouvé avec cet identifiant.");
        }

        // Générer code à 4 chiffres
        $code = rand(1000, 9999);
        $user->reset_password_code = $code;
        $user->reset_password_expires_at = Carbon::now()->addMinutes(15);
        $user->save();

        // Envoyer par Email si dispo
        if ($user->email) {
            try {
                Mail::send([], [], function ($message) use ($user, $code) {
                    $message->to($user->email)
                        ->subject('Code de réinitialisation - Maelys Immobilier')
                        ->html("
                            <div style='font-family: Arial, sans-serif; text-align: center; padding: 40px;'>
                                <h1 style='color: #02245b;'>MAELYS-IMO</h1>
                                <p>Votre code de réinitialisation de mot de passe est :</p>
                                <div style='font-size: 32px; font-weight: 800; color: #ff5e14; letter-spacing: 10px; margin: 20px 0;'>{$code}</div>
                                <p>Ce code est valable pendant 15 minutes.</p>
                            </div>
                        ");
                });
            } catch (\Exception $e) { \Log::error($e->getMessage()); }
        }

        // Envoyer par SMS si dispo
        if ($user->contact) {
            $smsMessage = "CODE MAELYS-IMO: Votre code de reinitialisation est {$code}. Valable 15 min.";
            $this->yellika->send($user->contact, $smsMessage);
        }

        return redirect()->route('locataire.password.verify', ['user_id' => $user->id])
            ->with('success', "Le code de réinitialisation a été envoyé.");
    }

    public function showVerifyForm(Request $request)
    {
        $userId = $request->user_id;
        return view('locataire.auth.verify_reset_code', compact('userId'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'required|numeric',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->reset_password_code != $request->code) {
            return back()->with('error', "Code incorrect.");
        }

        if (Carbon::now()->isAfter($user->reset_password_expires_at)) {
            return back()->with('error', "Le code a expiré.");
        }

        return redirect()->route('locataire.password.reset_form', ['user_id' => $user->id, 'token' => Hash::make($user->reset_password_code)])
            ->with('success', "Code validé. Veuillez choisir un nouveau mot de passe.");
    }

    public function showResetForm(Request $request)
    {
        $userId = $request->user_id;
        return view('locataire.auth.reset_password', compact('userId'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($request->user_id);
        
        $user->password = Hash::make($request->password);
        $user->reset_password_code = null;
        $user->reset_password_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('success', "Mot de passe réinitialisé avec succès.");
    }
}
