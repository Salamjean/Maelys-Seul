<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proprietaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenoms',
        'email',
        'contact',
        'lieu_residence',
        'profession',
        'piece_identite_recto',
        'piece_identite_verso',
    ];
}
