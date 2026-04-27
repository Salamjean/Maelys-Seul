<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bien extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'biens';

    protected $fillable = [
        'reference',
        'type_bien',
        'typologie',
        'superficie',
        'commune',
        'nb_pieces',
        'nb_toilettes',
        'garage',
        'type_utilisation',
        'loyer_mensuel',
        'avance',
        'caution',
        'frais_agence',
        'montant_total',
        'date_paiement',
        'photo_principale',
        'photos_supplementaires',
        'video_3d',
        'description',
        'google_maps_url',
        'statut',
        'added_by',
    ];

    protected $casts = [
        'photos_supplementaires' => 'array',
        'garage'                 => 'boolean',
    ];
    public function locataire()
    {
        return $this->hasOne(User::class);
    }
}
