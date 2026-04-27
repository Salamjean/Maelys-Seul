<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visite extends Model
{
    use HasFactory;

    protected $fillable = [
        'bien_id',
        'nom',
        'prenom',
        'email',
        'telephone',
        'date_visite',
        'heure_visite',
        'message',
        'statut',
    ];

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }
}
