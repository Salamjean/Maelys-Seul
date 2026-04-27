<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'priority',
        'status',
        'category',
        'admin_response',
        'responded_at',
        'is_read_by_user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
