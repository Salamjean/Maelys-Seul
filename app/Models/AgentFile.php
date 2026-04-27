<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'type',
        'agent_id',
    ];

    public function agent()
    {
        return $this->belongsTo(Admin::class, 'agent_id');
    }
}
