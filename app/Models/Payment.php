<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'months_count',
        'periode_couverte',
        'amount',
        'payment_method',
        'reference',
        'verification_code',
        'status',
        'paid_at',
        'qr_code',
        'payment_proof',
        'agent_id'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(Admin::class, 'agent_id');
    }
}
