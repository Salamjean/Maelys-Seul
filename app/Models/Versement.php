<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versement extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'comptable_id',
        'amount',
        'reference',
        'notes',
    ];

    public function agent()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'agent_id');
    }

    public function comptable()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'comptable_id');
    }
}
