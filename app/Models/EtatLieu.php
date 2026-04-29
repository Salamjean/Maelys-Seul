<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatLieu extends Model
{
    use HasFactory;

    protected $table = 'etat_lieux';

    protected $fillable = [
        'user_id',
        'bien_id',
        'agent_id',
        'type',
        'statut',
        'otp_code',
        'compteur_eau',
        'compteur_electricite',
        'nombre_cles',
        'remarques_globales',
        'document_pdf',
        'date_etat_lieux',
    ];

    protected $casts = [
        'date_etat_lieux' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    public function agent()
    {
        return $this->belongsTo(Admin::class, 'agent_id');
    }

    public function details()
    {
        return $this->hasMany(EtatLieuDetail::class);
    }
}
