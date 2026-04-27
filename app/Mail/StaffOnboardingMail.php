<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffOnboardingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $url;

    public function __construct(Admin $member)
    {
        $this->member = $member;
        $this->url = route('admin.onboarding', ['token' => $member->onboarding_token]);
    }

    public function envelope(): Envelope
    {
        $roleLabel = $this->member->role === 'comptable' ? 'Comptable' : 'Agent de Recouvrement';
        return new Envelope(
            subject: "Bienvenue chez MAELYS-IMO : Activez votre compte $roleLabel",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.staff_onboarding',
        );
    }
}
