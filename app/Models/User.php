<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'password', 'prenoms', 'contact', 'profession', 'adresse', 
    'piece_identite', 'attestation_travail', 'bulletin_salaire',
    'doc_extra_1', 'doc_extra_2', 'doc_extra_3',
    'contrat_bail', 'bien_id', 'contract_start_date', 'role',
    'configuration_code', 'configuration_token', 'loyer_mensuel', 'added_by'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
