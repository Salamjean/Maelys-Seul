<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
        return view('admin.auth.forgot_password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'login' => 'required',
        ]);

        $admin = Admin::where('email', $request->login)
            ->orWhere('contact', $request->login)
            ->first();

        if (!$admin) {
            return back()->with('error', "Aucun compte trouvé avec cet identifiant.");
        }

        // Sécurité : On ne permet pas la réinitialisation automatique pour le rôle 'admin' (Super Admin)
        if ($admin->role === 'admin') {
            return back()->with('error', "Pour des raisons de sécurité, le mot de passe du Super Administrateur ne peut pas être réinitialisé de cette manière. Veuillez contacter le support technique.");
        }

        // Générer code à 4 chiffres
        $code = rand(1000, 9999);
        $admin->reset_password_code = $code;
        $admin->reset_password_expires_at = Carbon::now()->addMinutes(15);
        $admin->save();

        // Envoyer par Email
        try {
            Mail::send([], [], function ($message) use ($admin, $code) {
                $message->to($admin->email)
                    ->subject('Code de réinitialisation Admin - Maelys-imo')
                    ->html("
                        <div style='font-family: Arial, sans-serif; text-align: center; padding: 40px; background: #f4f7fa; border-radius: 20px;'>
                            <h1 style='color: #02245b;'>MAELYS-IMO</h1>
                            <p style='color: #6b7280;'>Vous avez demandé une réinitialisation de votre mot de passe administrateur.</p>
                            <p>Votre code de sécurité est :</p>
                            <div style='font-size: 36px; font-weight: 900; color: #ff5e14; letter-spacing: 12px; margin: 30px 0; background: white; display: inline-block; padding: 15px 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);'>{$code}</div>
                            <p style='font-size: 13px; color: #9ca3af;'>Ce code expirera dans 15 minutes.</p>
                        </div>
                    ");
            });
        } catch (\Exception $e) { 
            \Log::error("Erreur Mail Admin Forgot: " . $e->getMessage()); 
        }

        // Envoyer par SMS si contact dispo
        if (isset($admin->contact) && $admin->contact) {
            $smsMessage = "ADMIN MAELYS-IMO: Votre code de reinitialisation est {$code}. Valable 15 min.";
            $this->yellika->send($admin->contact, $smsMessage);
        }

        return redirect()->route('admin.password.verify', ['admin_id' => $admin->id])
            ->with('success', "Un code de sécurité a été envoyé à votre adresse email.");
    }

    public function showVerifyForm(Request $request)
    {
        $adminId = $request->admin_id;
        return view('admin.auth.verify_reset_code', compact('adminId'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'code' => 'required|numeric',
        ]);

        $admin = Admin::findOrFail($request->admin_id);

        if ($admin->reset_password_code != $request->code) {
            return back()->with('error', "Le code saisi est incorrect.");
        }

        if (Carbon::now()->isAfter($admin->reset_password_expires_at)) {
            return back()->with('error', "Ce code a expiré. Veuillez recommencer la procédure.");
        }

        return redirect()->route('admin.password.reset_form', ['admin_id' => $admin->id, 'token' => Hash::make($admin->reset_password_code)])
            ->with('success', "Code validé. Vous pouvez maintenant modifier votre mot de passe.");
    }

    public function showResetForm(Request $request)
    {
        $adminId = $request->admin_id;
        return view('admin.auth.reset_password', compact('adminId'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'password' => 'required|min:6|confirmed',
        ]);

        $admin = Admin::findOrFail($request->admin_id);
        
        $admin->password = Hash::make($request->password);
        $admin->reset_password_code = null;
        $admin->reset_password_expires_at = null;
        $admin->save();

        return redirect()->route('admin.login')->with('success', "Votre mot de passe a été réinitialisé avec succès.");
    }
}
