<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OIPBusinessType;

class OIPRequest extends Model
{
    use HasFactory;

    protected $table = 'oip_requests';

    protected $casts = [
        'value_add' => 'array',
        'expire_at' => 'datetime',

    ];

    protected $with = ['statusUser'];

    protected $guarded = [];

    /**
     * An OIPRequest belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An OIPRequest can have a status user (approver / rejecter)
     */
    public function statusUser()
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    


  
}
