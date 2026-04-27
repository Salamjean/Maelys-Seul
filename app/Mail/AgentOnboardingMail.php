<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgentOnboardingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agent;
    public $url;

    public function __construct(Admin $agent)
    {
        $this->agent = $agent;
        $this->url = route('admin.onboarding', ['token' => $agent->onboarding_token]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue chez MAELYS-IMO : Activez votre compte Agent',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.agent_onboarding',
        );
    }
}
