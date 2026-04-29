<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatLieuDetail extends Model
{
    use HasFactory;

    protected $table = 'etat_lieux_details';

    protected $fillable = [
        'etat_lieu_id',
        'piece',
        'element',
        'etat',
        'observations',
    ];

    public function etatLieu()
    {
        return $this->belongsTo(EtatLieu::class);
    }
}
