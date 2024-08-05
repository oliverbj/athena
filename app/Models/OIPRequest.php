<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\BusinessType;

class OIPRequest extends Model
{
    use HasFactory;

    protected $table = 'oip_requests';

    protected $fillable = [
        'user_id',
        'business_type',
        'organization_code',
        'status',
        'expire_at',
        'origin',
        'destination',
        'mode'

    ];

    protected $casts = [
        'expire_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

  
}
